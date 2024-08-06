<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        //Tiendas
        $storeCount = Store::count();
        $storeCount1= Store::where('status', 0)->count();

        //Usuarios
        $sellersCount = User::where('role', 'vendedor')->count();
        $clientsCount = User::where('role', 'cliente')->count();

        return view('dashboard', compact('storeCount','storeCount1', 'sellersCount', 'clientsCount'));
    }

}

