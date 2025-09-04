<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class VerificarMatriculas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matriculas:verificar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica as matrículas geradas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Verificando matrículas geradas...');
        
        $usuarios = User::select('id', 'name', 'cpf', 'matricula', 'created_at')->get();
        
        if ($usuarios->isEmpty()) {
            $this->info('Nenhum usuário encontrado!');
            return 0;
        }
        
        $this->table(
            ['ID', 'Nome', 'CPF', 'Matrícula', 'Tamanho'],
            $usuarios->map(function($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->cpf,
                    $user->matricula,
                    strlen($user->matricula)
                ];
            })
        );
        
        return 0;
    }
}
