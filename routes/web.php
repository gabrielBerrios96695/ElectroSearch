<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;


//logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
  
    //Usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    

    //tiendas
Route::get('/stores', [StoreController::class, 'index'])->name('store.index');
Route::get('/stores/create', [StoreController::class, 'create'])->name('store.create');
Route::post('/stores', [StoreController::class, 'store'])->name('store.store');
Route::get('/stores/{store}/edit', [StoreController::class, 'edit'])->name('store.edit');
Route::put('/stores/{store}', [StoreController::class, 'update'])->name('store.update');
Route::delete('/stores/{store}', [StoreController::class, 'destroy'])->name('store.destroy');



require __DIR__.'/auth.php';
