<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;



Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::view('/', 'welcome');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

  
    //Usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggleStatus', [UserController::class, 'toggleStatus'])->name('users.toggleStatus'); 



// Productos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


Route::get('/stores', [StoreController::class, 'index'])->name('store.index');
Route::get('/stores/create', [StoreController::class, 'create'])->name('store.create');
Route::post('/stores', [StoreController::class, 'store'])->name('store.store');
Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('store.edit');
Route::put('/stores/{store}', [StoreController::class, 'update'])->name('store.update');
Route::patch('/stores/{store}/toggleStatus', [StoreController::class, 'toggleStatus'])->name('store.toggleStatus');



require __DIR__.'/auth.php';
