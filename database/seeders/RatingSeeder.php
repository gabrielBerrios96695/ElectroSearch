<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\SaleDetail;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        // Obtén algunos usuarios y detalles de ventas existentes
        $users = User::where('role', 3)->get(); // Asegurarse de que sean usuarios clientes
        $saleDetails = SaleDetail::all(); // Obtener todos los detalles de venta

        // Si no hay detalles de ventas o usuarios, no insertes nada
        if ($users->isEmpty() || $saleDetails->isEmpty()) {
            return;
        }

        // Crear calificaciones de ejemplo
        $ratings = [
            [
                'user_id' => $users->random()->id, // Selecciona un usuario aleatorio
                'sale_detail_id' => $saleDetails->random()->id, // Selecciona un detalle de venta aleatorio
                'rating' => rand(1, 5), // Calificación entre 1 y 5
                'comment' => 'Excelente producto, muy recomendable.',
                'comment_status' => 3, // Comentario aprobado
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users->random()->id,
                'sale_detail_id' => $saleDetails->random()->id,
                'rating' => rand(1, 5),
                'comment' => 'Buena calidad, aunque el envío tardó un poco.',
                'comment_status' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users->random()->id,
                'sale_detail_id' => $saleDetails->random()->id,
                'rating' => rand(1, 5),
                'comment' => 'No es lo que esperaba, pero el producto funciona bien.',
                'comment_status' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users->random()->id,
                'sale_detail_id' => $saleDetails->random()->id,
                'rating' => rand(1, 5),
                'comment' => 'Producto defectuoso, no estoy satisfecho.',
                'comment_status' => 2, // Comentario pendiente o bloqueado
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users->random()->id,
                'sale_detail_id' => $saleDetails->random()->id,
                'rating' => rand(1, 5),
                'comment' => 'Muy bueno, vale la pena cada centavo.',
                'comment_status' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insertar las calificaciones en la tabla
        foreach ($ratings as $rating) {
            DB::table('ratings')->insert($rating);
        }
    }
}
