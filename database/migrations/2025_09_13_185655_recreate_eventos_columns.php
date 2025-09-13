<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RecreateEventosColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Renomear coluna usando SQL direto
        DB::statement('ALTER TABLE eventos_sistema CHANGE data_assembleia data_evento DATE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE eventos_sistema CHANGE data_evento data_assembleia DATE');
    }
}