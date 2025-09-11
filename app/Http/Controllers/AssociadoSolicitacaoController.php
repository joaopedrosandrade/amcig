<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Solicitacao;

class AssociadoSolicitacaoController extends Controller
{
    /**
     * Exibe a listagem de solicitações do associado
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query base para solicitações do usuário
        $query = $user->solicitacoes()->orderBy('created_at', 'desc');
        
        // Filtros
        $filtros = [];
        
        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $filtros['status'] = $request->status;
        }
        
        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
            $filtros['tipo'] = $request->tipo;
        }
        
        // Filtro por prioridade
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
            $filtros['prioridade'] = $request->prioridade;
        }
        
        // Filtro por período
        if ($request->filled('data_inicio')) {
            $query->where('created_at', '>=', $request->data_inicio);
            $filtros['data_inicio'] = $request->data_inicio;
        }
        
        if ($request->filled('data_fim')) {
            $query->where('created_at', '<=', $request->data_fim);
            $filtros['data_fim'] = $request->data_fim;
        }
        
        // Busca por título ou descrição
        if ($request->filled('busca')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->busca . '%')
                  ->orWhere('descricao', 'like', '%' . $request->busca . '%');
            });
            $filtros['busca'] = $request->busca;
        }
        
        // Paginação
        $solicitacoes = $query->paginate(10)->appends($request->all());
        
        // Estatísticas
        $totalSolicitacoes = $user->solicitacoes()->count();
        $solicitacoesAbertas = $user->solicitacoes()->where('status', 'ABERTA')->count();
        $solicitacoesEmAndamento = $user->solicitacoes()->whereIn('status', ['EM_ANALISE', 'EM_ANDAMENTO'])->count();
        $solicitacoesConcluidas = $user->solicitacoes()->where('status', 'CONCLUIDA')->count();
        
        // Opções para filtros
        $statusOptions = [
            'ABERTA' => 'Aberta',
            'EM_ANALISE' => 'Em Análise',
            'EM_ANDAMENTO' => 'Em Andamento',
            'CONCLUIDA' => 'Concluída',
            'CANCELADA' => 'Cancelada',
            'REJEITADA' => 'Rejeitada'
        ];
        
        $tipoOptions = [
            'PATRULHAMENTO_RUA' => 'Patrulhamento de Rua',
            'ILUMINACAO_PUBLICA' => 'Iluminação Pública',
            'MANUTENCAO_VIAS' => 'Manutenção de Vias',
            'LIMPEZA_PUBLICA' => 'Limpeza Pública',
            'SEGURANCA_PUBLICA' => 'Segurança Pública',
            'TRANSPORTE_PUBLICO' => 'Transporte Público',
            'SAUDE_PUBLICA' => 'Saúde Pública',
            'EDUCACAO' => 'Educação',
            'MEIO_AMBIENTE' => 'Meio Ambiente',
            'OUTROS' => 'Outros'
        ];
        
        $prioridadeOptions = [
            'BAIXA' => 'Baixa',
            'MEDIA' => 'Média',
            'ALTA' => 'Alta',
            'URGENTE' => 'Urgente'
        ];
        
        return view('associado.solicitacoes.index', compact(
            'user',
            'solicitacoes',
            'filtros',
            'totalSolicitacoes',
            'solicitacoesAbertas',
            'solicitacoesEmAndamento',
            'solicitacoesConcluidas',
            'statusOptions',
            'tipoOptions',
            'prioridadeOptions'
        ));
    }

    /**
     * Exibe o formulário para criar nova solicitação
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        $tipoOptions = [
            'PATRULHAMENTO_RUA' => 'Patrulhamento de Rua',
            'ILUMINACAO_PUBLICA' => 'Iluminação Pública',
            'MANUTENCAO_VIAS' => 'Manutenção de Vias',
            'LIMPEZA_PUBLICA' => 'Limpeza Pública',
            'SEGURANCA_PUBLICA' => 'Segurança Pública',
            'TRANSPORTE_PUBLICO' => 'Transporte Público',
            'SAUDE_PUBLICA' => 'Saúde Pública',
            'EDUCACAO' => 'Educação',
            'MEIO_AMBIENTE' => 'Meio Ambiente',
            'OUTROS' => 'Outros'
        ];
        
        $prioridadeOptions = [
            'BAIXA' => 'Baixa',
            'MEDIA' => 'Média',
            'ALTA' => 'Alta',
            'URGENTE' => 'Urgente'
        ];
        
        return view('associado.solicitacoes.create', compact(
            'user',
            'tipoOptions',
            'prioridadeOptions'
        ));
    }

    /**
     * Armazena uma nova solicitação
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo' => 'required|in:PATRULHAMENTO_RUA,ILUMINACAO_PUBLICA,MANUTENCAO_VIAS,LIMPEZA_PUBLICA,SEGURANCA_PUBLICA,TRANSPORTE_PUBLICO,SAUDE_PUBLICA,EDUCACAO,MEIO_AMBIENTE,OUTROS',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|min:10',
            'endereco' => 'required|string|max:255',
            'bairro' => 'nullable|string|max:100',
            'cep' => 'nullable|string|max:9',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'prioridade' => 'required|in:BAIXA,MEDIA,ALTA,URGENTE'
        ], [
            'tipo.required' => 'Por favor, selecione o tipo de solicitação.',
            'tipo.in' => 'Tipo de solicitação inválido.',
            'titulo.required' => 'Por favor, informe o título da solicitação.',
            'titulo.max' => 'O título não pode ter mais que 255 caracteres.',
            'descricao.required' => 'Por favor, descreva a solicitação.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'endereco.required' => 'Por favor, informe o endereço.',
            'endereco.max' => 'O endereço não pode ter mais que 255 caracteres.',
            'bairro.max' => 'O bairro não pode ter mais que 100 caracteres.',
            'cep.max' => 'O CEP não pode ter mais que 9 caracteres.',
            'latitude.between' => 'A latitude deve estar entre -90 e 90.',
            'longitude.between' => 'A longitude deve estar entre -180 e 180.',
            'prioridade.required' => 'Por favor, selecione a prioridade.',
            'prioridade.in' => 'Prioridade inválida.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        $solicitacao = $user->solicitacoes()->create([
            'tipo' => $request->tipo,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'endereco' => $request->endereco,
            'bairro' => $request->bairro,
            'cidade' => 'São Mateus',
            'cep' => $request->cep,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'prioridade' => $request->prioridade,
            'status' => 'ABERTA'
        ]);

        return redirect()->route('associado.solicitacoes.index')
            ->with('success', 'Solicitação criada com sucesso! Número: #' . $solicitacao->id);
    }

    /**
     * Exibe os detalhes de uma solicitação específica
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $solicitacao = $user->solicitacoes()->findOrFail($id);
        
        return view('associado.solicitacoes.show', compact('user', 'solicitacao'));
    }

    /**
     * Cancela uma solicitação
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $user = Auth::user();
        $solicitacao = $user->solicitacoes()->findOrFail($id);
        
        if ($solicitacao->status === 'ABERTA') {
            $solicitacao->update(['status' => 'CANCELADA']);
            
            return redirect()->route('associado.solicitacoes.index')
                ->with('success', 'Solicitação cancelada com sucesso!');
        }
        
        return redirect()->back()
            ->with('error', 'Não é possível cancelar esta solicitação.');
    }
}
