<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('user_id');
            $table->string('asaas_payment_id', 50); // ID do pagamento no Asaas
            $table->decimal('value', 10, 2); // Valor pago
            $table->date('payment_date'); // Data do pagamento
            $table->enum('status', ['PENDING', 'CONFIRMED', 'RECEIVED_IN_CASH', 'OVERDUE', 'REFUNDED', 'RECEIVED_WITH_OVERDUE', 'CHARGEBACK_REQUESTED', 'CHARGEBACK_DISPUTE', 'AWAITING_CHARGEBACK_REVERSAL', 'DUNNING_REQUESTED', 'DUNNING_RECEIVED', 'AWAITING_RISK_ANALYSIS'])->default('PENDING');
            $table->string('payment_method')->nullable(); // Método de pagamento usado
            $table->text('description')->nullable(); // Descrição do pagamento
            $table->json('asaas_data')->nullable(); // Dados completos do Asaas
            $table->timestamps();
            
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['invoice_id', 'status']);
            $table->index('asaas_payment_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
