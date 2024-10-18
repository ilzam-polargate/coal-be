<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\StockApiController;
use App\Http\Controllers\Api\ClientOrderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint untuk login
Route::post('/login', [AuthController::class, 'login']);

// Endpoint untuk logout (dengan middleware auth)
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::get('/clients-checkout', [ClientApiController::class, 'showIndex']);
Route::get('/clients', [ClientApiController::class, 'index']);
Route::post('/clients', [ClientApiController::class, 'store']);
Route::get('/clients/{id}', [ClientApiController::class, 'show']);
Route::middleware('auth:sanctum')->post('/clients/request-delete/{client}', [ClientApiController::class, 'requestDelete']);
// Route::post('/clients/request-delete/{client}', [ClientApiController::class, 'requestDelete']);

Route::get('/orders', [OrderApiController::class, 'index']);
Route::get('/orders/{id}', [OrderApiController::class, 'show']);
Route::put('/order-details/{id}/status', [OrderApiController::class, 'updateStatus']);

Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::get('/stocks', [StockApiController::class, 'index']); // Route untuk menampilkan semua stok
Route::get('/stocks/{id}', [StockApiController::class, 'show']); // Route untuk menampilkan stok berdasarkan ID

Route::post('/client-orders', [ClientOrderController::class, 'store']);