<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'descripcion', 
        'precio', 
        'imagen', 
        'categoria',
        'store_id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
