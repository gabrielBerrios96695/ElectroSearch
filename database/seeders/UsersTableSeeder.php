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
                'name' => 'Juan Pablo',
                'last_name' => 'Pérez',
                'second_last_name' => null, // Opcional
                'email' => 'juan.perez@example.com',
                'role' => '1', // Administrador
                'userid' => 1,
                'status' => 1,
                'password' => Hash::make('12345678'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '77424842', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'María',
                'last_name' => 'López',
                'second_last_name' => 'Gómez', // Ejemplo con segundo apellido
                'email' => 'maria.lopez@example.com',
                'role' => '2', // Vendedor
                'userid' => 2,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '775987654', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carlos',
                'last_name' => 'García',
                'second_last_name' => null, // Opcional
                'email' => 'carlos.garcia@example.com',
                'role' => '3', // Cliente
                'userid' => 3,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '763456789', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ana',
                'last_name' => 'Torres',
                'second_last_name' => null, // Opcional
                'email' => 'ana.torres@example.com',
                'role' => '2', // Vendedor
                'userid' => 4,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '773234567', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Luis',
                'last_name' => 'Fernández',
                'second_last_name' => 'Pérez', // Ejemplo con segundo apellido
                'email' => 'luis.fernandez@example.com',
                'role' => '3', // Cliente
                'userid' => 5,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '767234567', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laura',
                'last_name' => 'Martínez',
                'second_last_name' => null, // Opcional
                'email' => 'laura.martinez@example.com',
                'role' => '2', // Vendedor
                'userid' => 6,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '773456789', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'José',
                'last_name' => 'Rodríguez',
                'second_last_name' => 'Hernández', // Ejemplo con segundo apellido
                'email' => 'jose.rodriguez@example.com',
                'role' => '3', // Cliente
                'userid' => 7,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '766123456', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sofía',
                'last_name' => 'González',
                'second_last_name' => null, // Opcional
                'email' => 'sofia.gonzalez@example.com',
                'role' => '2', // Vendedor
                'userid' => 8,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '775678901', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andrés',
                'last_name' => 'Herrera',
                'second_last_name' => 'García', // Ejemplo con segundo apellido
                'email' => 'andres.herrera@example.com',
                'role' => '3', // Cliente
                'userid' => 9,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '772345678', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Valentina',
                'last_name' => 'Ruiz',
                'second_last_name' => null, // Opcional
                'email' => 'valentina.ruiz@example.com',
                'role' => '2', // Vendedor
                'userid' => 10,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate' => true,
                'email_verified_at' => now(),
                'phone' => '766987654', // Teléfono con formato correcto
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
                
    }
}
