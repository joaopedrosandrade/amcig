<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContasPagarAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_pagar', function (Blueprint $table) {
            // Adicionar campos de relacionamento
            $table->unsignedBigInteger('fornecedor_id')->nullable()->after('categoria');
            $table->unsignedBigInteger('categoria_id')->nullable()->after('categoria');
            
            // Foreign keys
            $table->foreign('fornecedor_id')->references('id')->on('fornecedores')->onDelete('set null');
            $table->foreign('categoria_id')->references('id')->on('categorias_contas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->dropForeign(['fornecedor_id']);
            $table->dropForeign(['categoria_id']);
            $table->dropColumn(['fornecedor_id', 'categoria_id']);
        });
    }
}

