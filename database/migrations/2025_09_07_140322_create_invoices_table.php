<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subscription_id');
            $table->unsignedInteger('user_id');
            $table->string('asaas_payment_id', 50)->unique(); // ID do pagamento no Asaas
            $table->decimal('value', 10, 2); // Valor da fatura
            $table->date('due_date'); // Data de vencimento
            $table->date('payment_date')->nullable(); // Data do pagamento
            $table->enum('status', ['PENDING', 'CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'OVERDUE', 'REFUNDED', 'RECEIVED_WITH_OVERDUE', 'CHARGEBACK_REQUESTED', 'CHARGEBACK_DISPUTE', 'AWAITING_CHARGEBACK_REVERSAL', 'DUNNING_REQUESTED', 'DUNNING_RECEIVED', 'AWAITING_RISK_ANALYSIS'])->default('PENDING');
            $table->string('billing_type')->default('PIX'); // Tipo de cobrança
            $table->text('description')->nullable(); // Descrição da fatura
            $table->string('invoice_url')->nullable(); // URL do boleto/fatura
            $table->string('pix_qr_code')->nullable(); // QR Code PIX
            $table->string('pix_copy_paste')->nullable(); // Chave PIX para copiar
            $table->json('asaas_data')->nullable(); // Dados completos do Asaas
            $table->timestamps();
            
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['subscription_id', 'status']);
            $table->index('asaas_payment_id');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
