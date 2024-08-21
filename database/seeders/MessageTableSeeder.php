<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MessageTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('messages')->insert([
            [
                'message' => 'Hola, ¿cómo estás?',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'message' => 'Tengo una pregunta con el reciclaje.',
                'user_id' => 2,
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => Carbon::now()->subDays(18),
            ],
            [
                'message' => 'El sistema está experimentando errores.',
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(16),
                'updated_at' => Carbon::now()->subDays(16),
            ],
            [
                'message' => 'Necesito ayuda con el proceso de compra.',
                'user_id' => 4,
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            [
                'message' => '¿Hay alguna oferta especial esta semana?',
                'user_id' => 5,
                'created_at' => Carbon::now()->subDays(12),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'message' => 'Mi pedido aún no ha llegado.',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'message' => '¿Cuándo se actualizarán los puntos?',
                'user_id' => 2,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'message' => 'Me gustaría recibir más información sobre su servicio.',
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            [
                'message' => '¿Cómo puedo reutilaz el papel periodico ?',
                'user_id' => 4,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'message' => 'El sitio web parece genial.',
                'user_id' => 5,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'message' => 'Tengo un problema con mi cuenta.',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'message' => '¿Pueden resolver el problema con el envío?',
                'user_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'message' => '¿Cómo puedo actualizar mis datos personales?',
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(22),
                'updated_at' => Carbon::now()->subDays(22),
            ],
            [
                'message' => '¿Cuál es el proceso para solicitar un reembolso?',
                'user_id' => 4,
                'created_at' => Carbon::now()->subDays(21),
                'updated_at' => Carbon::now()->subDays(21),
            ],
            [
                'message' => 'Estoy teniendo problemas con la aplicación móvil.',
                'user_id' => 5,
                'created_at' => Carbon::now()->subDays(19),
                'updated_at' => Carbon::now()->subDays(19),
            ],
            [
                'message' => 'Necesito asistencia técnica.',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(17),
                'updated_at' => Carbon::now()->subDays(17),
            ],
            [
                'message' => '¿Hay algún cambio en los horarios de atención?',
                'user_id' => 2,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'message' => 'Quiero cancelar mi suscripción.',
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(13),
                'updated_at' => Carbon::now()->subDays(13),
            ],
            [
                'message' => 'Me gustaría saber más sobre sus políticas de privacidad.',
                'user_id' => 4,
                'created_at' => Carbon::now()->subDays(11),
                'updated_at' => Carbon::now()->subDays(11),
            ],
            [
                'message' => 'El sitio web no carga en mi navegador.',
                'user_id' => 5,
                'created_at' => Carbon::now()->subDays(9),
                'updated_at' => Carbon::now()->subDays(9),
            ],
            [
                'message' => 'Tengo una pregunta sobre la garantía del producto.',
                'user_id' => 1,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'message' => '¿Puedo obtener un soporte más personalizado?',
                'user_id' => 2,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'message' => 'Quisiera recibir una actualización sobre mi caso.',
                'user_id' => 3,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'message' => 'El proceso de devolución es confuso.',
                'user_id' => 4,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'message' => 'Necesito asistencia inmediata.',
                'user_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
