<?php

use Illuminate\Database\Seeder;
use App\Admin;
use App\CategoriaConta;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'João Pedro',
                'password' => Hash::make('159753'),
                'status' => true,
                'is_superadmin' => true,
                'last_login_at' => null, // Será atualizado no primeiro login
            ]
        );

        if (isset($this->command)) {
            $this->command->info('Administrador padrão semeado: admin@admin.com / 159753');
        }

        // Criar categorias padrão para Contas a Pagar
        $categoriasPagar = [
            'Água',
            'Luz',
            'Telefone/Internet',
            'Aluguel',
            'Condomínio',
            'Manutenção',
            'Material de Escritório',
            'Material de Limpeza',
            'Salários',
            'Impostos',
            'Serviços Profissionais',
            'Publicidade',
            'Seguros',
            'Combustível',
            'Transporte',
            'Outros',
        ];

        foreach ($categoriasPagar as $nome) {
            CategoriaConta::updateOrCreate(
                ['nome' => $nome, 'tipo' => 'pagar'],
                ['ativo' => true]
            );
        }

        // Criar categorias padrão para Contas a Receber
        $categoriasReceber = [
            'Mensalidades',
            'Doações',
            'Eventos',
            'Patrocínios',
            'Vendas',
            'Outros',
        ];

        foreach ($categoriasReceber as $nome) {
            CategoriaConta::updateOrCreate(
                ['nome' => $nome, 'tipo' => 'receber'],
                ['ativo' => true]
            );
        }

        if (isset($this->command)) {
            $this->command->info('Categorias de contas cadastradas!');
        }
    }
}


