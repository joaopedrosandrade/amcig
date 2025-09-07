<?php

namespace App\Http\Controllers;

use App\User;
use App\Subscription;
use App\Invoice;
use App\Payment;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssociadoPagamentoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a página de pagamentos do associado
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buscar assinatura ativa
        $subscription = $user->activeSubscription();
        
        // Buscar faturas pendentes
        $invoices = $user->invoices()
            ->whereIn('status', ['PENDING', 'OVERDUE'])
            ->orderBy('due_date', 'asc')
            ->get();

        // Buscar histórico de pagamentos
        $payments = $user->payments()
            ->whereIn('status', ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        return view('associado.pagamentos', compact('user', 'subscription', 'invoices', 'payments'));
    }

    /**
     * Exibe detalhes de uma fatura específica
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $request->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('associado.fatura', compact('invoice'));
    }

    /**
     * Atualiza dados de uma fatura (busca informações atualizadas do Asaas)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function atualizar(Request $request)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $request->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            $asaasService = new AsaasService();
            $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);

            // Atualizar fatura com dados do Asaas
            $invoice->update([
                'status' => $paymentData['status'],
                'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
                'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                'pix_qr_code' => $paymentData['pixTransaction']['qrCode'] ?? null,
                'pix_copy_paste' => $paymentData['pixTransaction']['payload'] ?? null,
                'asaas_data' => $paymentData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fatura atualizada com sucesso!',
                'invoice' => $invoice
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar fatura', [
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar fatura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancela assinatura do associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelarAssinatura(Request $request)
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma assinatura ativa encontrada.'
            ], 400);
        }

        try {
            $asaasService = new AsaasService();
            $asaasService->cancelSubscription($subscription->asaas_subscription_id);

            // Atualizar status da assinatura
            $subscription->update(['status' => 'CANCELLED']);

            Log::info('Assinatura cancelada pelo associado', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assinatura cancelada com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao cancelar assinatura', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao cancelar assinatura: ' . $e->getMessage()
            ], 500);
        }
    }
}