<?php

namespace App\Services;

use App\User;
use App\Subscription;
use App\Invoice;
use App\Payment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    private $apiKey;
    private $baseUrl;
    private $environment;

    public function __construct()
    {
        $this->apiKey = '$aact_hmlg_000MzkwODA2MWY2OGM3MWRlMDU2NWM3MzJlNzZmNGZhZGY6OjA2MTYzNDZkLWI0NjYtNDM0Yi1iNzAzLTZmMTAwN2NkYTAzMTo6JGFhY2hfYmMzZDdkMWUtMzU4ZC00MzMwLTg2N2MtNGMxODZiZDQyZjUw';
        $this->environment = 'sandbox'; // ou 'production'
        $this->baseUrl = $this->environment === 'sandbox' 
            ? 'https://api-sandbox.asaas.com/v3' 
            : 'https://api.asaas.com/v3';
    }

    /**
     * Criar cliente no Asaas
     */
    public function createCustomer(User $user): array
    {
        try {
            $client = new Client();
            
            $response = $client->post($this->baseUrl . '/customers', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'cpfCnpj' => preg_replace('/[^0-9]/', '', $user->cpf),
                    'phone' => $user->telefone,
                    'mobilePhone' => $user->telefone,
                    'postalCode' => preg_replace('/[^0-9]/', '', $user->cep),
                    'address' => $user->logradouro,
                    'addressNumber' => $user->numero,
                    'complement' => $user->complemento,
                    'province' => $user->bairro,
                    'city' => $user->cidade,
                    'state' => $user->uf,
                    'externalReference' => $user->id,
                    'notificationDisabled' => false
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Cliente criado no Asaas', ['user_id' => $user->id, 'asaas_customer_id' => $data['id']]);
            return $data;

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao criar cliente no Asaas', [
                'user_id' => $user->id,
                'status' => $statusCode,
                'response' => $body
            ]);
            throw new \Exception('Erro ao criar cliente no Asaas: ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao criar cliente no Asaas', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Criar assinatura no Asaas
     */
    public function createSubscription(User $user, string $asaasCustomerId): array
    {
        try {
            $client = new Client();
            $monthlyValue = $user->getMonthlyValue();
            $nextDueDate = now()->addMonth()->format('Y-m-d');

            $response = $client->post($this->baseUrl . '/subscriptions', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'customer' => $asaasCustomerId,
                    'billingType' => 'PIX',
                    'value' => $monthlyValue,
                    'nextDueDate' => $nextDueDate,
                    'cycle' => 'MONTHLY',
                    'description' => 'Mensalidade AMCIG - ' . ucfirst($user->tipo_associado),
                    'externalReference' => 'AMCIG_' . $user->id,
                    'endDate' => null, // Assinatura sem data de fim
                    'maxPayments' => null, // Sem limite de pagamentos
                    'sendPaymentByEmail' => true,
                    'notificationDisabled' => false
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Assinatura criada no Asaas', [
                'user_id' => $user->id,
                'asaas_subscription_id' => $data['id'],
                'value' => $monthlyValue
            ]);
            return $data;

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao criar assinatura no Asaas', [
                'user_id' => $user->id,
                'status' => $statusCode,
                'response' => $body
            ]);
            throw new \Exception('Erro ao criar assinatura no Asaas: ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao criar assinatura no Asaas', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obter informações de uma assinatura
     */
    public function getSubscription(string $asaasSubscriptionId): array
    {
        try {
            $client = new Client();
            
            $response = $client->get($this->baseUrl . '/subscriptions/' . $asaasSubscriptionId, [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao obter assinatura no Asaas', [
                'subscription_id' => $asaasSubscriptionId,
                'status' => $statusCode,
                'response' => $body
            ]);
            throw new \Exception('Erro ao obter assinatura no Asaas: ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao obter assinatura no Asaas', [
                'subscription_id' => $asaasSubscriptionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Listar cobranças de uma assinatura
     */
    public function getSubscriptionPayments(string $asaasSubscriptionId): array
    {
        try {
            $client = new Client();
            
            $response = $client->get($this->baseUrl . '/subscriptions/' . $asaasSubscriptionId . '/payments', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao obter cobranças da assinatura no Asaas', [
                'subscription_id' => $asaasSubscriptionId,
                'status' => $statusCode,
                'response' => $body
            ]);
            throw new \Exception('Erro ao obter cobranças da assinatura no Asaas: ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao obter cobranças da assinatura no Asaas', [
                'subscription_id' => $asaasSubscriptionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obter informações de um pagamento específico
     */
    public function getPayment(string $asaasPaymentId): array
    {
        try {
            $client = new Client();
            
            $response = $client->get($this->baseUrl . '/payments/' . $asaasPaymentId, [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao obter pagamento no Asaas', [
                'payment_id' => $asaasPaymentId,
                'status' => $statusCode,
                'response' => $body
            ]);
            throw new \Exception('Erro ao obter pagamento no Asaas: ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao obter pagamento no Asaas', [
                'payment_id' => $asaasPaymentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Cancelar assinatura
     */
    public function cancelSubscription(string $asaasSubscriptionId): array
    {
        try {
            $client = new Client();
            
            $response = $client->delete($this->baseUrl . '/subscriptions/' . $asaasSubscriptionId, [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Assinatura cancelada no Asaas', [
                'subscription_id' => $asaasSubscriptionId
            ]);
            return $data;

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao cancelar assinatura no Asaas', [
                'subscription_id' => $asaasSubscriptionId,
                'status' => $statusCode,
                'response' => $body
            ]);
            throw new \Exception('Erro ao cancelar assinatura no Asaas: ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao cancelar assinatura no Asaas', [
                'subscription_id' => $asaasSubscriptionId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Processar webhook do Asaas
     */
    public function processWebhook(array $webhookData): void
    {
        try {
            $event = $webhookData['event'] ?? null;
            $payment = $webhookData['payment'] ?? null;

            if (!$event || !$payment) {
                Log::warning('Webhook do Asaas com dados incompletos', $webhookData);
                return;
            }

            Log::info('Processando webhook do Asaas', [
                'event' => $event,
                'payment_id' => $payment['id'] ?? null
            ]);

            // Buscar fatura pelo ID do pagamento no Asaas
            $invoice = Invoice::where('asaas_payment_id', $payment['id'])->first();

            if (!$invoice) {
                Log::warning('Fatura não encontrada para o pagamento', [
                    'asaas_payment_id' => $payment['id']
                ]);
                return;
            }

            // Atualizar status da fatura
            $invoice->update([
                'status' => $payment['status'],
                'payment_date' => $payment['paymentDate'] ? \Carbon\Carbon::parse($payment['paymentDate']) : null,
                'asaas_data' => $payment
            ]);

            // Criar registro de pagamento se necessário
            if (in_array($payment['status'], ['CONFIRMED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])) {
                Payment::updateOrCreate(
                    ['asaas_payment_id' => $payment['id']],
                    [
                        'invoice_id' => $invoice->id,
                        'user_id' => $invoice->user_id,
                        'value' => $payment['value'],
                        'payment_date' => \Carbon\Carbon::parse($payment['paymentDate']),
                        'status' => $payment['status'],
                        'payment_method' => $payment['billingType'] ?? null,
                        'description' => $payment['description'] ?? null,
                        'asaas_data' => $payment
                    ]
                );
            }

            Log::info('Webhook processado com sucesso', [
                'invoice_id' => $invoice->id,
                'payment_id' => $payment['id'],
                'status' => $payment['status']
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook do Asaas', [
                'error' => $e->getMessage(),
                'webhook_data' => $webhookData
            ]);
            throw $e;
        }
    }
}
