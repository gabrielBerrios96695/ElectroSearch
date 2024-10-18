<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;
use App\Services\PdfService;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Cargar ventas con detalles
        $sales = Sale::with('user', 'customer', 'details.product')->get();
        return view('livewire.sales.index', compact('sales'));
    }

    public function show($id)
    {
        // Mostrar detalles de la venta
        $sale = Sale::with('details.product')->findOrFail($id);
        return view('livewire.sales.show', compact('sale'));
    }

    public function create()
    {
        // Obtener productos y clientes para la vista de creación
        $products = Product::all();
        $customers = User::where('role', 3)->get(); // Clientes tienen el role 3

        return view('livewire/sales.create', compact('products', 'customers'));
    }



    public function store(Request $request, PdfService $pdfService)
    {
        // Validación de los datos de la venta
        $request->validate([
            'customer_id' => 'required|exists:users,id',
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
                'customer_id' => $request->input('customer_id'),
                'total_amount' => 0,
                'status' => 'completed',
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
            return redirect()->route('sales.index')->with('success', 'Venta realizada con exito.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Hubo un error al crear la venta. ' . $e->getMessage()])->withInput();
        }
    }
    


    // Ejemplo de controlador para la vista 'edit'
    public function edit($id)
    {
        $sale = Sale::with('saleDetails.product')->find($id);
        if (!$sale) {
            return redirect()->route('sales.index')->with('error', 'Venta no encontrada.');
        }

        // Obtener productos y clientes
        $products = Product::all();
        $customers = \App\Models\User::where('role', 3)->get(); // Obtener clientes (usuarios con role 3)

        return view('livewire/sales.edit', compact('sale', 'products', 'customers'));
    }

public function update(Request $request, $id)
{
    // Iniciar una transacción
    DB::beginTransaction();

    try {
        $sale = Sale::find($id);
        if (!$sale) {
            return redirect()->route('sales.index')->with('error', 'Venta no encontrada.');
        }

        // Validar la solicitud
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.id' => 'required|exists:products,id',
        ]);

        // Obtener los detalles anteriores de la venta
        $previousDetails = $sale->saleDetails()->get();

        // Restaurar el stock de los productos anteriores
        foreach ($previousDetails as $detail) {
            $product = Product::find($detail->product_id);
            $product->increment('quantity', $detail->quantity);
        }

        // Actualizar información de la venta
        $sale->customer_id = $validated['customer_id'];
        $sale->save();

        // Eliminar los detalles de venta anteriores
        $sale->saleDetails()->delete();

        // Inicializar el monto total
        $totalAmount = 0;

        // Crear nuevos detalles de venta y actualizar stock
        foreach ($validated['products'] as $product) {
            $productId = $product['id'];
            $quantity = $product['quantity'];
            $productModel = Product::findOrFail($productId);
            $price = $productModel->price;
            $total = $price * $quantity;

            // Crear detalle de venta
            SaleDetail::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $total,
            ]);

            // Acumular el monto total de la venta
            $totalAmount += $total;

            // Reducir el stock del producto
            $productModel->decrement('quantity', $quantity);
        }

        // Actualizar el total de la venta
        $sale->update(['total_amount' => $totalAmount]);

        // Confirmar la transacción si todo ha ido bien
        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Venta actualizada con éxito.');
    } catch (\Exception $e) {
        // Si ocurre un error, revertir todos los cambios
        DB::rollBack();
        
        // Puedes agregar más lógica de manejo de errores aquí si lo deseas
        return redirect()->route('sales.index')->with('error', 'Ocurrió un error al actualizar la venta.');
    }
}


public function destroy($id)
{
    $sale = Sale::find($id);
    if (!$sale) {
        return redirect()->route('sales.index')->with('error', 'Venta no encontrada.');
    }

    // Iniciar una transacción
    DB::beginTransaction();
    try {
        // Recuperar detalles de la venta para restaurar el stock
        foreach ($sale->saleDetails as $detail) {
            $product = Product::find($detail->product_id);
            $product->increment('quantity', $detail->quantity);
        }

        // Eliminar los detalles de la venta
        $sale->saleDetails()->delete();

        // Eliminar la venta
        $sale->delete();

        // Confirmar la transacción
        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Venta eliminada con éxito.');
    } catch (\Exception $e) {
        // Revertir la transacción en caso de error
        DB::rollBack();
        return redirect()->route('sales.index')->with('error', 'Hubo un error al eliminar la venta. ' . $e->getMessage());
    }
}

}


