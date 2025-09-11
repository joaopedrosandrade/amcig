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
                'cidade' => 'Guarulhos',
                'cep' => '07000-000',
                'latitude' => -23.4538,
                'longitude' => -46.5331,
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
                'cidade' => 'Guarulhos',
                'cep' => '07000-000',
                'latitude' => -23.4500,
                'longitude' => -46.5300,
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
                'cidade' => 'Guarulhos',
                'cep' => '07000-000',
                'latitude' => -23.4600,
                'longitude' => -46.5400,
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
                'cidade' => 'Guarulhos',
                'cep' => '07000-000',
                'latitude' => -23.4550,
                'longitude' => -46.5350,
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
                'cidade' => 'Guarulhos',
                'cep' => '07000-000',
                'latitude' => -23.4700,
                'longitude' => -46.5500,
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
