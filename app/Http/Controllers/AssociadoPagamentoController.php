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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            ->whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();

        return view('associado.pagamentos', compact('user', 'subscription', 'invoices', 'payments'));
    }

    /**
     * Exibe o histórico completo de pagamentos do associado
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function historico(Request $request)
    {
        $user = Auth::user();
        
        // Debug: verificar dados do usuário
        \Log::info('Histórico de pagamentos - Usuário ID: ' . $user->id);
        
        // Debug: verificar total de invoices do usuário
        $totalInvoices = $user->invoices()->count();
        \Log::info('Total de invoices do usuário: ' . $totalInvoices);
        
        // Debug: verificar invoices pagos
        $paidInvoices = $user->invoices()
            ->whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->count();
        \Log::info('Invoices pagos: ' . $paidInvoices);
        
        // Query base para invoices pagos
        $query = $user->invoices()
            ->whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->with('payments');
        
        // Filtros
        $filtros = [];
        
        // Filtro por período
        if ($request->filled('data_inicio')) {
            $query->where('due_date', '>=', $request->data_inicio);
            $filtros['data_inicio'] = $request->data_inicio;
        }
        
        if ($request->filled('data_fim')) {
            $query->where('due_date', '<=', $request->data_fim);
            $filtros['data_fim'] = $request->data_fim;
        }
        
        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $filtros['status'] = $request->status;
        }
        
        // Filtro por método de pagamento (buscar nos payments relacionados)
        if ($request->filled('metodo_pagamento')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->where('payment_method', $request->metodo_pagamento);
            });
            $filtros['metodo_pagamento'] = $request->metodo_pagamento;
        }
        
        // Filtro por valor mínimo
        if ($request->filled('valor_minimo')) {
            $query->where('value', '>=', $request->valor_minimo);
            $filtros['valor_minimo'] = $request->valor_minimo;
        }
        
        // Filtro por valor máximo
        if ($request->filled('valor_maximo')) {
            $query->where('value', '<=', $request->valor_maximo);
            $filtros['valor_maximo'] = $request->valor_maximo;
        }
        
        // Busca por descrição
        if ($request->filled('busca')) {
            $query->where('description', 'like', '%' . $request->busca . '%');
            $filtros['busca'] = $request->busca;
        }
        
        // Ordenação
        $ordenacao = $request->get('ordenacao', 'due_date');
        $direcao = $request->get('direcao', 'desc');
        
        if (in_array($ordenacao, ['due_date', 'value', 'status'])) {
            $query->orderBy($ordenacao, $direcao);
        } else {
            $query->orderBy('due_date', 'desc');
        }
        
        // Debug: verificar query final
        \Log::info('Query SQL: ' . $query->toSql());
        \Log::info('Query bindings: ' . json_encode($query->getBindings()));
        
        // Paginação
        $invoices = $query->paginate(15)->appends($request->all());
        
        // Debug: verificar resultado da paginação
        \Log::info('Invoices encontrados: ' . $invoices->count());
        \Log::info('Total de páginas: ' . $invoices->lastPage());
        
        // Estatísticas
        $totalPagamentos = $user->invoices()
            ->whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->count();
            
        $valorTotal = $user->invoices()
            ->whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->sum('value');
            
        // Debug: verificar estatísticas
        \Log::info('Total de invoices pagos (estatística): ' . $totalPagamentos);
        \Log::info('Valor total: ' . $valorTotal);
        
        // Debug: verificar se há dados para exibir
        if ($invoices->count() == 0) {
            \Log::warning('Nenhum invoice pago encontrado para o usuário ID: ' . $user->id);
            \Log::info('Verificando se há invoices com outros status...');
            
            $allInvoices = $user->invoices()->get();
            \Log::info('Todos os invoices do usuário: ' . $allInvoices->count());
            
            foreach ($allInvoices as $invoice) {
                \Log::info('Invoice ID: ' . $invoice->id . ', Status: ' . $invoice->status . ', Data: ' . $invoice->due_date);
            }
        }
        
        // Opções para filtros
        $statusOptions = [
            'CONFIRMED' => 'Confirmado',
            'RECEIVED' => 'Recebido',
            'RECEIVED_IN_CASH' => 'Recebido em Dinheiro',
            'RECEIVED_WITH_OVERDUE' => 'Recebido com Atraso'
        ];
        
        $metodoOptions = [
            'PIX' => 'PIX',
            'BOLETO' => 'Boleto',
            'CREDIT_CARD' => 'Cartão de Crédito',
            'DEBIT_CARD' => 'Cartão de Débito'
        ];
        
        return view('associado.historico-pagamentos', compact(
            'user', 
            'invoices', 
            'filtros', 
            'totalPagamentos', 
            'valorTotal',
            'statusOptions',
            'metodoOptions',
            'ordenacao',
            'direcao'
        ));
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

            // Tentar buscar QR Code PIX se não estiver disponível
            $pixQrCode = $paymentData['pixTransaction']['qrCode'] ?? null;
            $pixCopyPaste = $paymentData['pixTransaction']['payload'] ?? null;

            // Se não tem QR Code, tentar buscar especificamente
            if (!$pixQrCode && $paymentData['billingType'] === 'PIX') {
                try {
                    $qrCodeData = $asaasService->getPixQrCode($invoice->asaas_payment_id);
                    $pixQrCode = $qrCodeData['qrCode'] ?? null;
                    $pixCopyPaste = $qrCodeData['payload'] ?? $pixCopyPaste;
                    
                    Log::info('QR Code PIX obtido via método específico', [
                        'invoice_id' => $invoice->id,
                        'has_qr_code' => !empty($pixQrCode),
                        'has_payload' => !empty($pixCopyPaste)
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Erro ao buscar QR Code PIX específico', [
                        'invoice_id' => $invoice->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Atualizar fatura com dados do Asaas
            $invoice->update([
                'status' => $paymentData['status'],
                'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
                'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                'pix_qr_code' => $pixQrCode,
                'pix_copy_paste' => $pixCopyPaste,
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
            $pago = in_array($paymentData['status'], ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']);

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
            'value' => $invoice->value,
            'has_pix_qr_code' => !empty($invoice->pix_qr_code),
            'asaas_payment_id' => $invoice->asaas_payment_id,
            'pix_qr_code_length' => $invoice->pix_qr_code ? strlen($invoice->pix_qr_code) : 0,
            'pix_copy_paste_length' => $invoice->pix_copy_paste ? strlen($invoice->pix_copy_paste) : 0
        ]);

        // Se não tem QR Code PIX, tentar buscar automaticamente
        if (empty($invoice->pix_qr_code) && $invoice->asaas_payment_id) {
            try {
                $asaasService = new AsaasService();
                $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);
                
                // Tentar buscar QR Code PIX se não estiver disponível
                $pixQrCode = $paymentData['pixTransaction']['encodedImage'] ?? 
                           $paymentData['pixTransaction']['qrCode'] ?? 
                           $paymentData['pixTransaction']['qrCodeImage'] ?? 
                           $paymentData['pixTransaction']['qrCodeBase64'] ?? 
                           $paymentData['encodedImage'] ?? 
                           $paymentData['qrCode'] ?? 
                           $paymentData['qrCodeImage'] ?? 
                           $paymentData['qrCodeBase64'] ?? 
                           null;
                           
                $pixCopyPaste = $paymentData['pixTransaction']['payload'] ?? 
                               $paymentData['pixTransaction']['copyPaste'] ?? 
                               $paymentData['payload'] ?? 
                               $paymentData['copyPaste'] ?? 
                               null;
                
                Log::info('Dados PIX obtidos do getPayment', [
                    'invoice_id' => $invoice->id,
                    'has_pix_transaction' => isset($paymentData['pixTransaction']),
                    'pix_transaction_keys' => isset($paymentData['pixTransaction']) ? array_keys($paymentData['pixTransaction']) : [],
                    'has_qr_code' => !empty($pixQrCode),
                    'has_payload' => !empty($pixCopyPaste),
                    'billing_type' => $paymentData['billingType'] ?? 'unknown'
                ]);

                // Se não tem QR Code, tentar buscar especificamente
                if (!$pixQrCode && $paymentData['billingType'] === 'PIX') {
                    try {
                        $qrCodeData = $asaasService->getPixQrCode($invoice->asaas_payment_id);
                        $pixQrCode = $qrCodeData['encodedImage'] ?? $qrCodeData['qrCode'] ?? null;
                        $pixCopyPaste = $qrCodeData['payload'] ?? $pixCopyPaste;
                        
                        Log::info('QR Code PIX obtido via método específico (pagarFatura)', [
                            'invoice_id' => $invoice->id,
                            'has_qr_code' => !empty($pixQrCode),
                            'has_payload' => !empty($pixCopyPaste)
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Erro ao buscar QR Code PIX específico (pagarFatura)', [
                            'invoice_id' => $invoice->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                // Se ainda não tem QR Code mas tem payload, gerar QR Code a partir do payload
                if (!$pixQrCode && $pixCopyPaste) {
                    try {
                        Log::info('Gerando QR Code a partir do payload PIX', [
                            'invoice_id' => $invoice->id,
                            'payload_length' => strlen($pixCopyPaste)
                        ]);
                        
                        // Gerar QR Code em base64 a partir do payload
                        $pixQrCode = base64_encode(QrCode::format('png')
                            ->size(300)
                            ->margin(1)
                            ->generate($pixCopyPaste));
                            
                        Log::info('QR Code gerado com sucesso a partir do payload', [
                            'invoice_id' => $invoice->id,
                            'qr_code_length' => strlen($pixQrCode)
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Erro ao gerar QR Code a partir do payload', [
                            'invoice_id' => $invoice->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                // Atualizar fatura com dados do Asaas
                $invoice->update([
                    'status' => $paymentData['status'],
                    'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
                    'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                    'pix_qr_code' => $pixQrCode,
                    'pix_copy_paste' => $pixCopyPaste,
                    'asaas_data' => $paymentData
                ]);

                // Recarregar a fatura com os dados atualizados
                $invoice = $invoice->fresh();

                Log::info('Fatura atualizada com QR Code PIX (pagarFatura)', [
                    'invoice_id' => $invoice->id,
                    'has_pix_qr_code' => !empty($invoice->pix_qr_code),
                    'pix_qr_code_length' => $invoice->pix_qr_code ? strlen($invoice->pix_qr_code) : 0,
                    'pix_copy_paste_length' => $invoice->pix_copy_paste ? strlen($invoice->pix_copy_paste) : 0
                ]);

            } catch (\Exception $e) {
                Log::error('Erro ao buscar QR Code PIX automaticamente (pagarFatura)', [
                    'invoice_id' => $invoice->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

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

            // Tentar buscar QR Code PIX se não estiver disponível
            $pixQrCode = $paymentData['pixTransaction']['encodedImage'] ?? 
                       $paymentData['pixTransaction']['qrCode'] ?? 
                       $paymentData['pixTransaction']['qrCodeImage'] ?? 
                       $paymentData['pixTransaction']['qrCodeBase64'] ?? 
                       $paymentData['encodedImage'] ?? 
                       $paymentData['qrCode'] ?? 
                       $paymentData['qrCodeImage'] ?? 
                       $paymentData['qrCodeBase64'] ?? 
                       null;
                       
            $pixCopyPaste = $paymentData['pixTransaction']['payload'] ?? 
                           $paymentData['pixTransaction']['copyPaste'] ?? 
                           $paymentData['payload'] ?? 
                           $paymentData['copyPaste'] ?? 
                           null;

            // Se não tem QR Code, tentar buscar especificamente
            if (!$pixQrCode && $paymentData['billingType'] === 'PIX') {
                try {
                    $qrCodeData = $asaasService->getPixQrCode($invoice->asaas_payment_id);
                    $pixQrCode = $qrCodeData['encodedImage'] ?? $qrCodeData['qrCode'] ?? null;
                    $pixCopyPaste = $qrCodeData['payload'] ?? $pixCopyPaste;
                    
                    Log::info('QR Code PIX obtido via método específico (rota direta)', [
                        'invoice_id' => $invoice->id,
                        'has_qr_code' => !empty($pixQrCode),
                        'has_payload' => !empty($pixCopyPaste)
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Erro ao buscar QR Code PIX específico (rota direta)', [
                        'invoice_id' => $invoice->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Se ainda não tem QR Code mas tem payload, gerar QR Code a partir do payload
            if (!$pixQrCode && $pixCopyPaste) {
                try {
                    Log::info('Gerando QR Code a partir do payload PIX (atualizarFatura)', [
                        'invoice_id' => $invoice->id,
                        'payload_length' => strlen($pixCopyPaste)
                    ]);
                    
                    // Gerar QR Code em base64 a partir do payload
                    $pixQrCode = base64_encode(QrCode::format('png')
                        ->size(300)
                        ->margin(1)
                        ->generate($pixCopyPaste));
                        
                    Log::info('QR Code gerado com sucesso a partir do payload (atualizarFatura)', [
                        'invoice_id' => $invoice->id,
                        'qr_code_length' => strlen($pixQrCode)
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erro ao gerar QR Code a partir do payload (atualizarFatura)', [
                        'invoice_id' => $invoice->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Atualizar fatura com dados do Asaas
            $invoice->update([
                'status' => $paymentData['status'],
                'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
                'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                'pix_qr_code' => $pixQrCode,
                'pix_copy_paste' => $pixCopyPaste,
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
     * Busca especificamente o QR Code PIX de uma fatura
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarQrCodePix(Request $request)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $request->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        try {
            $asaasService = new AsaasService();
            
            // Buscar QR Code PIX especificamente
            $qrCodeData = $asaasService->getPixQrCode($invoice->asaas_payment_id);
            
            $pixQrCode = $qrCodeData['encodedImage'] ?? $qrCodeData['qrCode'] ?? null;
            $pixCopyPaste = $qrCodeData['payload'] ?? null;
            
            // Se não tem QR Code mas tem payload, gerar QR Code a partir do payload
            if (!$pixQrCode && $pixCopyPaste) {
                try {
                    Log::info('Gerando QR Code a partir do payload PIX (buscarQrCodePix)', [
                        'invoice_id' => $invoice->id,
                        'payload_length' => strlen($pixCopyPaste)
                    ]);
                    
                    // Gerar QR Code em base64 a partir do payload
                    $pixQrCode = base64_encode(QrCode::format('png')
                        ->size(300)
                        ->margin(1)
                        ->generate($pixCopyPaste));
                        
                    Log::info('QR Code gerado com sucesso a partir do payload (buscarQrCodePix)', [
                        'invoice_id' => $invoice->id,
                        'qr_code_length' => strlen($pixQrCode)
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erro ao gerar QR Code a partir do payload (buscarQrCodePix)', [
                        'invoice_id' => $invoice->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            // Atualizar fatura com os dados do QR Code
            $invoice->update([
                'pix_qr_code' => $pixQrCode,
                'pix_copy_paste' => $pixCopyPaste,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code PIX obtido com sucesso!',
                'qr_code' => $pixQrCode,
                'payload' => $pixCopyPaste,
                'invoice' => $invoice
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar QR Code PIX', [
                'invoice_id' => $invoice->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar QR Code PIX: ' . $e->getMessage()
            ], 500);
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