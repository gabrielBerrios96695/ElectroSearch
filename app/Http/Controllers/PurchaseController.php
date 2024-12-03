<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;
use App\Services\PdfService;
use Carbon\Carbon;  // Asegúrate de poner esta línea aquí, antes de la clase

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario autenticado tiene rol 3 (Cliente)
        if (auth()->user()->role == 3) {
            // Filtrar ventas donde el customer_id sea igual al ID del usuario autenticado
            $sales = Sale::with('user', 'customer', 'details.product')
                        ->where('type_of_sale', 0) // Filtra las ventas con type_of_sale igual a 0
                        ->where('customer_id', auth()->id()) // Solo ventas del cliente autenticado
                        ->get();
        } else {
            // Si no es un cliente, mostrar todas las ventas con type_of_sale igual a 0
            $sales = Sale::with('user', 'customer', 'details.product')
                        ->where('type_of_sale', 0) // Filtra las ventas con type_of_sale igual a 0
                        ->get();
        }
        
        return view('livewire.purchases.index', compact('sales'));
    }


    public function create()
{
    // Obtener solo productos con status 1
    $products = Product::where('status', 1)->get();
    $customers = User::where('role', 3)->get(); // Usuarios tienen el role 3
    $categories = Category::all(); // Obtener todas las categorías

    return view('livewire/purchases.create', compact('products', 'customers', 'categories'));
}


    public function store(Request $request, PdfService $pdfService)
{
    // Validación de los datos de la venta
    $request->validate([
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);
    
    // Iniciar una transacción
    DB::beginTransaction();
    try {
        // Crear la venta
        $sale = Sale::create([
            'user_id' => auth()->id(),
            'customer_id' => auth()->id(),
            'total_amount' => 0,
            'type_of_sale'=> 0,
            'status' => 'pending',
        ]);
    
        $totalAmount = 0;
    
        foreach ($request->input('products') as $product) {
            $productId = $product['id'];
            $quantity = $product['quantity'];
            $productModel = Product::findOrFail($productId);
    
            if ($productModel->quantity < $quantity) {
                throw new \Exception('Stock insuficiente para el producto: ' . $productModel->name);
            }
    
            $price = $productModel->price;
            $total = $price * $quantity;
    
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
            ]);
    
            $totalAmount += $total;
            $productModel->decrement('quantity', $quantity);
        }
    
        $sale->update(['total_amount' => $totalAmount]);
    
        DB::commit();

        // Mensaje a mostrar al usuario
        $message = 'Venta realizada con éxito. La venta estará como pendiente hasta que un administrador o empleado coordine el pago con usted. Muchas gracias';
        
        return redirect()->route('purchases.index')->with('success', $message);
    
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Hubo un error al crear la venta. ' . $e->getMessage()])->withInput();
    }
}


    // Método para cancelar la venta
    public function cancel($id)
    {
        // Obtener la venta
        $sale = Sale::with('details.product')->findOrFail($id);

        // Verificar si han pasado más de 24 horas desde la creación de la venta
        $hoursDifference = Carbon::now()->diffInHours($sale->created_at);

        if ($hoursDifference > 24) {
            // Si han pasado más de 24 horas, no se puede cancelar
            return back()->withErrors(['error' => 'No puedes cancelar esta Pedido porque han pasado más de 24 horas desde su realización.']);
        }

        // Comenzar una transacción
        DB::beginTransaction();

        try {
            // Cambiar el estado de la venta a 'cancelled'
            $sale->update(['status' => 'cancelled']);

            // Restaurar el stock de los productos
            foreach ($sale->details as $detail) {
                $product = $detail->product;
                $product->increment('quantity', $detail->quantity); // Aumentar el stock
            }

            // Commit de la transacción
            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Pedido cancelada y stock restaurado.');

        } catch (\Exception $e) {
            // Rollback en caso de error
            DB::rollBack();
            return back()->withErrors(['error' => 'Hubo un error al cancelar la Pedido. ' . $e->getMessage()]);
        }
    }
}
