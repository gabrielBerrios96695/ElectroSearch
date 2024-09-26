<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'latitude', 
        'longitude', 
        'status',
        
    ];

    public function scopeEnabled($query)
    {
        return $query->where('status', 1);
    }

     // Store.php
    public function products()
    {
        return $this->hasMany(Product::class);
    }

// Product.php
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

}


