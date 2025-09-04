<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class MatriculaTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar alguns usuários de teste para verificar a geração da matrícula
        $usuarios = [
            [
                'name' => 'João Silva',
                'email' => 'joao.silva@teste.com',
                'password' => Hash::make('password'),
                'cpf' => '123.456.789-01',
                'data_nascimento' => '1985-05-15',
                'telefone' => '(27) 99999-1111',
                'cep' => '29930-000',
                'logradouro' => 'Rua das Flores',
                'numero' => '123',
                'complemento' => 'Apto 101',
                'bairro' => 'Centro',
                'cidade' => 'São Mateus',
                'uf' => 'ES',
                'tipo_associado' => 'morador',
                'status' => 'aprovado',
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@teste.com',
                'password' => Hash::make('password'),
                'cpf' => '987.654.321-09',
                'data_nascimento' => '1990-08-22',
                'telefone' => '(27) 99999-2222',
                'cep' => '29930-100',
                'logradouro' => 'Avenida Principal',
                'numero' => '456',
                'complemento' => 'Loja 2',
                'bairro' => 'Centro',
                'cidade' => 'São Mateus',
                'uf' => 'ES',
                'tipo_associado' => 'comerciante',
                'nome_comercio' => 'Loja da Maria',
                'endereco_comercio' => 'Avenida Principal, 456 - Centro',
                'ramo_atividade' => 'Vestuário',
                'status' => 'aprovado',
            ],
            [
                'name' => 'Pedro Oliveira',
                'email' => 'pedro.oliveira@teste.com',
                'password' => Hash::make('password'),
                'cpf' => '111.222.333-44',
                'data_nascimento' => '1978-12-03',
                'telefone' => '(27) 99999-3333',
                'cep' => '29930-200',
                'logradouro' => 'Rua do Comércio',
                'numero' => '789',
                'complemento' => null,
                'bairro' => 'Centro',
                'cidade' => 'São Mateus',
                'uf' => 'ES',
                'tipo_associado' => 'morador',
                'status' => 'aprovado',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }

        $this->command->info('Usuários de teste criados com sucesso!');
        $this->command->info('Verifique as matrículas geradas automaticamente.');
    }
}
