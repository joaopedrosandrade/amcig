<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('asaas_subscription_id', 50)->unique(); // ID da assinatura no Asaas
            $table->string('asaas_customer_id', 50); // ID do cliente no Asaas
            $table->decimal('value', 10, 2); // Valor da mensalidade
            $table->string('billing_type')->default('PIX'); // Tipo de cobrança (PIX, BOLETO, CREDIT_CARD)
            $table->date('next_due_date'); // Próxima data de vencimento
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'CANCELLED', 'SUSPENDED'])->default('ACTIVE');
            $table->text('description')->nullable(); // Descrição da assinatura
            $table->json('asaas_data')->nullable(); // Dados completos retornados pelo Asaas
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index('asaas_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
