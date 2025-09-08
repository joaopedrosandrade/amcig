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
     * Validar e formatar CPF
     */
    private function validarCPF($cpf): string
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            throw new \Exception('CPF deve ter 11 dígitos');
        }
        
        // Verifica se não é uma sequência de números iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new \Exception('CPF inválido: sequência de números iguais');
        }
        
        // Validação básica do CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                throw new \Exception('CPF inválido: dígito verificador incorreto');
            }
        }
        
        return $cpf;
    }

    /**
     * Criar cliente no Asaas
     */
    public function createCustomer(User $user): array
    {
        // Validar dados obrigatórios
        if (empty($user->name) || empty($user->email) || empty($user->cpf)) {
            throw new \Exception('Dados obrigatórios não encontrados: nome, email ou CPF');
        }

        // Validar e formatar CPF
        try {
            $cpfValidado = $this->validarCPF($user->cpf);
        } catch (\Exception $e) {
            throw new \Exception('CPF inválido: ' . $e->getMessage());
        }

        try {
            $client = new Client([
                'timeout' => 30,
                'verify' => false // Para desenvolvimento, desabilita verificação SSL
            ]);
            
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'cpfCnpj' => $cpfValidado,
                'phone' => $user->telefone ?: '',
                'mobilePhone' => $user->telefone ?: '',
                'postalCode' => preg_replace('/[^0-9]/', '', $user->cep ?: ''),
                'address' => $user->logradouro ?: '',
                'addressNumber' => $user->numero ?: '',
                'complement' => $user->complemento ?: '',
                'province' => $user->bairro ?: '',
                'city' => $user->cidade ?: '',
                'state' => $user->uf ?: '',
                'externalReference' => (string)$user->id,
                'notificationDisabled' => false
            ];

            Log::info('Tentando criar cliente no Asaas', [
                'user_id' => $user->id,
                'url' => $this->baseUrl . '/customers',
                'data' => $data,
                'api_key_preview' => substr($this->apiKey, 0, 20) . '...',
                'cpf_original' => $user->cpf,
                'cpf_validado' => $cpfValidado,
                'user_data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'cpf' => $user->cpf,
                    'telefone' => $user->telefone,
                    'cep' => $user->cep,
                    'logradouro' => $user->logradouro,
                    'numero' => $user->numero,
                    'complemento' => $user->complemento,
                    'bairro' => $user->bairro,
                    'cidade' => $user->cidade,
                    'uf' => $user->uf
                ]
            ]);
            
            $response = $client->post($this->baseUrl . '/customers', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            Log::info('Cliente criado no Asaas', ['user_id' => $user->id, 'asaas_customer_id' => $responseData['id']]);
            return $responseData;

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao criar cliente no Asaas (RequestException)', [
                'user_id' => $user->id,
                'status' => $statusCode,
                'response' => $body,
                'message' => $e->getMessage(),
                'url' => $this->baseUrl . '/customers',
                'cpf_original' => $user->cpf,
                'cpf_validado' => $cpfValidado ?? 'não validado'
            ]);
            throw new \Exception('Erro ao criar cliente no Asaas (Status: ' . $statusCode . '): ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao criar cliente no Asaas', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $this->baseUrl . '/customers',
                'cpf_original' => $user->cpf
            ]);
            throw new \Exception('Exceção ao criar cliente no Asaas: ' . $e->getMessage());
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

            $subscriptionData = [
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
                'notificationDisabled' => false,
                'split' => null // Sem split
            ];

            Log::info('Tentando criar assinatura no Asaas', [
                'user_id' => $user->id,
                'url' => $this->baseUrl . '/subscriptions',
                'data' => $subscriptionData,
                'api_key_preview' => substr($this->apiKey, 0, 20) . '...'
            ]);

            $response = $client->post($this->baseUrl . '/subscriptions', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $subscriptionData,
                'timeout' => 30,
                'verify' => false // Para desenvolvimento
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Assinatura criada no Asaas', [
                'user_id' => $user->id,
                'asaas_subscription_id' => $data['id'],
                'value' => $monthlyValue,
                'response_data' => $data
            ]);

            // Gerar primeira cobrança imediatamente
            try {
                $firstPaymentData = $this->createFirstPayment($user, $asaasCustomerId, $monthlyValue);
                $data['first_payment'] = $firstPaymentData;
                
                Log::info('Primeira cobrança criada', [
                    'user_id' => $user->id,
                    'payment_id' => $firstPaymentData['id'],
                    'value' => $monthlyValue
                ]);
            } catch (\Exception $e) {
                Log::warning('Erro ao criar primeira cobrança', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Não falha a criação da assinatura se a primeira cobrança falhar
            }

            return $data;

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao criar assinatura no Asaas (RequestException)', [
                'user_id' => $user->id,
                'status' => $statusCode,
                'response' => $body,
                'message' => $e->getMessage(),
                'url' => $this->baseUrl . '/subscriptions'
            ]);
            throw new \Exception('Erro ao criar assinatura no Asaas (Status: ' . $statusCode . '): ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao criar assinatura no Asaas', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $this->baseUrl . '/subscriptions'
            ]);
            throw new \Exception('Exceção ao criar assinatura no Asaas: ' . $e->getMessage());
        }
    }

    /**
     * Criar primeira cobrança imediatamente após aprovação
     */
    private function createFirstPayment(User $user, string $asaasCustomerId, float $value): array
    {
        try {
            $client = new Client();
            $dueDate = now()->format('Y-m-d'); // Vencimento para hoje

            $paymentData = [
                'customer' => $asaasCustomerId,
                'billingType' => 'PIX',
                'value' => $value,
                'dueDate' => $dueDate,
                'description' => 'Primeira mensalidade AMCIG - ' . ucfirst($user->tipo_associado),
                'externalReference' => 'AMCIG_FIRST_' . $user->id,
                'sendPaymentByEmail' => true,
                'notificationDisabled' => false
            ];

            Log::info('Tentando criar primeira cobrança no Asaas', [
                'user_id' => $user->id,
                'url' => $this->baseUrl . '/payments',
                'data' => $paymentData,
                'api_key_preview' => substr($this->apiKey, 0, 20) . '...'
            ]);

            $response = $client->post($this->baseUrl . '/payments', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => $paymentData,
                'timeout' => 30,
                'verify' => false // Para desenvolvimento
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Primeira cobrança criada no Asaas', [
                'user_id' => $user->id,
                'asaas_payment_id' => $data['id'],
                'value' => $value,
                'due_date' => $dueDate,
                'response_data' => $data
            ]);

            // Aguardar um pouco e buscar os dados completos do pagamento (incluindo PIX)
            sleep(2); // Aguardar 2 segundos para o Asaas processar
            
            try {
                $completePaymentData = $this->getPayment($data['id']);
                Log::info('Dados completos do pagamento obtidos', [
                    'payment_id' => $data['id'],
                    'has_pix' => isset($completePaymentData['pixTransaction']),
                    'status' => $completePaymentData['status'] ?? 'unknown'
                ]);
                
                // Retornar os dados completos
                return $completePaymentData;
            } catch (\Exception $e) {
                Log::warning('Erro ao buscar dados completos do pagamento', [
                    'payment_id' => $data['id'],
                    'error' => $e->getMessage()
                ]);
                // Retornar os dados básicos mesmo se falhar
                return $data;
            }

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao criar primeira cobrança no Asaas (RequestException)', [
                'user_id' => $user->id,
                'status' => $statusCode,
                'response' => $body,
                'message' => $e->getMessage(),
                'url' => $this->baseUrl . '/payments'
            ]);
            throw new \Exception('Erro ao criar primeira cobrança no Asaas (Status: ' . $statusCode . '): ' . $body);
        } catch (\Exception $e) {
            Log::error('Exceção ao criar primeira cobrança no Asaas', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $this->baseUrl . '/payments'
            ]);
            throw new \Exception('Exceção ao criar primeira cobrança no Asaas: ' . $e->getMessage());
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
            
            Log::info('Buscando pagamento no Asaas', [
                'payment_id' => $asaasPaymentId,
                'url' => $this->baseUrl . '/payments/' . $asaasPaymentId
            ]);
            
            $response = $client->get($this->baseUrl . '/payments/' . $asaasPaymentId, [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 30,
                'verify' => false // Para desenvolvimento
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            Log::info('Pagamento encontrado no Asaas', [
                'payment_id' => $asaasPaymentId,
                'status' => $data['status'] ?? 'unknown',
                'has_pix' => isset($data['pixTransaction']),
                'response_keys' => array_keys($data)
            ]);

            return $data;

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao obter pagamento no Asaas', [
                'payment_id' => $asaasPaymentId,
                'status' => $statusCode,
                'response' => $body,
                'url' => $this->baseUrl . '/payments/' . $asaasPaymentId
            ]);
            throw new \Exception('Erro ao obter pagamento no Asaas (Status: ' . $statusCode . '): ' . $body);
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
     * Testar conectividade com a API do Asaas
     */
    public function testConnection(): array
    {
        try {
            $client = new Client([
                'timeout' => 30,
                'verify' => false
            ]);
            
            Log::info('Testando conexão com Asaas', [
                'url' => $this->baseUrl,
                'api_key_length' => strlen($this->apiKey)
            ]);
            
            $response = $client->get($this->baseUrl . '/customers', [
                'headers' => [
                    'access_token' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'query' => [
                    'limit' => 1
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            Log::info('Conexão com Asaas OK', ['status' => $response->getStatusCode()]);
            
            return [
                'success' => true,
                'status' => $response->getStatusCode(),
                'data' => $data
            ];

        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 'unknown';
            $body = $response ? $response->getBody()->getContents() : 'unknown';
            
            Log::error('Erro ao testar conexão com Asaas', [
                'status' => $statusCode,
                'response' => $body,
                'message' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'status' => $statusCode,
                'error' => $body,
                'message' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            Log::error('Exceção ao testar conexão com Asaas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
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
