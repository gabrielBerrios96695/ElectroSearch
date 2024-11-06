<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 14 Pro',
                'description' => 'Smartphone de última generación con cámara avanzada.',
                'image' => 'products/iphone14pro.png',
                'quantity' => 15,
                'price' => 1200.00,
                'category_id' => 1, // Smartphones
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MacBook Pro 16"',
                'description' => 'Laptop profesional con gran rendimiento.',
                'image' => 'products/macbookpro.png',
                'quantity' => 30,
                'price' => 2500.00,
                'category_id' => 2, // Laptops
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy Tab S8',
                'description' => 'Tablet de alto rendimiento con pantalla AMOLED.',
                'image' => 'products/galaxytab.png',
                'quantity' => 25,
                'price' => 600.00,
                'category_id' => 3, // Tablets
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Auriculares con cancelación de ruido avanzada.',
                'image' => 'products/sonyxm5.png',
                'quantity' => 10,
                'price' => 350.00,
                'category_id' => 4, // Auriculares
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apple Watch Series 8',
                'description' => 'Reloj inteligente con funciones avanzadas de salud.',
                'image' => 'products/applewatch8.png',
                'quantity' => 50,
                'price' => 400.00,
                'category_id' => 5, // Smartwatches
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Canon EOS 90D',
                'description' => 'Cámara digital DSLR con resolución 32.5 MP.',
                'image' => 'products/canon90d.png',
                'quantity' => 20,
                'price' => 1300.00,
                'category_id' => 6, // Cámaras Digitales
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anker PowerCore 10000',
                'description' => 'Batería portátil compacta de alta capacidad.',
                'image' => 'products/ankerpowercore.png',
                'quantity' => 45,
                'price' => 25.00,
                'category_id' => 7, // Accesorios Electrónicos
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PlayStation 5',
                'description' => 'Consola de videojuegos de última generación.',
                'image' => 'products/ps5.png',
                'quantity' => 12,
                'price' => 500.00,
                'category_id' => 8, // Videojuegos y Consolas
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Google Pixel 8',
                'description' => 'Smartphone con cámara avanzada y Google Tensor.',
                'image' => 'products/googlepixel8.png',
                'quantity' => 20,
                'price' => 850.00,
                'category_id' => 1, // Smartphones
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dell XPS 13',
                'description' => 'Laptop ultradelgada con pantalla 4K y gran rendimiento.',
                'image' => 'products/dellxps13.png',
                'quantity' => 18,
                'price' => 1500.00,
                'category_id' => 2, // Laptops
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Microsoft Surface Pro 9',
                'description' => 'Tablet híbrida con teclado y pantalla táctil de alta resolución.',
                'image' => 'products/surfacepro9.png',
                'quantity' => 10,
                'price' => 999.00,
                'category_id' => 3, // Tablets
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bose QuietComfort 45',
                'description' => 'Auriculares con cancelación de ruido de alta calidad.',
                'image' => 'products/boseqc45.png',
                'quantity' => 25,
                'price' => 329.00,
                'category_id' => 4, // Auriculares
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Garmin Forerunner 945',
                'description' => 'Reloj inteligente para deportistas con GPS y monitor de frecuencia cardíaca.',
                'image' => 'products/garminforerunner.png',
                'quantity' => 30,
                'price' => 600.00,
                'category_id' => 5, // Smartwatches
                'status' => 1,
                'userId' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
