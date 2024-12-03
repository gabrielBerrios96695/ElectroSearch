<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Contar vendedores y Usuarios
        $sellersCount = User::where('role', '2')->count();
        $clientsCount = User::where('role', '3')->count();

        // Top de vendedores
        $topSellers = DB::table('sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->select(
                'users.id as seller_id',
                'users.name as seller_name',
                DB::raw('SUM(sales.total_amount) as total_sales')
            )
            ->where('sales.status', 'completed')
            ->whereMonth('sales.created_at', now()->month)
            ->whereYear('sales.created_at', now()->year)
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Productos más vendidos
        $topProducts = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.status', 'completed')
            ->whereMonth('sales.created_at', now()->month)
            ->whereYear('sales.created_at', now()->year)
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                DB::raw('SUM(sale_details.quantity) as total_quantity_sold'),
                DB::raw('SUM(sale_details.total) as total_sales')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity_sold', 'desc')
            ->take(5)
            ->get();

        // Productos con bajo stock
        $lowStockProducts = DB::table('products')
            ->select('id', 'name', 'quantity')
            ->where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->get();

            $monthlyEarnings = DB::table('sales')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total_amount) as earnings'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month', 'asc')
            ->get();

            $months = $monthlyEarnings->pluck('month');
            $earnings = $monthlyEarnings->pluck('earnings');
        return view('dashboard', compact(
            'sellersCount',
            'clientsCount',
            'monthlyEarnings',
            'months',
            'earnings',
            'topSellers',
            'topProducts',
            'lowStockProducts',
            'user'
        ));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validar que la contraseña actual es correcta
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        if (Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['La nueva contraseña no puede ser igual a la contraseña actual.'],
            ]);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->password);
        $user->passwordUpdate = false; // Marca como actualizado
        $user->save();

        return redirect()->route('dashboard')->with('status', 'Contraseña actualizada con éxito, muchas gracias');
    }
}
