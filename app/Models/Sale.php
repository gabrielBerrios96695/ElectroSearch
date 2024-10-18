<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'total_amount', 'status'];

    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, SaleDetail::class, 'sale_id', 'id', 'id', 'product_id');
    }
    
}
