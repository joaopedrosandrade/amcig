<?php

use Illuminate\Database\Seeder;
use App\Admin;
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
            ]
        );

        if (isset($this->command)) {
            $this->command->info('Administrador padrão semeado: admin@admin.com / 159753');
        }
    }
}


