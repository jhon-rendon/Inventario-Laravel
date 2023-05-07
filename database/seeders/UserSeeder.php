<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'nombres'   => 'Admin',
            'apellidos' => 'Admin',
            'email'     => 'admin@admin.com',
            'documento' => '1234',
            'password'  => bcrypt('password'),
        ]);

        $user->assignRole('Admin');

        $user = User::create([
            'nombres'   => 'Tecnico',
            'apellidos' => 'Tecnico',
            'email'     => 'tecnico@tecnico.com',
            'documento' => '55555',
            'password'  => bcrypt('password'),
        ]);

        $user->assignRole('tecnico');
    }
}
