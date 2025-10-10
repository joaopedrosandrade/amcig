<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContasBancariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_bancarias', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // Informações da Conta
            $table->string('nome'); // Ex: "Caixa Econômica - Conta Corrente"
            $table->string('banco');
            $table->string('agencia')->nullable();
            $table->string('numero_conta')->nullable();
            $table->enum('tipo_conta', ['corrente', 'poupanca', 'aplicacao', 'caixa'])->default('corrente');
            
            // Saldo
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->decimal('saldo_atual', 15, 2)->default(0);
            
            // Dados Adicionais
            $table->string('titular')->nullable();
            $table->string('cpf_cnpj_titular')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('principal')->default(false); // Conta principal/padrão
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas_bancarias');
    }
}

