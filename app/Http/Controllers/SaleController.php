<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\store;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\UserRegistered;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleDetail;
use App\Services\PdfService;


class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Cargar ventas con detalles, pero solo aquellas cuyo type_of_sale sea igual a 1
        $sales = Sale::with('user', 'customer', 'details.product')
                    ->where('type_of_sale', 1) // Filtra las ventas con type_of_sale igual a 1
                    ->get();
                    
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
    // Obtener el usuario autenticado
    $user = auth()->user();

    // Si el usuario tiene el role 2 (vendedor)
    if ($user->role == 2) {
        // Obtener los productos que pertenecen a la tienda del vendedor
        $products = Product::where('status', 1)
                           ->where('store_id', $user->store_id) // Filtrar por store_id del vendedor
                           ->get();
    }
    // Si el usuario tiene el role 1 (administrador)
    elseif ($user->role == 1) {
        // Obtener los productos de las tiendas registradas por el administrador
        $products = Product::where('status', 1)
                           ->whereIn('store_id', Store::where('admin_id', $user->id)->pluck('id'))
                           ->get();
    } else {
        // Obtener todos los productos con status 1 para otros roles (como clientes)
        $products = Product::where('status', 1)->get();
    }

    // Obtener clientes con role 3 (clientes)
    $customers = User::where('role', 3)->get();

    // Obtener todas las categorías
    $categories = Category::all();

    // Retornar la vista con los productos filtrados
    return view('livewire/sales.create', compact('products', 'customers', 'categories'));
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

        // Obtener productos y Usuarios
        $products = Product::all();
        $customers = \App\Models\User::where('role', 3)->get(); // Obtener Usuarios (usuarios con role 3)

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


public function receipt($id)
{
    // Obtener la venta con los detalles necesarios
    $sale = Sale::with('details.product', 'user', 'customer')->findOrFail($id);

    // Renderizar la vista `receipt` y pasarle los datos de la venta
    $pdf = Pdf::loadView('livewire.sales.receipt', compact('sale'));

    // Generar y descargar el PDF
    return $pdf->download('receipt_' . $sale->id . '.pdf');
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


public function createUser(Request $request)
{
    // Validar los datos recibidos
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/', // Permitir solo letras y un espacio
        ],
        'last_name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/', // Permitir solo letras y un espacio
        ],
        'second_last_name' => 'nullable|string|max:255|regex:/^[A-Za-záéíóúÁÉÍÓÚ\s]+$/', // Permitir solo letras y un espacio
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'phone' => 'required|string|regex:/^[0-9]{1,12}$/', // Teléfono obligatorio con 10 a 12 caracteres numéricos
    ]);

    // Crear el usuario
    $password = $request->password; // Guardar la contraseña antes de encriptarla
    $user = User::create([
        'name' => $request->name,
        'last_name' => $request->last_name,
        'second_last_name' => $request->second_last_name, // Si no se envía, por defecto 'nulo'
        'email' => $request->email,
        'password' => Hash::make($password), // Asegúrate de encriptar la contraseña
        'phone' => $request->phone, // Asegurarse de guardar el teléfono
        'role' => 3, // Asigna el rol que llega en la solicitud
    ]);

    // Enviar correo electrónico al usuario
    Mail::to($user->email)->send(new \App\Mail\UserRegistered($user, $password));

    // Redirigir o retornar la respuesta que necesites
    return redirect()->back()->with('success', 'Usuario creado exitosamente y se ha enviado un correo de confirmación.');
}


public function confirm($saleId, Request $request)
{
    // Validar que el usuario está autenticado
    $user = auth()->user();

    // Obtener la venta a partir del ID
    $sale = Sale::findOrFail($saleId);

    // Verificar si la venta no está ya confirmada
    if ($sale->status == 'completed') {
        return redirect()->route('sales.show', $sale->id)->with('error', 'La Pedido ya ha sido confirmada.');
    }

    // Actualizar la venta, asignando el vendedor (usuario autenticado)
    $sale->user_id = $request->user_id; // Establecer el usuario autenticado como vendedor
    $sale->status = 'completed'; // Marcar la venta como completada
    $sale->save(); // Guardar los cambios

    // Redirigir de vuelta a la vista de detalles de la venta con un mensaje de éxito
    return redirect()->route('sales.show', $sale->id)->with('success', 'La Pedido ha sido confirmada.');
}


}


