<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDesativadoStatusToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Atualizar o enum do status para incluir 'desativado'
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('pendente', 'aprovado', 'rejeitado', 'desativado') NOT NULL DEFAULT 'pendente'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverter o enum do status para o estado anterior
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('pendente', 'aprovado', 'rejeitado') NOT NULL DEFAULT 'pendente'");
    }
}
