<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Solicitacao;

class SolicitacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar usuários existentes
        $users = User::where('status', 'APROVADO')->take(3)->get();
        
        if ($users->count() == 0) {
            $this->command->info('Nenhum usuário aprovado encontrado. Criando solicitações de exemplo...');
            return;
        }
        
        $solicitacoes = [
            [
                'user_id' => $users[0]->id,
                'tipo' => 'ILUMINACAO_PUBLICA',
                'titulo' => 'Falta de iluminação na Rua das Flores',
                'descricao' => 'A rua das Flores está completamente sem iluminação há mais de uma semana. Isso está causando insegurança para os moradores, especialmente à noite. A situação é crítica pois há muitas crianças e idosos na região.',
                'endereco' => 'Rua das Flores, 123',
                'bairro' => 'Centro',
                'cidade' => 'São Mateus',
                'cep' => '07000-000',
                'latitude' => -18.7167,
                'longitude' => -39.8667,
                'prioridade' => 'ALTA',
                'status' => 'ABERTA'
            ],
            [
                'user_id' => $users[0]->id,
                'tipo' => 'PATRULHAMENTO_RUA',
                'titulo' => 'Necessidade de patrulhamento no Parque Municipal',
                'descricao' => 'O Parque Municipal tem sido palco de vários assaltos nos últimos dias. Os frequentadores estão com medo de usar o local. Seria importante aumentar o patrulhamento, especialmente nos horários de maior movimento (manhã e final da tarde).',
                'endereco' => 'Parque Municipal, s/n',
                'bairro' => 'Centro',
                'cidade' => 'São Mateus',
                'cep' => '07000-000',
                'latitude' => -18.7000,
                'longitude' => -39.8500,
                'prioridade' => 'URGENTE',
                'status' => 'EM_ANALISE'
            ],
            [
                'user_id' => $users[0]->id,
                'tipo' => 'MANUTENCAO_VIAS',
                'titulo' => 'Buraco na Avenida Principal',
                'descricao' => 'Existe um buraco grande na Avenida Principal, próximo ao número 456. O buraco está causando danos aos veículos e representa perigo para os pedestres. Já houve alguns acidentes menores.',
                'endereco' => 'Avenida Principal, 456',
                'bairro' => 'Vila Galvão',
                'cidade' => 'São Mateus',
                'cep' => '07000-000',
                'latitude' => -18.7300,
                'longitude' => -39.8800,
                'prioridade' => 'MEDIA',
                'status' => 'EM_ANDAMENTO'
            ],
            [
                'user_id' => $users[0]->id,
                'tipo' => 'LIMPEZA_PUBLICA',
                'titulo' => 'Lixo acumulado na Praça da Liberdade',
                'descricao' => 'A Praça da Liberdade está com muito lixo acumulado. Os cestos estão transbordando e há lixo espalhado pelo chão. Isso está atraindo animais e causando mau cheiro. A limpeza precisa ser feita com urgência.',
                'endereco' => 'Praça da Liberdade, s/n',
                'bairro' => 'Centro',
                'cidade' => 'São Mateus',
                'cep' => '07000-000',
                'latitude' => -18.7200,
                'longitude' => -39.8700,
                'prioridade' => 'ALTA',
                'status' => 'CONCLUIDA',
                'data_conclusao' => now()->subDays(2)
            ],
            [
                'user_id' => $users[0]->id,
                'tipo' => 'SEGURANCA_PUBLICA',
                'titulo' => 'Instalação de câmeras de segurança',
                'descricao' => 'Solicito a instalação de câmeras de segurança na Rua das Palmeiras. A região tem sido alvo de furtos e vandalismo. As câmeras ajudariam na identificação dos responsáveis e na prevenção de crimes.',
                'endereco' => 'Rua das Palmeiras, 789',
                'bairro' => 'Jardim Presidente',
                'cidade' => 'São Mateus',
                'cep' => '07000-000',
                'latitude' => -18.7400,
                'longitude' => -39.8900,
                'prioridade' => 'MEDIA',
                'status' => 'ABERTA'
            ]
        ];
        
        foreach ($solicitacoes as $solicitacao) {
            Solicitacao::create($solicitacao);
        }
        
        $this->command->info('Solicitações de exemplo criadas com sucesso!');
    }
}
