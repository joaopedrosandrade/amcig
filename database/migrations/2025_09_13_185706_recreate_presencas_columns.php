<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RecreatePresencasColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Renomear coluna usando SQL direto
        DB::statement('ALTER TABLE presencas_eventos CHANGE assembleia_id evento_id INT UNSIGNED');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE presencas_eventos CHANGE evento_id assembleia_id INT UNSIGNED');
    }
}