<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//csrf token
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::post('/create-payment', [App\Http\Controllers\PaymentController::class, 'createPayment']);
Route::post('/tripay-callback', [App\Http\Controllers\PaymentController::class, 'callback']);

Route::get('/', [App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/reset', [App\Http\Controllers\PaymentController::class, 'resetPayments'])->name('payments.reset');
Route::get('/payments/{payment}', [App\Http\Controllers\PaymentController::class, 'detail'])->name('payments.detail');
Route::get('/payments/{payment}/check-status', [App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payments.check-status');
Route::get('/payments/check-status/{id}', [App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payments.checkStatus');
