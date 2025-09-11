<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('tipo', [
                'PATRULHAMENTO_RUA',
                'ILUMINACAO_PUBLICA', 
                'MANUTENCAO_VIAS',
                'LIMPEZA_PUBLICA',
                'SEGURANCA_PUBLICA',
                'TRANSPORTE_PUBLICO',
                'SAUDE_PUBLICA',
                'EDUCACAO',
                'MEIO_AMBIENTE',
                'OUTROS'
            ]);
            $table->string('titulo');
            $table->text('descricao');
            $table->string('endereco');
            $table->string('bairro')->nullable();
            $table->string('cidade')->default('Guarulhos');
            $table->string('cep')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('prioridade', ['BAIXA', 'MEDIA', 'ALTA', 'URGENTE'])->default('MEDIA');
            $table->enum('status', [
                'ABERTA',
                'EM_ANALISE', 
                'EM_ANDAMENTO',
                'CONCLUIDA',
                'CANCELADA',
                'REJEITADA'
            ])->default('ABERTA');
            $table->text('observacoes_admin')->nullable();
            $table->timestamp('data_limite')->nullable();
            $table->timestamp('data_conclusao')->nullable();
            $table->unsignedInteger('admin_responsavel')->nullable();
            $table->foreign('admin_responsavel')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['tipo', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacoes');
    }
}
