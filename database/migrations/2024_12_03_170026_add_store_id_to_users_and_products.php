<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    // Agregar store_id a la tabla users
    Schema::table('users', function (Blueprint $table) {
        $table->tinyInteger('store_id')->nullable()->after('id');
    });

    // Agregar store_id a la tabla products
    Schema::table('products', function (Blueprint $table) {
        $table->tinyInteger('store_id')->nullable()->after('id');
    });
}

public function down()
{
    // Eliminar store_id de la tabla users
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('store_id');
    });

    // Eliminar store_id de la tabla products
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('store_id');
    });
}

};
