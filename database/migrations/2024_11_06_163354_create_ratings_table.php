<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que realiza la calificación
            $table->foreignId('sale_detail_id')->constrained()->onDelete('cascade'); // Relación con el detalle de la venta
            $table->integer('rating')->nullable(); // Calificación entre 1 y 5
            $table->text('comment')->nullable(); // Comentario opcional
            $table->integer('comment_status')->default(1); // Estado del comentario (0: bloqueado, 1: pendiente, 3: aprobado)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
