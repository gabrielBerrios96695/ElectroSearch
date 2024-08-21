<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'userid',
        'status',
        'opening_time',
        'closing_time',
        'type_point_id',
    ];

    public function typePoint()
    {
        return $this->belongsTo(TypePoint::class, 'type_point_id');
    }
}
