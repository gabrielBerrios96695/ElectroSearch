<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePoint extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'name',
        'description',
        'status',
        'userid'
    ];

    public function collectionPoints()
    {
        return $this->hasMany(CollectionPoint::class, 'type_point_id');
    }
}
