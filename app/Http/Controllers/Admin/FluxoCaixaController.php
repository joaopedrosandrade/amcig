<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ContaPagar;
use App\Fornecedor;
use App\CategoriaConta;
use App\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FluxoCaixaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Exibe a página de Contas a Pagar
     *
     * @return \Illuminate\View\View
     */
    public function contasPagar(Request $request)
    {
        $query = ContaPagar::with(['cadastradoPor', 'pagoPor', 'evento', 'fornecedorRelacao', 'categoriaRelacao']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_vencimento', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_vencimento', '<=', $request->data_fim);
        }

        // Por padrão, mostrar todas as contas (não filtrar por data)
        // Usuário pode usar filtros se quiser período específico
        
        $contas = $query->orderBy('data_vencimento', 'desc')->paginate(20);

        // Dados para os filtros
        $eventos = Evento::orderBy('data_evento', 'desc')->get();
        $categorias = CategoriaConta::ativas()->pagar()->orderBy('nome')->get();
        
        // Estatísticas
        $totalPagar = ContaPagar::pendentes()->sum('valor');
        $totalPago = ContaPagar::pagas()->whereMonth('data_pagamento', Carbon::now()->month)->sum('valor');
        $totalVencido = ContaPagar::vencidas()->sum('valor');

        return view('admin.fluxo-caixa.contas-pagar', compact('contas', 'eventos', 'categorias', 'totalPagar', 'totalPago', 'totalVencido'));
    }

    /**
     * Exibe formulário de criação de conta a pagar
     *
     * @return \Illuminate\View\View
     */
    public function createContaPagar()
    {
        $categorias = CategoriaConta::ativas()->pagar()->orderBy('nome')->get();
        $fornecedores = Fornecedor::ativos()->orderBy('nome')->get();
        $eventos = Evento::orderBy('data_evento', 'desc')->get();

        return view('admin.fluxo-caixa.contas-pagar-create', compact('categorias', 'fornecedores', 'eventos'));
    }

    /**
     * Salva uma nova conta a pagar
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeContaPagar(Request $request)
    {
        $validatedData = $request->validate([
            'descricao' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias_contas,id',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'evento_id' => 'nullable|exists:eventos_sistema,id',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'data_competencia' => 'nullable|date',
            'numero_nota_fiscal' => 'nullable|string|max:50',
            'serie_nota_fiscal' => 'nullable|string|max:10',
            'data_emissao_nota' => 'nullable|date',
            'chave_acesso_nfe' => 'nullable|string|max:44',
            'observacoes' => 'nullable|string',
            'parcelado' => 'nullable|boolean',
            'numero_parcela' => 'nullable|integer|min:1',
            'total_parcelas' => 'nullable|integer|min:1',
            'arquivo_nota_fiscal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Pegar dados do fornecedor e categoria para preencher campos antigos (compatibilidade)
        $fornecedor = Fornecedor::find($validatedData['fornecedor_id']);
        $categoria = CategoriaConta::find($validatedData['categoria_id']);
        
        $validatedData['fornecedor'] = $fornecedor->nome;
        $validatedData['cnpj_fornecedor'] = $fornecedor->cnpj;
        $validatedData['telefone_fornecedor'] = $fornecedor->telefone;
        $validatedData['email_fornecedor'] = $fornecedor->email;
        $validatedData['categoria'] = $categoria->nome;

        // Upload do arquivo da nota fiscal
        if ($request->hasFile('arquivo_nota_fiscal')) {
            $arquivo = $request->file('arquivo_nota_fiscal');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $caminhoArquivo = $arquivo->storeAs('notas_fiscais', $nomeArquivo, 'public');
            $validatedData['arquivo_nota_fiscal'] = $caminhoArquivo;
        }

        $validatedData['cadastrado_por'] = Auth::guard('admin')->id();
        $validatedData['status'] = 'pendente';

        ContaPagar::create($validatedData);

        return redirect()->route('admin.fluxo-caixa.contas-pagar')
            ->with('success', 'Conta a pagar cadastrada com sucesso!');
    }

    /**
     * Exibe formulário de edição de conta a pagar
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editContaPagar($id)
    {
        $conta = ContaPagar::findOrFail($id);
        $categorias = CategoriaConta::ativas()->pagar()->orderBy('nome')->get();
        $fornecedores = Fornecedor::ativos()->orderBy('nome')->get();
        $eventos = Evento::orderBy('data_evento', 'desc')->get();

        return view('admin.fluxo-caixa.contas-pagar-edit', compact('conta', 'categorias', 'fornecedores', 'eventos'));
    }

    /**
     * Atualiza uma conta a pagar
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateContaPagar(Request $request, $id)
    {
        $conta = ContaPagar::findOrFail($id);

        $validatedData = $request->validate([
            'descricao' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias_contas,id',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'evento_id' => 'nullable|exists:eventos_sistema,id',
            'valor' => 'required|numeric|min:0',
            'data_vencimento' => 'required|date',
            'data_competencia' => 'nullable|date',
            'numero_nota_fiscal' => 'nullable|string|max:50',
            'serie_nota_fiscal' => 'nullable|string|max:10',
            'data_emissao_nota' => 'nullable|date',
            'chave_acesso_nfe' => 'nullable|string|max:44',
            'observacoes' => 'nullable|string',
            'arquivo_nota_fiscal' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        // Pegar dados do fornecedor e categoria para preencher campos antigos (compatibilidade)
        $fornecedor = Fornecedor::find($validatedData['fornecedor_id']);
        $categoria = CategoriaConta::find($validatedData['categoria_id']);
        
        $validatedData['fornecedor'] = $fornecedor->nome;
        $validatedData['cnpj_fornecedor'] = $fornecedor->cnpj;
        $validatedData['telefone_fornecedor'] = $fornecedor->telefone;
        $validatedData['email_fornecedor'] = $fornecedor->email;
        $validatedData['categoria'] = $categoria->nome;

        // Upload do arquivo da nota fiscal
        if ($request->hasFile('arquivo_nota_fiscal')) {
            // Deletar arquivo antigo se existir
            if ($conta->arquivo_nota_fiscal) {
                Storage::disk('public')->delete($conta->arquivo_nota_fiscal);
            }
            
            $arquivo = $request->file('arquivo_nota_fiscal');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $caminhoArquivo = $arquivo->storeAs('notas_fiscais', $nomeArquivo, 'public');
            $validatedData['arquivo_nota_fiscal'] = $caminhoArquivo;
        }

        $conta->update($validatedData);

        return redirect()->route('admin.fluxo-caixa.contas-pagar')
            ->with('success', 'Conta a pagar atualizada com sucesso!');
    }

    /**
     * Remove uma conta a pagar
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyContaPagar($id)
    {
        $conta = ContaPagar::findOrFail($id);

        // Deletar arquivos associados
        if ($conta->arquivo_nota_fiscal) {
            Storage::disk('public')->delete($conta->arquivo_nota_fiscal);
        }
        if ($conta->comprovante_pagamento) {
            Storage::disk('public')->delete($conta->comprovante_pagamento);
        }

        $conta->delete();

        return redirect()->route('admin.fluxo-caixa.contas-pagar')
            ->with('success', 'Conta a pagar excluída com sucesso!');
    }

    /**
     * Registra o pagamento de uma conta a pagar
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pagarConta(Request $request, $id)
    {
        $conta = ContaPagar::findOrFail($id);

        $validatedData = $request->validate([
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'required|string',
            'valor_pago' => 'required|numeric|min:0',
            'juros' => 'nullable|numeric|min:0',
            'multa' => 'nullable|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0',
            'comprovante_pagamento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Upload do comprovante
        if ($request->hasFile('comprovante_pagamento')) {
            $arquivo = $request->file('comprovante_pagamento');
            $nomeArquivo = time() . '_comprovante_' . $arquivo->getClientOriginalName();
            $caminhoArquivo = $arquivo->storeAs('comprovantes', $nomeArquivo, 'public');
            $validatedData['comprovante_pagamento'] = $caminhoArquivo;
        }

        $validatedData['status'] = 'pago';
        $validatedData['pago_por'] = Auth::guard('admin')->id();

        $conta->update($validatedData);

        return redirect()->route('admin.fluxo-caixa.contas-pagar')
            ->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
     * Buscar fornecedores (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarFornecedores(Request $request)
    {
        $term = $request->get('term', '');
        
        $fornecedores = Fornecedor::ativos()
            ->where(function($query) use ($term) {
                $query->where('nome', 'LIKE', "%{$term}%")
                      ->orWhere('cnpj', 'LIKE', "%{$term}%");
            })
            ->limit(10)
            ->get()
            ->map(function($fornecedor) {
                return [
                    'id' => $fornecedor->id,
                    'text' => $fornecedor->nome_completo,
                    'nome' => $fornecedor->nome,
                    'cnpj' => $fornecedor->cnpj,
                    'telefone' => $fornecedor->telefone,
                    'email' => $fornecedor->email,
                ];
            });

        return response()->json($fornecedores);
    }

    /**
     * Criar novo fornecedor (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFornecedor(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo_pessoa' => 'required|in:fisica,juridica',
                'cpf' => 'nullable|string|max:14',
                'cnpj' => 'nullable|string|max:18',
                'telefone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
            ]);

            $fornecedor = Fornecedor::create($validatedData);

            return response()->json([
                'success' => true,
                'fornecedor' => [
                    'id' => $fornecedor->id,
                    'text' => $fornecedor->nome_completo,
                    'nome' => $fornecedor->nome,
                    'cnpj' => $fornecedor->cnpj,
                    'telefone' => $fornecedor->telefone,
                    'email' => $fornecedor->email,
                ],
                'message' => 'Fornecedor cadastrado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar fornecedor: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Criar nova categoria (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCategoria(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'required|in:pagar,receber',
                'cor' => 'nullable|string|max:7',
                'descricao' => 'nullable|string',
            ]);

            $categoria = CategoriaConta::create($validatedData);

            return response()->json([
                'success' => true,
                'categoria' => [
                    'id' => $categoria->id,
                    'text' => $categoria->nome,
                    'nome' => $categoria->nome,
                ],
                'message' => 'Categoria cadastrada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar categoria: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Exibe a página de Contas a Receber
     *
     * @return \Illuminate\View\View
     */
    public function contasReceber()
    {
        return view('admin.fluxo-caixa.contas-receber');
    }
}


