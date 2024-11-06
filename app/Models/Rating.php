<?php

// app/Models/Rating.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sale_detail_id', 'rating', 'comment', 'comment_status'];

    // Relación con el usuario que realizó la calificación
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el detalle de la venta
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class);
    }

    /**
     * Obtener el producto asociado con la calificación (a través de SaleDetail).
     */
    public function product()
    {
        return $this->belongsToThrough(Product::class, SaleDetail::class);
    }
}
