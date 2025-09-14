<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParceriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcerias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome_empresa');
            $table->text('descricao')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('endereco')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('categoria')->default('geral');
            $table->string('tipo_desconto'); // percentual, valor_fixo, desconto_especial
            $table->decimal('valor_desconto', 8, 2); // valor do desconto (10 para 10%, 5.00 para R$ 5,00)
            $table->decimal('valor_minimo_pedido', 10, 2)->nullable(); // valor mínimo para ter desconto
            $table->text('condicoes_desconto')->nullable(); // condições específicas
            $table->boolean('ativo')->default(true);
            $table->boolean('destaque')->default(false); // se aparece em destaque
            $table->integer('ordem')->default(0); // para ordenação
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcerias');
    }
}
