<?php

namespace App\Http\Controllers;

use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Processa webhooks do Asaas
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function asaas(Request $request)
    {
        try {
            Log::info('Webhook recebido do Asaas', [
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'raw_body' => $request->getContent()
            ]);

            // Verificar se é um webhook válido do Asaas
            $event = $request->input('event');
            $payment = $request->input('payment');

            if (!$event || !$payment) {
                Log::warning('Webhook do Asaas com dados incompletos', [
                    'event' => $event,
                    'has_payment' => !empty($payment),
                    'full_request' => $request->all()
                ]);
                return response('Dados incompletos', 400);
            }

            Log::info('Dados do webhook validados', [
                'event' => $event,
                'payment_id' => $payment['id'] ?? null,
                'payment_status' => $payment['status'] ?? null,
                'external_reference' => $payment['externalReference'] ?? null
            ]);

            // Processar webhook usando o AsaasService
            $asaasService = new AsaasService();
            $asaasService->processWebhook($request->all());

            Log::info('Webhook processado com sucesso', [
                'event' => $event,
                'payment_id' => $payment['id'] ?? null,
                'status' => $payment['status'] ?? null
            ]);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook do Asaas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'raw_body' => $request->getContent()
            ]);

            return response('Erro interno: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Testa o webhook (apenas para desenvolvimento)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
        Log::info('Teste de webhook', $request->all());
        return response('Teste OK', 200);
    }

    /**
     * Simula um webhook de pagamento PIX para teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function simulatePixPayment(Request $request)
    {
        try {
            $paymentId = $request->input('payment_id');
            
            if (!$paymentId) {
                return response()->json(['error' => 'payment_id é obrigatório'], 400);
            }

            // Simular dados de webhook do Asaas para PIX pago
            $webhookData = [
                'event' => 'PAYMENT_CONFIRMED',
                'payment' => [
                    'id' => $paymentId,
                    'status' => 'CONFIRMED',
                    'billingType' => 'PIX',
                    'value' => 50.00,
                    'paymentDate' => now()->format('Y-m-d H:i:s'),
                    'description' => 'Mensalidade AMCIG - Teste PIX',
                    'externalReference' => 'AMCIG_1' // Substitua pelo ID do usuário de teste
                ]
            ];

            // Processar webhook
            $asaasService = new AsaasService();
            $asaasService->processWebhook($webhookData);

            return response()->json([
                'success' => true,
                'message' => 'Webhook de PIX simulado com sucesso',
                'webhook_data' => $webhookData
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao simular webhook de PIX', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}