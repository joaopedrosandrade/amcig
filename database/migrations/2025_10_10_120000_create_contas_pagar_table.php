<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContasPagarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // Informações Básicas
            $table->string('descricao');
            $table->text('observacoes')->nullable();
            $table->decimal('valor', 10, 2);
            
            // Categoria e Fornecedor
            $table->string('categoria'); // Ex: Manutenção, Água, Luz, Telefone, etc
            $table->string('fornecedor');
            $table->string('cnpj_fornecedor')->nullable();
            $table->string('telefone_fornecedor')->nullable();
            $table->string('email_fornecedor')->nullable();
            
            // Dados da Nota Fiscal
            $table->string('numero_nota_fiscal')->nullable();
            $table->string('serie_nota_fiscal')->nullable();
            $table->date('data_emissao_nota')->nullable();
            $table->string('chave_acesso_nfe')->nullable();
            
            // Datas
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->date('data_competencia')->nullable(); // Mês/Ano de referência
            
            // Pagamento
            $table->enum('status', ['pendente', 'pago', 'vencido', 'cancelado'])->default('pendente');
            $table->enum('forma_pagamento', ['dinheiro', 'pix', 'transferencia', 'boleto', 'cartao_credito', 'cartao_debito', 'cheque'])->nullable();
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->decimal('juros', 10, 2)->default(0);
            $table->decimal('multa', 10, 2)->default(0);
            $table->decimal('desconto', 10, 2)->default(0);
            
            // Parcelamento
            $table->boolean('parcelado')->default(false);
            $table->integer('numero_parcela')->nullable(); // Ex: 1 de 3
            $table->integer('total_parcelas')->nullable();
            $table->unsignedBigInteger('conta_pagar_origem_id')->nullable(); // Referência à conta original se for parcelada
            
            // Anexos e Comprovantes
            $table->string('comprovante_pagamento')->nullable();
            $table->string('arquivo_nota_fiscal')->nullable();
            
            // Responsável pelo cadastro/pagamento
            $table->unsignedBigInteger('cadastrado_por')->nullable();
            $table->unsignedBigInteger('pago_por')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('cadastrado_por')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('pago_por')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('conta_pagar_origem_id')->references('id')->on('contas_pagar')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas_pagar');
    }
}

