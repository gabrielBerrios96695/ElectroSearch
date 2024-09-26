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
            ['name' => 'Electrónica', 'description' => 'Dispositivos y gadgets', 'status' => 1],
            ['name' => 'Moda', 'description' => 'Ropa y accesorios', 'status' => 1],
            ['name' => 'Electrodomésticos', 'description' => 'Equipos para el hogar', 'status' => 1],
            ['name' => 'Libros', 'description' => 'Literatura impresa y digital', 'status' => 1],
            ['name' => 'Deportes', 'description' => 'Artículos y ropa deportiva', 'status' => 1],
            ['name' => 'Belleza', 'description' => 'Productos de belleza y cosméticos', 'status' => 1],
            ['name' => 'Automotriz', 'description' => 'Vehículos y accesorios', 'status' => 1],
        ];

        // Insertar categorías en la tabla
        DB::table('categories')->insert($categories);
    }
}
