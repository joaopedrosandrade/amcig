<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\DB;

class GerarMatriculas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matriculas:gerar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera matrículas para usuários que ainda não possuem';

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
        $this->info('Iniciando geração de matrículas...');
        
        // Busca usuários sem matrícula ou com matrícula antiga (menos de 11 dígitos)
        $usuarios = User::where(function($query) {
            $query->whereNull('matricula')
                  ->orWhereRaw('LENGTH(matricula) < 11');
        })->get();
        
        if ($usuarios->isEmpty()) {
            $this->info('Todos os usuários já possuem matrícula!');
            return 0;
        }
        
        $this->info("Encontrados {$usuarios->count()} usuários sem matrícula.");
        
        $bar = $this->output->createProgressBar($usuarios->count());
        $bar->start();
        
        foreach ($usuarios as $usuario) {
            // Gera a matrícula
            $matricula = $usuario->gerarMatricula();
            
            // Atualiza apenas o campo matricula
            DB::table('users')
                ->where('id', $usuario->id)
                ->update(['matricula' => $matricula]);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->line('');
        $this->info('Matrículas geradas com sucesso!');
        
        return 0;
    }
}
