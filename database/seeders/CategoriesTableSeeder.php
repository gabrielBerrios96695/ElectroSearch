<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Smartphones', 'description' => 'Teléfonos inteligentes de última generación.', 'status' => 1],
            ['name' => 'Laptops', 'description' => 'Computadoras portátiles de alto rendimiento.', 'status' => 1],
            ['name' => 'Tablets', 'description' => 'Tabletas de diferentes tamaños y especificaciones.', 'status' => 1],
            ['name' => 'Auriculares', 'description' => 'Auriculares con tecnología Bluetooth y cancelación de ruido.', 'status' => 1],
            ['name' => 'Smartwatches', 'description' => 'Relojes inteligentes con múltiples funciones de monitoreo.', 'status' => 1],
            ['name' => 'Cámaras Digitales', 'description' => 'Cámaras de alta resolución para fotografía y video.', 'status' => 1],
            ['name' => 'Accesorios Electrónicos', 'description' => 'Accesorios como cargadores, cables, fundas, y más.', 'status' => 1],
            ['name' => 'Videojuegos y Consolas', 'description' => 'Consolas y videojuegos para todas las edades.', 'status' => 1],
        ];

        // Insertar categorías en la tabla
        DB::table('categories')->insert($categories);
}
}
