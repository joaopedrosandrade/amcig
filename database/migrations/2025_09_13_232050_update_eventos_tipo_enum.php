<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventosTipoEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Usar SQL raw para modificar o enum
        DB::statement("ALTER TABLE eventos_sistema MODIFY COLUMN tipo ENUM('ordinaria', 'extraordinaria', 'assembleia', 'reuniao', 'palestra', 'workshop', 'outro')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverter para os valores originais
        DB::statement("ALTER TABLE eventos_sistema MODIFY COLUMN tipo ENUM('ordinaria', 'extraordinaria')");
    }
}
