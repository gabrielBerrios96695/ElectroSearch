<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        // Seleccionar algunos usuarios
        $users = User::limit(5)->get();
        $products = Product::limit(10)->get();

        foreach ($users as $user) {
            // Crear una venta
            $sale = Sale::create([
                'user_id' => $user->id, // Usuario que realiza la venta
                'customer_id' => $users->except($user->id)->random()->id, // Usuario que recibe la venta (cliente)
                'total_amount' => 0, // Monto total inicial
                'status' => 'completed', // Estado de la venta
            ]);

            $totalAmount = 0;

            // Agregar detalles de venta
            foreach ($products->random(3) as $product) {
                $quantity = rand(1, 5);
                $price = $product->price;
                $total = $price * $quantity;
                
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ]);

                // Sumar el total a la venta
                $totalAmount += $total;
            }

            // Actualizar el monto total de la venta
            $sale->update(['total_amount' => $totalAmount]);
        }
    }
}
