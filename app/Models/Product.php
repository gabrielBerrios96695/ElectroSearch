<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'purchase_price',

        'quantity',
        'image',
        'category_id',
        'status',
        'store_id',
    ];

    // Relación con el modelo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con el modelo Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Relación con el modelo SaleDetail
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
