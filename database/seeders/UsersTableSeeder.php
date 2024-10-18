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
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@example.com',
                'role' => '1', // Administrador
                'userid' => 1,
                'status' => 1,
                'password' => Hash::make('12345678'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'María López',
                'email' => 'maria.lopez@example.com',
                'role' => '2', // Vendedor
                'userid' => 2,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carlos García',
                'email' => 'carlos.garcia@example.com',
                'role' => '3', // Cliente
                'userid' => 3,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ana Torres',
                'email' => 'ana.torres@example.com',
                'role' => '2', // Vendedor
                'userid' => 4,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luis Fernández',
                'email' => 'luis.fernandez@example.com',
                'role' => '3', // Cliente
                'userid' => 5,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laura Martínez',
                'email' => 'laura.martinez@example.com',
                'role' => '2', // Vendedor
                'userid' => 6,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'José Rodríguez',
                'email' => 'jose.rodriguez@example.com',
                'role' => '3', // Cliente
                'userid' => 7,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sofía González',
                'email' => 'sofia.gonzalez@example.com',
                'role' => '2', // Vendedor
                'userid' => 8,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andrés Herrera',
                'email' => 'andres.herrera@example.com',
                'role' => '3', // Cliente
                'userid' => 9,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Valentina Ruiz',
                'email' => 'valentina.ruiz@example.com',
                'role' => '2', // Vendedor
                'userid' => 10,
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
