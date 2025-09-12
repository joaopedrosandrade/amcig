<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresencasSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presencas_sistema', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assembleia_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('nome')->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->timestamp('data_presenca');
            $table->string('ip_address')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->foreign('assembleia_id')->references('id')->on('assembleias_sistema')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unique(['assembleia_id', 'user_id'], 'unique_user_assembly');
            $table->unique(['assembleia_id', 'cpf'], 'unique_cpf_assembly');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presencas_sistema');
    }
}
