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
                'first_surname' => 'Admin',
                'second_surname' => 'User',
                'email' => 'admin@admin.com',
                'phone' => '1234567890',
                'role' => 1,
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
                'first_surname' => 'Vendedor',
                'second_surname' => 'Uno',
                'email' => 'vendedor1@example.com',
                'phone' => '2345678901',
                'role' => 2,
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
                'first_surname' => 'Cliente',
                'second_surname' => 'Uno',
                'email' => 'cliente1@example.com',
                'phone' => '3456789012',
                'role' => 3,
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
                'first_surname' => 'Vendedor',
                'second_surname' => 'Dos',
                'email' => 'vendedor2@example.com',
                'phone' => '4567890123',
                'role' => 2,
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
                'first_surname' => 'Cliente',
                'second_surname' => 'Dos',
                'email' => 'cliente2@example.com',
                'phone' => '5678901234',
                'role' => 3,
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
