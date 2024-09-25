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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Changed 'nombre' to 'name'
            $table->string('description'); // Changed 'descripcion' to 'description'
            $table->decimal('price', 10, 2); // Changed 'precio' to 'price'
            $table->string('image'); // Changed 'imagen' to 'image'
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Changed 'categoria' to 'category_id'
            $table->tinyInteger('status')->default(1);
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
