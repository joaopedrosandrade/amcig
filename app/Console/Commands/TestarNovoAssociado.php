<?php

namespace App\Console\Commands;

use App\User;
use App\Subscription;
use App\Invoice;
use App\Services\AsaasService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestarNovoAssociado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:novo-associado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a criação de um novo associado e verifica se a primeira fatura é gerada';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando teste de criação de novo associado...');

        try {
            DB::beginTransaction();

            // Criar usuário de teste
            $user = User::create([
                'name' => 'Teste Associado',
                'email' => 'joaopedrooandrade+746@gmail.com',
                'cpf' => '356.127.300-98',
                'data_nascimento' => '1990-01-01',
                'telefone' => '(33) 99197-0656',
                'cep' => '01234-567',
                'logradouro' => 'Rua Teste',
                'numero' => '123',
                'bairro' => 'Centro',
                'cidade' => 'São Paulo',
                'uf' => 'SP',
                'tipo_associado' => 'morador',
                'status' => 'pendente',
                'password' => bcrypt('123456')
            ]);

            $this->info("Usuário criado: ID {$user->id}");

            // Simular aprovação
            $user->update([
                'status' => 'aprovado',
                'data_aprovacao' => now()
            ]);

            $this->info('Usuário aprovado');

            // Criar cliente e assinatura no Asaas
            $asaasService = new AsaasService();
            
            $customerData = $asaasService->createCustomer($user);
            $asaasCustomerId = $customerData['id'];
            $this->info("Cliente criado no Asaas: {$asaasCustomerId}");

            $subscriptionData = $asaasService->createSubscription($user, $asaasCustomerId);
            $asaasSubscriptionId = $subscriptionData['id'];
            $this->info("Assinatura criada no Asaas: {$asaasSubscriptionId}");

            // Salvar assinatura no banco local
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'asaas_subscription_id' => $asaasSubscriptionId,
                'asaas_customer_id' => $asaasCustomerId,
                'value' => $user->getMonthlyValue(),
                'billing_type' => 'PIX',
                'next_due_date' => \Carbon\Carbon::parse($subscriptionData['nextDueDate']),
                'status' => 'ACTIVE',
                'description' => 'Mensalidade AMCIG - ' . ucfirst($user->tipo_associado),
                'asaas_data' => $subscriptionData
            ]);

            $this->info("Assinatura salva no banco: ID {$subscription->id}");

            // Verificar se primeira cobrança foi criada
            if (isset($subscriptionData['first_payment'])) {
                $firstPayment = $subscriptionData['first_payment'];
                
                $invoice = Invoice::create([
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
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

                $this->info("✅ Primeira fatura criada: ID {$invoice->id}");
                $this->info("   - Valor: R$ {$invoice->value}");
                $this->info("   - Vencimento: {$invoice->due_date->format('d/m/Y')}");
                $this->info("   - Status: {$invoice->status}");
            } else {
                $this->error("❌ Primeira cobrança não foi criada!");
            }

            // Verificar faturas no banco
            $invoices = Invoice::where('user_id', $user->id)->get();
            $this->info("Total de faturas no banco: {$invoices->count()}");

            DB::commit();

            $this->info('✅ Teste concluído com sucesso!');
            $this->info("Usuário ID: {$user->id}");
            $this->info("Assinatura ID: {$subscription->id}");
            $this->info("Próximo vencimento: {$subscription->next_due_date->format('d/m/Y')}");

            return 0;

        } catch (\Exception $e) {
            DB::rollback();
            $this->error("❌ Erro no teste: " . $e->getMessage());
            return 1;
        }
    }
}
