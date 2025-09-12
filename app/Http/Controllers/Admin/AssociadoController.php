<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Subscription;
use App\Invoice;
use App\Events\AssociadoAprovado;
use App\Events\AssociadoRejeitado;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\Facade\Pdf;

class AssociadoController extends Controller
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
     * Exibe a listagem de associados
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $associados = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'photo',
            'created_at'
        ])->where('status', 'aprovado')->get();

        // Formata as datas no fuso horário de São Paulo
        foreach ($associados as $associado) {
            if ($associado->created_at) {
                $associado->created_at_formatted = $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i');
            } else {
                $associado->created_at_formatted = 'N/A';
            }
        }

        return view('admin.associados.index', compact('associados'));
    }

    /**
     * Retorna os dados para o DataTable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $associados = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'photo',
            'created_at'
        ])->where('status', 'aprovado')->get();

        $data = [];
        foreach ($associados as $associado) {
            $tipos = [
                'morador' => 'Morador',
                'comerciante' => 'Comerciante'
            ];

            $status = $associado->status ?? 'pendente';
            $badges = [
                'aprovado' => '<span class="badge bg-success">Aprovado</span>',
                'ativo' => '<span class="badge bg-success">Ativo</span>',
                'inativo' => '<span class="badge bg-danger">Inativo</span>',
                'pendente' => '<span class="badge bg-warning">Pendente</span>',
                'suspenso' => '<span class="badge bg-secondary">Suspenso</span>'
            ];

            $data[] = [
                'id' => $associado->id,
                'name' => $associado->name,
                'email' => $associado->email,
                'cpf' => $associado->cpf ?? 'N/A',
                'matricula' => $associado->matricula ?? 'N/A',
                'tipo_associado' => $tipos[$associado->tipo_associado] ?? 'N/A',
                'status' => $badges[$status] ?? '<span class="badge bg-warning">Pendente</span>',
                'created_at' => $associado->created_at ? $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : 'N/A',
                'actions' => '<button type="button" class="btn btn-sm btn-info view-associado" data-id="' . $associado->id . '" title="Visualizar">
                                <i class="ri-eye-line"></i>
                            </button>'
            ];
        }

        return response()->json([
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data)
        ]);
    }

    /**
     * Exibe os detalhes de um associado (modal)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $associado = User::findOrFail($request->id);
        
        return view('admin.associados.show', compact('associado'));
    }

    /**
     * Exibe a página de detalhes de um associado
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function detalhes($id)
    {
        $associado = User::with(['subscriptions.invoices'])->findOrFail($id);
        
        // Buscar mensalidades (invoices) do associado
        $mensalidades = $associado->subscriptions()
            ->with(['invoices' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get()
            ->pluck('invoices')
            ->flatten();
        
        // Estatísticas das mensalidades
        $totalMensalidades = $mensalidades->count();
        $mensalidadesPagas = $mensalidades->where('status', 'CONFIRMED')->count() + 
                           $mensalidades->where('status', 'RECEIVED')->count() +
                           $mensalidades->where('status', 'RECEIVED_IN_CASH')->count();
        $mensalidadesPendentes = $mensalidades->where('status', 'PENDING')->count();
        $mensalidadesVencidas = $mensalidades->where('status', 'OVERDUE')->count();
        
        // Valor total pago
        $valorTotalPago = $mensalidades->whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH'])
            ->sum('value');
        
        // Valor pendente
        $valorPendente = $mensalidades->whereIn('status', ['PENDING', 'OVERDUE'])
            ->sum('value');
        
        return view('admin.associados.detalhes', compact(
            'associado', 
            'mensalidades', 
            'totalMensalidades',
            'mensalidadesPagas',
            'mensalidadesPendentes', 
            'mensalidadesVencidas',
            'valorTotalPago',
            'valorPendente'
        ));
    }

    /**
     * Resetar senha do associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'nova_senha' => 'required|string|min:6|confirmed'
        ], [
            'id.required' => 'ID do associado é obrigatório.',
            'id.exists' => 'Associado não encontrado.',
            'nova_senha.required' => 'A nova senha é obrigatória.',
            'nova_senha.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da senha não confere.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $associado = User::findOrFail($request->id);
            
            $associado->update([
                'password' => Hash::make($request->nova_senha)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Senha atualizada com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Desativar associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function desativar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id'
        ], [
            'id.required' => 'ID do associado é obrigatório.',
            'id.exists' => 'Associado não encontrado.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $associado = User::findOrFail($request->id);
            
            // Verificar se o associado pode ser desativado
            if ($associado->status === 'desativado') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este associado já está desativado.'
                ], 400);
            }
            
            $associado->update([
                'status' => 'desativado'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Associado desativado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Exibe a listagem de associados pendentes
     *
     * @return \Illuminate\View\View
     */
    public function pendentes()
    {
        $associadosPendentes = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'photo',
            'created_at'
        ])->where('status', 'pendente')->get();

        // Formata as datas no fuso horário de São Paulo
        foreach ($associadosPendentes as $associado) {
            if ($associado->created_at) {
                $associado->created_at_formatted = $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i');
            } else {
                $associado->created_at_formatted = 'N/A';
            }
        }

        return view('admin.associados.pendentes', compact('associadosPendentes'));
    }

    /**
     * Retorna os dados para o DataTable dos associados pendentes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendentesData(Request $request)
    {
        $associados = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'photo',
            'created_at'
        ])->where('status', 'pendente')->get();

        $data = [];
        foreach ($associados as $associado) {
            $tipos = [
                'morador' => 'Morador',
                'comerciante' => 'Comerciante'
            ];

            $data[] = [
                'id' => $associado->id,
                'name' => $associado->name,
                'email' => $associado->email,
                'cpf' => $associado->cpf ?? 'N/A',
                'matricula' => $associado->matricula ?? 'N/A',
                'tipo_associado' => $tipos[$associado->tipo_associado] ?? 'N/A',
                'created_at' => $associado->created_at ? $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : 'N/A',
                'actions' => '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-info view-associado" data-id="' . $associado->id . '" title="Visualizar">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-success aprovar-associado" data-id="' . $associado->id . '" title="Aprovar">
                                    <i class="ri-check-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rejeitar-associado" data-id="' . $associado->id . '" title="Rejeitar">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>'
            ];
        }

        return response()->json([
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data)
        ]);
    }

    /**
     * Aprova um associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprovar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $associado = User::findOrFail($request->id);
        
        // Verifica se o associado está pendente
        if ($associado->status !== 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'Este associado não está pendente de aprovação.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Atualizar status do associado
            $associado->update([
                'status' => 'aprovado',
                'data_aprovacao' => now()
            ]);

            // Criar cliente e assinatura no Asaas
            $asaasService = new AsaasService();
            
            // Criar cliente no Asaas
            $customerData = $asaasService->createCustomer($associado);
            $asaasCustomerId = $customerData['id'];

            // Criar assinatura no Asaas
            $subscriptionData = $asaasService->createSubscription($associado, $asaasCustomerId);
            $asaasSubscriptionId = $subscriptionData['id'];

            // Salvar assinatura no banco local
            $subscription = Subscription::create([
                'user_id' => $associado->id,
                'asaas_subscription_id' => $asaasSubscriptionId,
                'asaas_customer_id' => $asaasCustomerId,
                'value' => $associado->getMonthlyValue(),
                'billing_type' => 'PIX',
                'next_due_date' => \Carbon\Carbon::parse($subscriptionData['nextDueDate']),
                'status' => 'ACTIVE',
                'description' => 'Mensalidade AMCIG - ' . ucfirst($associado->tipo_associado),
                'asaas_data' => $subscriptionData
            ]);

            // As faturas serão criadas automaticamente via webhook quando o Asaas gerar os pagamentos
            // A primeira cobrança será criada automaticamente pela assinatura
            Log::info('Assinatura criada com sucesso', [
                'user_id' => $associado->id,
                'subscription_id' => $subscription->id,
                'next_due_date' => $subscription->next_due_date->format('Y-m-d')
            ]);

            // Criar primeira fatura se ela foi gerada manualmente
            if (isset($subscriptionData['first_payment'])) {
                $firstPayment = $subscriptionData['first_payment'];
                
                $invoice = Invoice::create([
                    'subscription_id' => $subscription->id,
                    'user_id' => $associado->id,
                    'asaas_payment_id' => $firstPayment['id'],
                    'value' => $firstPayment['value'],
                    'due_date' => \Carbon\Carbon::parse($firstPayment['dueDate']),
                    'status' => $firstPayment['status'],
                    'billing_type' => $firstPayment['billingType'],
                    'description' => $firstPayment['description'],
                    'invoice_url' => $firstPayment['invoiceUrl'] ?? null,
                    'pix_qr_code' => $firstPayment['pixTransaction']['qrCode'] ?? null,
                    'pix_copy_paste' => $firstPayment['pixTransaction']['payload'] ?? null,
                    'asaas_data' => $firstPayment
                ]);

                Log::info('Primeira fatura criada localmente', [
                    'user_id' => $associado->id,
                    'invoice_id' => $invoice->id,
                    'asaas_payment_id' => $firstPayment['id'],
                    'value' => $firstPayment['value'],
                    'due_date' => $firstPayment['dueDate']
                ]);
            }

            DB::commit();

            // Disparar evento de aprovação
            event(new AssociadoAprovado($associado));

            Log::info('Associado aprovado e assinatura criada', [
                'user_id' => $associado->id,
                'subscription_id' => $subscription->id,
                'asaas_subscription_id' => $asaasSubscriptionId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Associado aprovado e assinatura criada com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Erro ao aprovar associado e criar assinatura', [
                'user_id' => $associado->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao aprovar associado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rejeita um associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejeitar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'motivo' => 'nullable|string|max:500'
        ]);

        $associado = User::findOrFail($request->id);
        
        // Verifica se o associado está pendente
        if ($associado->status !== 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'Este associado não está pendente de aprovação.'
            ], 400);
        }

        $associado->update([
            'status' => 'rejeitado',
            'motivo_rejeicao' => $request->motivo
        ]);

        // Disparar evento de rejeição
        event(new AssociadoRejeitado($associado, $request->motivo));

        return response()->json([
            'success' => true,
            'message' => 'Associado rejeitado com sucesso!'
        ]);
    }

    /**
     * Exibe a página de relatórios de associados
     *
     * @return \Illuminate\View\View
     */
    public function relatorios()
    {
        // Buscar dados únicos para os filtros
        $bairros = User::whereNotNull('bairro')
            ->where('bairro', '!=', '')
            ->distinct()
            ->pluck('bairro')
            ->sort()
            ->values();

        $logradouros = User::whereNotNull('logradouro')
            ->where('logradouro', '!=', '')
            ->distinct()
            ->pluck('logradouro')
            ->sort()
            ->values();

        $sexos = User::whereNotNull('sexo')
            ->where('sexo', '!=', '')
            ->distinct()
            ->pluck('sexo')
            ->sort()
            ->values();

        return view('admin.associados.relatorios', compact('bairros', 'logradouros', 'sexos'));
    }

    /**
     * Busca associados com base nos filtros aplicados
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarRelatorios(Request $request)
    {
        $query = User::query();

        // Filtro por sexo
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        // Filtro por bairro
        if ($request->filled('bairro')) {
            $query->where('bairro', 'like', '%' . $request->bairro . '%');
        }

        // Filtro por logradouro (rua)
        if ($request->filled('logradouro')) {
            $query->where('logradouro', 'like', '%' . $request->logradouro . '%');
        }

        // Filtro por idade
        if ($request->filled('idade_min')) {
            $idadeMin = $request->idade_min;
            $dataMax = now()->subYears($idadeMin)->format('Y-m-d');
            $query->where('data_nascimento', '<=', $dataMax);
        }

        if ($request->filled('idade_max')) {
            $idadeMax = $request->idade_max;
            $dataMin = now()->subYears($idadeMax + 1)->addDay()->format('Y-m-d');
            $query->where('data_nascimento', '>=', $dataMin);
        }

        // Filtro por tipo de associado
        if ($request->filled('tipo_associado')) {
            $query->where('tipo_associado', $request->tipo_associado);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por data de cadastro
        if ($request->filled('data_cadastro_inicio')) {
            $query->where('created_at', '>=', $request->data_cadastro_inicio);
        }

        if ($request->filled('data_cadastro_fim')) {
            $query->where('created_at', '<=', $request->data_cadastro_fim . ' 23:59:59');
        }

        // Buscar associados
        $associados = $query->select([
            'id', 'name', 'email', 'cpf', 'matricula', 'sexo', 'data_nascimento',
            'telefone', 'bairro', 'logradouro', 'numero', 'complemento',
            'cidade', 'uf', 'tipo_associado', 'status', 'photo', 'created_at'
        ])->get();

        // Calcular idade para cada associado
        $associados->each(function ($associado) {
            if ($associado->data_nascimento) {
                $associado->idade = $associado->data_nascimento->diffInYears(now());
            }
        });

        // Estatísticas gerais
        $totalAssociados = $associados->count();
        $porSexo = $associados->groupBy('sexo')->map->count();
        $porTipo = $associados->groupBy('tipo_associado')->map->count();
        $porStatus = $associados->groupBy('status')->map->count();
        $porBairro = $associados->groupBy('bairro')->map->count();

        return response()->json([
            'success' => true,
            'associados' => $associados,
            'estatisticas' => [
                'total' => $totalAssociados,
                'por_sexo' => $porSexo,
                'por_tipo' => $porTipo,
                'por_status' => $porStatus,
                'por_bairro' => $porBairro
            ]
        ]);
    }

    /**
     * Exporta os resultados para CSV (Excel)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportarExcel(Request $request)
    {
        // Aplicar os mesmos filtros da busca
        $query = User::query();
        $this->aplicarFiltros($query, $request);

        $associados = $query->select([
            'id', 'name', 'email', 'cpf', 'matricula', 'sexo', 'data_nascimento',
            'telefone', 'bairro', 'logradouro', 'numero', 'complemento',
            'cidade', 'uf', 'tipo_associado', 'status', 'photo', 'created_at'
        ])->get();

        // Calcular idade para cada associado
        $associados->each(function ($associado) {
            if ($associado->data_nascimento) {
                $associado->idade = $associado->data_nascimento->diffInYears(now());
            }
        });

        // Preparar dados para CSV
        $csvData = [];
        $csvData[] = [
            'Nome', 'Matrícula', 'CPF', 'Idade', 'Sexo', 'Telefone', 
            'Bairro', 'Rua', 'Número', 'Complemento', 'Cidade', 'UF',
            'Tipo Associado', 'Status', 'Data Cadastro'
        ];

        foreach ($associados as $associado) {
            $csvData[] = [
                $associado->name,
                $associado->matricula ?? 'N/A',
                $associado->cpf ?? 'N/A',
                $associado->idade ?? 'N/A',
                $associado->sexo ? ucfirst($associado->sexo) : 'N/A',
                $associado->telefone ?? 'N/A',
                $associado->bairro ?? 'N/A',
                $associado->logradouro ?? 'N/A',
                $associado->numero ?? 'N/A',
                $associado->complemento ?? 'N/A',
                $associado->cidade ?? 'N/A',
                $associado->uf ?? 'N/A',
                $this->getTipoAssociadoLabel($associado->tipo_associado),
                $this->getStatusLabel($associado->status),
                $associado->created_at ? $associado->created_at->format('d/m/Y') : 'N/A'
            ];
        }

        $filename = 'relatorio_associados_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Converter para CSV
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= '"' . implode('","', $row) . '"' . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }

    /**
     * Exporta os resultados para PDF (HTML para impressão)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportarPdf(Request $request)
    {
        // Aplicar os mesmos filtros da busca
        $query = User::query();
        $this->aplicarFiltros($query, $request);

        $associados = $query->select([
            'id', 'name', 'email', 'cpf', 'matricula', 'sexo', 'data_nascimento',
            'telefone', 'bairro', 'logradouro', 'numero', 'complemento',
            'cidade', 'uf', 'tipo_associado', 'status', 'photo', 'created_at'
        ])->get();

        // Calcular idade para cada associado
        $associados->each(function ($associado) {
            if ($associado->data_nascimento) {
                $associado->idade = $associado->data_nascimento->diffInYears(now());
            }
        });

        // Estatísticas
        $totalAssociados = $associados->count();
        $porSexo = $associados->groupBy('sexo')->map->count();
        $porTipo = $associados->groupBy('tipo_associado')->map->count();
        $porStatus = $associados->groupBy('status')->map->count();

        $html = view('admin.associados.relatorio-pdf', [
            'associados' => $associados,
            'totalAssociados' => $totalAssociados,
            'porSexo' => $porSexo,
            'porTipo' => $porTipo,
            'porStatus' => $porStatus,
            'filtros' => $this->getFiltrosAplicados($request)
        ])->render();

        $filename = 'relatorio_associados_' . now()->format('Y-m-d_H-i-s') . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Aplica os filtros na query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function aplicarFiltros($query, $request)
    {
        // Filtro por sexo
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        // Filtro por bairro
        if ($request->filled('bairro')) {
            $query->where('bairro', 'like', '%' . $request->bairro . '%');
        }

        // Filtro por logradouro (rua)
        if ($request->filled('logradouro')) {
            $query->where('logradouro', 'like', '%' . $request->logradouro . '%');
        }

        // Filtro por idade
        if ($request->filled('idade_min')) {
            $idadeMin = $request->idade_min;
            $dataMax = now()->subYears($idadeMin)->format('Y-m-d');
            $query->where('data_nascimento', '<=', $dataMax);
        }

        if ($request->filled('idade_max')) {
            $idadeMax = $request->idade_max;
            $dataMin = now()->subYears($idadeMax + 1)->addDay()->format('Y-m-d');
            $query->where('data_nascimento', '>=', $dataMin);
        }

        // Filtro por tipo de associado
        if ($request->filled('tipo_associado')) {
            $query->where('tipo_associado', $request->tipo_associado);
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por data de cadastro
        if ($request->filled('data_cadastro_inicio')) {
            $query->where('created_at', '>=', $request->data_cadastro_inicio);
        }

        if ($request->filled('data_cadastro_fim')) {
            $query->where('created_at', '<=', $request->data_cadastro_fim . ' 23:59:59');
        }
    }

    /**
     * Retorna os filtros aplicados para exibição
     *
     * @param Request $request
     * @return array
     */
    private function getFiltrosAplicados($request)
    {
        $filtros = [];

        if ($request->filled('sexo')) {
            $filtros['Sexo'] = ucfirst($request->sexo);
        }

        if ($request->filled('bairro')) {
            $filtros['Bairro'] = $request->bairro;
        }

        if ($request->filled('logradouro')) {
            $filtros['Rua'] = $request->logradouro;
        }

        if ($request->filled('idade_min') || $request->filled('idade_max')) {
            $idadeMin = $request->idade_min ?? 0;
            $idadeMax = $request->idade_max ?? 120;
            $filtros['Idade'] = "Entre {$idadeMin} e {$idadeMax} anos";
        }

        if ($request->filled('tipo_associado')) {
            $filtros['Tipo'] = $this->getTipoAssociadoLabel($request->tipo_associado);
        }

        if ($request->filled('status')) {
            $filtros['Status'] = $this->getStatusLabel($request->status);
        }

        if ($request->filled('data_cadastro_inicio') || $request->filled('data_cadastro_fim')) {
            $dataInicio = $request->data_cadastro_inicio ? \Carbon\Carbon::parse($request->data_cadastro_inicio)->format('d/m/Y') : 'Início';
            $dataFim = $request->data_cadastro_fim ? \Carbon\Carbon::parse($request->data_cadastro_fim)->format('d/m/Y') : 'Fim';
            $filtros['Data Cadastro'] = "De {$dataInicio} até {$dataFim}";
        }

        return $filtros;
    }

    /**
     * Retorna o label do tipo de associado
     *
     * @param string $tipo
     * @return string
     */
    private function getTipoAssociadoLabel($tipo)
    {
        $tipos = [
            'morador' => 'Morador',
            'comerciante' => 'Comerciante',
            'ambos' => 'Ambos'
        ];

        return $tipos[$tipo] ?? 'N/A';
    }

    /**
     * Retorna o label do status
     *
     * @param string $status
     * @return string
     */
    private function getStatusLabel($status)
    {
        $statuses = [
            'pendente' => 'Pendente',
            'aprovado' => 'Aprovado',
            'rejeitado' => 'Rejeitado',
            'desativado' => 'Desativado'
        ];

        return $statuses[$status] ?? 'N/A';
    }
}
