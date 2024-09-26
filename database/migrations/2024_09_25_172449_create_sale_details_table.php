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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id(); // ID del detalle de la venta
            $table->foreignId('sale_id')->constrained()->onDelete('cascade'); // ID de la venta
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // ID del producto
            $table->integer('quantity'); // Cantidad del producto
            $table->decimal('price', 10, 2); // Precio del producto
            $table->decimal('total', 10, 2); // Total para este producto (quantity * price)
            $table->timestamps(); // Timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
