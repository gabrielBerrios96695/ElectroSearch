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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // ID de la venta
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que realiza la venta
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade'); // Cliente que recibe la venta
            $table->decimal('total_amount', 10, 2); // Monto total de la venta
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); // Estado de la venta
            $table->timestamps(); // Timestamps
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
