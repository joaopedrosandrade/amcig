<?php

namespace App\Console\Commands;

use App\Subscription;
use App\Services\AsaasService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CorrigirAssinaturasExistentes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assinaturas:corrigir-datas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige as datas de vencimento das assinaturas existentes para o próximo mês';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando correção das assinaturas existentes...');

        $subscriptions = Subscription::where('status', 'ACTIVE')->get();
        
        if ($subscriptions->isEmpty()) {
            $this->info('Nenhuma assinatura ativa encontrada.');
            return 0;
        }

        $this->info("Encontradas {$subscriptions->count()} assinaturas ativas.");

        foreach ($subscriptions as $subscription) {
            $this->info("Processando assinatura ID: {$subscription->id} - User: {$subscription->user_id}");
            $this->info("Data atual: {$subscription->next_due_date->format('d/m/Y')}");

            // Calcular nova data: próximo mês a partir de hoje
            $novaData = now()->addMonth();
            
            // Atualizar no banco local
            $subscription->update([
                'next_due_date' => $novaData
            ]);

            $this->info("Nova data: {$novaData->format('d/m/Y')}");

            // Tentar atualizar no Asaas também
            try {
                $asaasService = new AsaasService();
                $asaasService->updateSubscriptionNextDueDate(
                    $subscription->asaas_subscription_id, 
                    $novaData->format('Y-m-d')
                );
                $this->info("✓ Atualizada no Asaas também");
            } catch (\Exception $e) {
                $this->warn("⚠ Erro ao atualizar no Asaas: " . $e->getMessage());
            }

            $this->info("---");
        }

        $this->info('Correção concluída!');
        return 0;
    }
}
