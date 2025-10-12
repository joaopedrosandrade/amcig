<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AdminSeeder::class);
        
        // Descomente a linha abaixo para criar usuários de exemplo com permissões
        // $this->call(AdminPermissionsSeeder::class);
    }
}
