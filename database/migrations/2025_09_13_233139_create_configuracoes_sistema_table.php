<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracoesSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes_sistema', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chave', 100)->unique();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('categoria')->default('geral');
            $table->boolean('valor_boolean')->nullable();
            $table->string('valor_string')->nullable();
            $table->integer('valor_integer')->nullable();
            $table->text('valor_text')->nullable();
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('configuracoes_sistema');
    }
}
