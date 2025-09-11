<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Solicitacao;
use App\User;
use App\Admin;

class AdminSolicitacaoController extends Controller
{
    /**
     * Exibe a listagem de todas as solicitações para o admin
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Query base para todas as solicitações
        $query = Solicitacao::with(['user', 'admin'])->orderBy('created_at', 'desc');
        
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
        
        // Filtro por admin responsável
        if ($request->filled('admin_responsavel')) {
            $query->where('admin_responsavel', $request->admin_responsavel);
            $filtros['admin_responsavel'] = $request->admin_responsavel;
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
        
        // Busca por título, descrição ou nome do usuário
        if ($request->filled('busca')) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->busca . '%')
                  ->orWhere('descricao', 'like', '%' . $request->busca . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->busca . '%');
                  });
            });
            $filtros['busca'] = $request->busca;
        }
        
        // Paginação
        $solicitacoes = $query->paginate(15)->appends($request->all());
        
        // Estatísticas
        $totalSolicitacoes = Solicitacao::count();
        $solicitacoesAbertas = Solicitacao::where('status', 'ABERTA')->count();
        $solicitacoesEmAndamento = Solicitacao::whereIn('status', ['EM_ANALISE', 'EM_ANDAMENTO'])->count();
        $solicitacoesConcluidas = Solicitacao::where('status', 'CONCLUIDA')->count();
        $solicitacoesAtrasadas = Solicitacao::where('data_limite', '<', now())
            ->whereNotIn('status', ['CONCLUIDA', 'CANCELADA'])
            ->count();
        
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
        
        // Lista de admins para filtro
        $admins = Admin::select('id', 'name')->get();
        
        return view('admin.solicitacoes.index', compact(
            'solicitacoes',
            'filtros',
            'totalSolicitacoes',
            'solicitacoesAbertas',
            'solicitacoesEmAndamento',
            'solicitacoesConcluidas',
            'solicitacoesAtrasadas',
            'statusOptions',
            'tipoOptions',
            'prioridadeOptions',
            'admins'
        ));
    }

    /**
     * Exibe os detalhes de uma solicitação específica
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $solicitacao = Solicitacao::with(['user', 'admin'])->findOrFail($id);
        
        // Lista de admins para atribuição
        $admins = Admin::select('id', 'name')->get();
        
        return view('admin.solicitacoes.show', compact('solicitacao', 'admins'));
    }

    /**
     * Atualiza o status de uma solicitação
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:ABERTA,EM_ANALISE,EM_ANDAMENTO,CONCLUIDA,CANCELADA,REJEITADA',
            'observacoes_admin' => 'nullable|string|max:1000',
            'data_limite' => 'nullable|date|after:today',
            'admin_responsavel' => 'nullable|exists:admins,id'
        ], [
            'status.required' => 'Por favor, selecione um status.',
            'status.in' => 'Status inválido.',
            'observacoes_admin.max' => 'As observações não podem ter mais que 1000 caracteres.',
            'data_limite.after' => 'A data limite deve ser futura.',
            'admin_responsavel.exists' => 'Admin responsável inválido.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $solicitacao = Solicitacao::findOrFail($id);
        
        $updateData = [
            'status' => $request->status,
            'observacoes_admin' => $request->observacoes_admin,
            'admin_responsavel' => $request->admin_responsavel
        ];
        
        // Se definindo data limite
        if ($request->filled('data_limite')) {
            $updateData['data_limite'] = $request->data_limite;
        }
        
        // Se marcando como concluída, definir data de conclusão
        if ($request->status === 'CONCLUIDA' && $solicitacao->status !== 'CONCLUIDA') {
            $updateData['data_conclusao'] = now();
        }
        
        $solicitacao->update($updateData);

        return redirect()->route('admin.solicitacoes.show', $id)
            ->with('success', 'Status da solicitação atualizado com sucesso!');
    }

    /**
     * Atribui um admin responsável para uma solicitação
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignAdmin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin_responsavel' => 'required|exists:admins,id'
        ], [
            'admin_responsavel.required' => 'Por favor, selecione um admin responsável.',
            'admin_responsavel.exists' => 'Admin responsável inválido.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $solicitacao = Solicitacao::findOrFail($id);
        $solicitacao->update([
            'admin_responsavel' => $request->admin_responsavel,
            'status' => 'EM_ANALISE'
        ]);

        return redirect()->route('admin.solicitacoes.show', $id)
            ->with('success', 'Admin responsável atribuído com sucesso!');
    }

    /**
     * Dashboard de estatísticas das solicitações
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Estatísticas gerais
        $totalSolicitacoes = Solicitacao::count();
        $solicitacoesAbertas = Solicitacao::where('status', 'ABERTA')->count();
        $solicitacoesEmAndamento = Solicitacao::whereIn('status', ['EM_ANALISE', 'EM_ANDAMENTO'])->count();
        $solicitacoesConcluidas = Solicitacao::where('status', 'CONCLUIDA')->count();
        $solicitacoesAtrasadas = Solicitacao::where('data_limite', '<', now())
            ->whereNotIn('status', ['CONCLUIDA', 'CANCELADA'])
            ->count();
        
        // Solicitações por tipo
        $solicitacoesPorTipo = Solicitacao::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->orderBy('total', 'desc')
            ->get();
        
        // Solicitações por prioridade
        $solicitacoesPorPrioridade = Solicitacao::selectRaw('prioridade, COUNT(*) as total')
            ->groupBy('prioridade')
            ->orderBy('total', 'desc')
            ->get();
        
        // Solicitações recentes
        $solicitacoesRecentes = Solicitacao::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Solicitações urgentes
        $solicitacoesUrgentes = Solicitacao::with(['user'])
            ->where('prioridade', 'URGENTE')
            ->whereNotIn('status', ['CONCLUIDA', 'CANCELADA'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.solicitacoes.dashboard', compact(
            'totalSolicitacoes',
            'solicitacoesAbertas',
            'solicitacoesEmAndamento',
            'solicitacoesConcluidas',
            'solicitacoesAtrasadas',
            'solicitacoesPorTipo',
            'solicitacoesPorPrioridade',
            'solicitacoesRecentes',
            'solicitacoesUrgentes'
        ));
    }
}
