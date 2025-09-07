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
                'body' => $request->all()
            ]);

            // Verificar se é um webhook válido do Asaas
            $event = $request->input('event');
            $payment = $request->input('payment');

            if (!$event || !$payment) {
                Log::warning('Webhook do Asaas com dados incompletos', $request->all());
                return response('Dados incompletos', 400);
            }

            // Processar webhook usando o AsaasService
            $asaasService = new AsaasService();
            $asaasService->processWebhook($request->all());

            Log::info('Webhook processado com sucesso', [
                'event' => $event,
                'payment_id' => $payment['id'] ?? null
            ]);

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook do Asaas', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response('Erro interno', 500);
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
}