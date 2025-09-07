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
use Illuminate\Support\Facades\Log;

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
     * Exibe os detalhes de um associado
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

            // Criar primeira fatura se existir
            if (isset($subscriptionData['payments']) && count($subscriptionData['payments']) > 0) {
                $firstPayment = $subscriptionData['payments'][0];
                
                Invoice::create([
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
}
