<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTipoAssociadoEnumRemoveAmbos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Primeiro, atualizar registros que têm 'ambos' para 'comerciante'
        DB::table('users')
            ->where('tipo_associado', 'ambos')
            ->update(['tipo_associado' => 'comerciante']);

        // Agora alterar o enum para remover a opção 'ambos'
        DB::statement("ALTER TABLE users MODIFY COLUMN tipo_associado ENUM('morador', 'comerciante') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Adicionar de volta a opção 'ambos' ao enum
        DB::statement("ALTER TABLE users MODIFY COLUMN tipo_associado ENUM('morador', 'comerciante', 'ambos') NOT NULL");
    }
}
