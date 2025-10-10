<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContaBancariaToContasPagar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->unsignedBigInteger('conta_bancaria_id')->nullable()->after('evento_id');
            
            // Foreign key
            $table->foreign('conta_bancaria_id')->references('id')->on('contas_bancarias')->onDelete('set null');
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
            $table->dropForeign(['conta_bancaria_id']);
            $table->dropColumn('conta_bancaria_id');
        });
    }
}

