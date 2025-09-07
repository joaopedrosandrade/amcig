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

    /**
     * Exibe dados de pagamento de uma fatura específica
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function pagamento(Request $request)
    {
        $user = Auth::user();
        
        Log::info('Tentando acessar dados de pagamento', [
            'user_id' => $user->id,
            'invoice_id' => $request->id
        ]);
        
        $invoice = Invoice::where('id', $request->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        Log::info('Fatura encontrada para pagamento', [
            'invoice_id' => $invoice->id,
            'status' => $invoice->status,
            'value' => $invoice->value
        ]);

        return view('associado.pagamento', compact('invoice'));
    }

    /**
     * Verifica se o pagamento foi realizado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificarPagamento(Request $request)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $request->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            $asaasService = new AsaasService();
            $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);

            // Verificar se o pagamento foi confirmado
            $pago = in_array($paymentData['status'], ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']);

            if ($pago) {
                // Atualizar fatura local
                $invoice->update([
                    'status' => $paymentData['status'],
                    'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
                    'asaas_data' => $paymentData
                ]);

                // Criar registro de pagamento se necessário
                if (!Payment::where('asaas_payment_id', $paymentData['id'])->exists()) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'user_id' => $user->id,
                        'asaas_payment_id' => $paymentData['id'],
                        'value' => $paymentData['value'],
                        'payment_date' => \Carbon\Carbon::parse($paymentData['paymentDate']),
                        'status' => $paymentData['status'],
                        'payment_method' => $paymentData['billingType'] ?? 'PIX',
                        'description' => $paymentData['description'] ?? null,
                        'asaas_data' => $paymentData
                    ]);
                }

                Log::info('Pagamento confirmado pelo associado', [
                    'user_id' => $user->id,
                    'invoice_id' => $invoice->id,
                    'payment_id' => $paymentData['id']
                ]);
            }

            return response()->json([
                'success' => true,
                'pago' => $pago,
                'status' => $paymentData['status'],
                'message' => $pago ? 'Pagamento confirmado!' : 'Pagamento ainda não foi confirmado.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao verificar pagamento', [
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna a primeira fatura em atraso do usuário
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function primeiraFaturaAtraso()
    {
        $user = Auth::user();
        $faturasEmAtraso = $user->getFaturasEmAtraso();

        if ($faturasEmAtraso->count() > 0) {
            $primeiraFatura = $faturasEmAtraso->first();
            
            return response()->json([
                'success' => true,
                'invoice_id' => $primeiraFatura->id,
                'message' => 'Primeira fatura em atraso encontrada'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nenhuma fatura em atraso encontrada'
        ]);
    }

    /**
     * Exibe detalhes de uma fatura específica (rota direta)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function verFatura($id)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('associado.fatura', compact('invoice'));
    }

    /**
     * Exibe dados de pagamento de uma fatura específica (rota direta)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function pagarFatura($id)
    {
        $user = Auth::user();
        
        Log::info('Tentando acessar dados de pagamento via rota direta', [
            'user_id' => $user->id,
            'invoice_id' => $id
        ]);
        
        $invoice = Invoice::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        Log::info('Fatura encontrada para pagamento via rota direta', [
            'invoice_id' => $invoice->id,
            'status' => $invoice->status,
            'value' => $invoice->value
        ]);

        return view('associado.pagamento', compact('invoice'));
    }

    /**
     * Atualiza dados de uma fatura específica (rota direta)
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function atualizarFatura($id)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $id)
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

            return redirect()->route('associado.pagamentos')->with('success', 'Fatura atualizada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar fatura via rota direta', [
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('associado.pagamentos')->with('error', 'Erro ao atualizar fatura: ' . $e->getMessage());
        }
    }

    /**
     * Exibe página de confirmação para cancelar assinatura
     *
     * @return \Illuminate\View\View
     */
    public function cancelarAssinaturaView()
    {
        $user = Auth::user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return redirect()->route('associado.pagamentos')->with('error', 'Nenhuma assinatura ativa encontrada.');
        }

        return view('associado.cancelar-assinatura', compact('subscription'));
    }
}