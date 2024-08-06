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
}


