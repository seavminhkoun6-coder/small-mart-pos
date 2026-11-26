<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

/*
|--------------------------------------------------------------------------
| Default & Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login'); // ឬប្តូរទៅ redirect()->route('pos.index') បើចង់ចូល POS ផ្ទាល់
});

// បង្ហាញទំព័រ Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// ទទួលទិន្នន័យពី Form Login មកផ្ទៀងផ្ទាត់
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

/*
|--------------------------------------------------------------------------
| POS Cashier & Sales History Routes
|--------------------------------------------------------------------------
*/
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
Route::get('/pos/orders', [PosController::class, 'orders'])->name('pos.orders');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Product Routes
Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

// Category Routes
Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
Route::post('/admin/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
Route::put('/admin/categories/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
Route::delete('/admin/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');