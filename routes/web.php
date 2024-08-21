<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TypePointController;
use App\Http\Controllers\CollectionPointController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;




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
});

//Productos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

//tiendas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/stores', [StoreController::class, 'index'])->name('store.index');
    Route::get('/stores/create', [StoreController::class, 'create'])->name('store.create');
    Route::post('/stores', [StoreController::class, 'store'])->name('store.store');
    Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('/stores/{store}', [StoreController::class, 'update'])->name('store.update');
    Route::patch('/stores/{store}/toggleStatus', [StoreController::class, 'toggleStatus'])->name('store.toggleStatus');
});


// Rutas para los tipos de puntos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/type_points', [TypePointController::class, 'index'])->name('type_points.index');
    Route::get('/type_points/create', [TypePointController::class, 'create'])->name('type_points.create');
    Route::post('/type_points', [TypePointController::class, 'store'])->name('type_points.store');
    Route::get('/type_points/{typePoint}/edit', [TypePointController::class, 'edit'])->name('type_points.edit');
    Route::put('/type_points/{typePoint}', [TypePointController::class, 'update'])->name('type_points.update');
    Route::patch('/type_points/{typePoint}/toggleStatus', [TypePointController::class, 'toggleStatus'])->name('type_points.toggleStatus');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/collection_points', [CollectionPointController::class, 'index'])->name('collection_points.index');
    Route::get('/collection_points/create', [CollectionPointController::class, 'create'])->name('collection_points.create');
    Route::post('/collection_points', [CollectionPointController::class, 'store'])->name('collection_points.store');
    Route::get('/collection_points/{collectionPoint}/edit', [CollectionPointController::class, 'edit'])->name('collection_points.edit');
    Route::put('/collection_points/{collectionPoint}', [CollectionPointController::class, 'update'])->name('collection_points.update');
    Route::delete('/collection_points/{collectionPoint}', [CollectionPointController::class, 'destroy'])->name('collection_points.destroy');
    Route::patch('/collection_points/{collectionPoint}/toggleStatus', [CollectionPointController::class, 'toggleStatus'])->name('collection_points.toggleStatus');
});



require __DIR__.'/auth.php';
