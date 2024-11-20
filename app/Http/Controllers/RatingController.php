<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Rating;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
{
    // Obtener todos los productos con sus calificaciones
    $productsWithAvgRatings = Product::with(['ratings' => function($query) {
        $query->whereNotNull('rating');  // Filtra solo las calificaciones no nulas
    }])->get()->map(function ($product) {
        // Calcular el promedio de calificación
        $ratings = $product->ratings;
        $averageRating = $ratings->avg('rating'); // Promedio de calificación
        $ratingsCount = $ratings->count(); // Número de calificaciones

        return [
            'product' => $product,
            'averageRating' => round($averageRating, 1), // Redondea a 1 decimal
            'ratings' => $ratings,
            'ratingsCount' => $ratingsCount // Agregar el conteo de calificaciones
        ];
    });
    
    return view('ratings.index', compact('productsWithAvgRatings'));
}



public function create()
{
    // Obtener todas las ventas del usuario logueado con estado 'completed' y que no tienen productos calificados
    $sales = Sale::where('customer_id', auth()->id())
        ->where('status', 'completed') // Filtrar solo ventas con estado 'completed'
        ->whereDoesntHave('saleDetails.rating') // Excluir ventas que ya tienen productos calificados
        ->get();

    return view('ratings.create', compact('sales'));
}



    public function store(Request $request)
{
    // Validación de los campos
    $request->validate([
        'sale_id' => 'required|exists:sales,id',
        'comments' => 'nullable|array', // Comentarios opcionales
        'ratings' => 'nullable|array',  // Calificaciones opcionales
    ]);

    // Asegurarse de que 'ratings' no sea nulo y tiene elementos
    if ($request->has('ratings') && is_array($request->ratings)) {
        // Guardar los ratings solo para productos con calificación
        foreach ($request->ratings as $saleDetailId => $rating) {
            // Si no se da una calificación (null o vacío), no guardar la calificación
            if ($rating !== null && $rating !== '') {
                Rating::create([
                    'sale_detail_id' => $saleDetailId,
                    'rating' => $rating,
                    'comment' => $request->comments[$saleDetailId] ?? null, // Comentario opcional
                    'comment_status' => 1, // Pendiente
                    'user_id' => auth()->id(), // Usuario que realiza la calificación
                ]);
            }
        }
    }

    return redirect()->route('ratings.index')->with('success', 'Calificación realizada exitosamente.');
}
public function approveComments()
{
    // Verificar si el usuario tiene el rol de Administrador (role 1)
    if (auth()->user()->role != 1) {
        abort(403, 'Acción no autorizada'); // Si no es administrador, abortar con un error 403
    }

    // Obtener los comentarios pendientes o bloqueados para que el administrador los pueda aprobar/denegar
    $ratings = Rating::with(['saleDetail.product', 'user'])
        ->whereIn('comment_status', [1, 2]) // Pendientes o Bloqueados
        ->get();

    return view('ratings.approve', compact('ratings'));
}

public function approve($ratingId)
{
    $rating = Rating::findOrFail($ratingId);

    // Cambiar el estado a Aprobado (3)
    $rating->update(['comment_status' => 3]);

    return redirect()->route('ratings.approve')->with('success', 'Comentario aprobado exitosamente.');
}

public function reject($ratingId)
{
    $rating = Rating::findOrFail($ratingId);

    // Cambiar el estado a Bloqueado (2)
    $rating->update(['comment_status' => 2]);

    return redirect()->route('ratings.approve')->with('success', 'Comentario rechazado exitosamente.');
}


// Bloquear un comentario
public function block(Rating $rating)
{
    // Verifica si el usuario tiene el rol 1 (administrador)
    if (auth()->user()->role != 1) {
        abort(403, 'Acción no autorizada');
    }

    // Cambiar el estado del comentario a bloqueado
    $rating->update(['comment_status' => 2]); // 2: Bloqueado

    return redirect()->route('ratings.approve')->with('success', 'Comentario bloqueado');
}

}
