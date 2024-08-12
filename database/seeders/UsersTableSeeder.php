<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Ejecutar las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {

        // Crear usuarios de ejemplo
        DB::table('users')->insert([
            [
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'role' => '1',
                'userid' => 1,
                'status' => 1,
                'password' => Hash::make('12345678'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendedor Uno',
                'email' => 'vendedor1@example.com',
                'role' => '2',
                'userid' => 2,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cliente Uno',
                'email' => 'cliente1@example.com',
                'role' => '3',
                'userid' => 3,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendedor Dos',
                'email' => 'vendedor2@example.com',
                'role' => '2',
                'userid' => 4,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cliente Dos',
                'email' => 'cliente2@example.com',
                'role' => '3',
                'userid' => 5,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
