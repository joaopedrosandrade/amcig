<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssociadoFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos pessoais
            $table->string('cpf', 14)->unique()->after('name');
            $table->date('data_nascimento')->after('cpf');
            $table->string('telefone', 20)->after('data_nascimento');
            
            // Endereço
            $table->string('cep', 9)->after('telefone');
            $table->string('logradouro')->after('cep');
            $table->string('numero', 20)->after('logradouro');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('bairro')->after('complemento');
            $table->string('cidade')->default('São Mateus')->after('bairro');
            $table->string('uf', 2)->default('ES')->after('cidade');
            
            // Tipo de associado
            $table->enum('tipo_associado', ['morador', 'comerciante', 'ambos'])->after('uf');
            
            // Campos do comércio (nullable)
            $table->string('nome_comercio')->nullable()->after('tipo_associado');
            $table->text('endereco_comercio')->nullable()->after('nome_comercio');
            $table->string('ramo_atividade')->nullable()->after('endereco_comercio');
            
            // Status do associado
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado'])->default('pendente')->after('ramo_atividade');
            $table->timestamp('data_aprovacao')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cpf', 'data_nascimento', 'telefone', 'cep', 'logradouro', 
                'numero', 'complemento', 'bairro', 'cidade', 'uf', 'tipo_associado',
                'nome_comercio', 'endereco_comercio', 'ramo_atividade', 'status', 'data_aprovacao'
            ]);
        });
    }
}
