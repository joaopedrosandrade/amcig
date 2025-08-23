<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class AssociadoTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria um associado de teste
        User::create([
            'name' => 'João Silva Santos',
            'cpf' => '123.456.789-00',
            'data_nascimento' => '1985-05-15',
            'telefone' => '(27) 99999-9999',
            'email' => 'joao.silva@teste.com',
            'password' => Hash::make('123456'),
            'cep' => '29946-000',
            'logradouro' => 'Rua das Palmeiras',
            'numero' => '123',
            'complemento' => 'Apto 101',
            'bairro' => 'Centro',
            'cidade' => 'São Mateus',
            'uf' => 'ES',
            'tipo_associado' => 'morador',
            'status' => 'aprovado',
        ]);

        // Cria um comerciante de teste
        User::create([
            'name' => 'Maria Oliveira Costa',
            'cpf' => '987.654.321-00',
            'data_nascimento' => '1978-12-03',
            'telefone' => '(27) 88888-8888',
            'email' => 'maria.costa@teste.com',
            'password' => Hash::make('123456'),
            'cep' => '29946-100',
            'logradouro' => 'Avenida Principal',
            'numero' => '456',
            'complemento' => 'Loja 2',
            'bairro' => 'Comercial',
            'cidade' => 'São Mateus',
            'uf' => 'ES',
            'tipo_associado' => 'comerciante',
            'nome_comercio' => 'Padaria São Mateus',
            'endereco_comercio' => 'Avenida Principal, 456 - Loja 2, Comercial, São Mateus-ES',
            'ramo_atividade' => 'alimentacao',
            'status' => 'aprovado',
        ]);

        $this->command->info('Associados de teste criados com sucesso!');
    }
}
