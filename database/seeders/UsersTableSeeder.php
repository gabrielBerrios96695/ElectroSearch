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
        // Limpiar la tabla
        DB::table('users')->truncate();

        // Crear usuarios de ejemplo
        DB::table('users')->insert([
            [
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'role' => 'admin',
                'userid' => 1,
                'status' => 1,
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendedor Uno',
                'email' => 'vendedor1@example.com',
                'role' => 'vendedor',
                'userid' => 2,
                'status' => 1,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cliente Uno',
                'email' => 'cliente1@example.com',
                'role' => 'cliente',
                'userid' => 3,
                'status' => 1,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vendedor Dos',
                'email' => 'vendedor2@example.com',
                'role' => 'vendedor',
                'userid' => 4,
                'status' => 1,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cliente Dos',
                'email' => 'cliente2@example.com',
                'role' => 'cliente',
                'userid' => 5,
                'status' => 1,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
