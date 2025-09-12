<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssembleiasSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assembleias_sistema', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_assembleia');
            $table->time('hora_inicio');
            $table->time('hora_fim')->nullable();
            $table->string('local');
            $table->enum('tipo', ['ordinaria', 'extraordinaria']);
            $table->enum('status', ['agendada', 'em_andamento', 'concluida', 'cancelada'])->default('agendada');
            $table->string('link_presenca', 100)->unique()->nullable();
            $table->boolean('lista_presenca_ativa')->default(false);
            $table->text('pauta')->nullable();
            $table->text('observacoes')->nullable();
            $table->integer('quorum_minimo')->nullable();
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
        Schema::dropIfExists('assembleias_sistema');
    }
}
