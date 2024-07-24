<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OrderPrintController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('reports', ReportController::class);

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);

    Route::get('/printers', [OrderPrintController::class, 'showPrinterSettings'])->name('printers.index');

    Route::get('/print-order/{orderId}', [OrderPrintController::class, 'printOrder'])->name('printOrder');

    Route::get('/tempUpdate', [OrderController::class, 'tempUpdateOrderItemPurPrice'])->name('tempUpdateOrderItemPurPrice');
});

Route::get('/phpinfo', [HomeController::class, 'checkinfo']);