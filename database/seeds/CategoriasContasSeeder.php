<?php

use Illuminate\Database\Seeder;
use App\CategoriaConta;

class CategoriasContasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            'Alimentação',
            'Outros',
        ];

        foreach ($categoriasPagar as $nome) {
            CategoriaConta::create([
                'nome' => $nome,
                'tipo' => 'pagar',
                'ativo' => true,
            ]);
        }

        $categoriasReceber = [
            'Mensalidades',
            'Doações',
            'Eventos',
            'Patrocínios',
            'Vendas',
            'Outros',
        ];

        foreach ($categoriasReceber as $nome) {
            CategoriaConta::create([
                'nome' => $nome,
                'tipo' => 'receber',
                'ativo' => true,
            ]);
        }

        echo "Categorias cadastradas com sucesso!\n";
    }
}

