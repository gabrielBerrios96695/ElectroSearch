<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReportsController;

Route::view('/', 'welcome');

// Ruta para el dashboard, protegida por autenticación y verificación de email

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('dashboard/update-password', [DashboardController::class, 'updatePassword'])
    ->middleware(['auth', 'verified'])
    ->name('password.update');

// Ruta para el cierre de sesión
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

//Usuarios
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggleStatus', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::get('/users/export', [UserController::class, 'exportToExcel'])->name('users.export');

    // Rutas de clientes
    Route::get('/clients', [UserController::class, 'indexClient'])->name('clients.index');
    Route::get('/clients/create', [UserController::class, 'createClient'])->name('clients.create');
    Route::post('/clients', [UserController::class, 'storeClient'])->name('clients.store');
    Route::get('/clients/{user}/edit', [UserController::class, 'editClient'])->name('clients.edit');
    Route::put('/clients/{user}', [UserController::class, 'updateClient'])->name('clients.update');
    Route::delete('/clients/{user}', [UserController::class, 'destroyClient'])->name('clients.destroy');
    Route::get('/clients/export', [UserController::class, 'exportClientsToExcel'])->name('clients.export');

});

//Productos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/export', [ProductController::class, 'exportToExcel'])->name('products.export');
    Route::post('/products/{id}/updateStock', [ProductController::class, 'updateStock'])->name('products.updateStock');

});

// Grupo de rutas para la gestión de tiendas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/stores', [StoreController::class, 'index'])->name('store.index');
    Route::get('/stores/create', [StoreController::class, 'create'])->name('store.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('store.store');
    Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('/stores/{store}', [StoreController::class, 'update'])->name('store.update');
    Route::patch('/stores/{store}/toggleStatus', [StoreController::class, 'toggleStatus'])->name('store.toggleStatus');
    Route::get('/stores/show', [StoreController::class, 'show'])->name('store.show');
    Route::get('/stores/export', [StoreController::class, 'exportToExcel'])->name('store.export');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('sales', [SaleController::class, 'index'])->name('sales.index'); // Listar todas las ventas
    Route::get('sales/create', [SaleController::class, 'create'])->name('sales.create'); // Mostrar formulario de creación
    Route::post('sales', [SaleController::class, 'store'])->name('sales.store'); // Almacenar nueva venta
    Route::get('sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit'); // Mostrar formulario de edición
    Route::put('sales/{id}', [SaleController::class, 'update'])->name('sales.update'); // Actualizar venta existente
    Route::delete('sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy'); // Eliminar venta
    Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/sales/create-user', [SaleController::class, 'createUser'])->name('sales.createUser');
    Route::post('/products/details', [ProductController::class, 'getDetails']);
    Route::get('/sales/{id}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');


    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('purchases/cancel/{id}', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    Route::post('/sales/{saleId}/confirm', [SaleController::class, 'confirm'])->name('sales.confirm');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-excel', [ReportsController::class, 'exportExcel'])->name('reports.exportExcel');
    Route::get('/reports/top-sellers', [ReportsController::class, 'reportTopSellers'])->name('reports.top_sellers');
    Route::get('/reports/top-sellers/export-excel', [ReportsController::class, 'exportExcelTopSellers'])->name('reports.exportExcelTopSellers');






Route::middleware('auth')->group(function () {
    Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('/ratings/create', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

    // Ruta para obtener los detalles de una venta y sus productos
    Route::get('/sale/{sale}/details', function ($saleId) {
        $sale = \App\Models\Sale::findOrFail($saleId);
        return response()->json($sale->details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'product_name' => $detail->product->name,
            ];
        }));
    });

// Ruta para aprobar un comentario
Route::put('ratings/{rating}/approve', [RatingController::class, 'approve'])->name('ratings.approve');

// Ruta para rechazar un comentario
Route::put('ratings/{rating}/reject', [RatingController::class, 'reject'])->name('ratings.reject');
// Ruta para la vista de aprobar calificaciones
// Ruta para ver todas las calificaciones por aprobar o bloquear
Route::get('ratings/approve', [RatingController::class, 'approveComments'])->name('ratings.approve');
// Ruta para aprobar un comentario
Route::get('ratings/{rating}/approve', [RatingController::class, 'approve'])->name('ratings.approve.comment');

// Ruta para bloquear un comentario
Route::get('ratings/{rating}/block', [RatingController::class, 'block'])->name('ratings.block.comment');

});


    
});



require __DIR__.'/auth.php';
