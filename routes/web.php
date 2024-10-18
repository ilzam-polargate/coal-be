<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientAddressController;
use App\Http\Controllers\ClientOrderController;
use App\Http\Controllers\ClientSpecController;
use App\Http\Controllers\ClientPaymentController;
use App\Http\Controllers\ClientOrderDetailController;
use App\Http\Controllers\NotificationController;
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

//cpanel
Route::get('/artisannn', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    Artisan::call('storage:link');
})->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	// Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');


	Route::resource('users', UserController::class);
	Route::resource('stocks', StockController::class);
	
	//clients
	Route::resource('clients', ClientController::class);
	Route::post('/clients/{client}/approve-delete', [ClientController::class, 'approveDelete'])->name('clients.approveDelete');

	// Route untuk menandai dan menghapus semua notifikasi sebagai telah dibaca
	Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead']);

	// Route untuk menandai dan menghapus notifikasi individual sebagai telah dibaca
	Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
	
	Route::resource('client_orders', ClientOrderController::class);
	Route::get('/client-specs/{client_address_id}', [ClientOrderController::class, 'getClientSpecsByAddress']);
	Route::post('client-orders/update-status', [ClientOrderController::class, 'updateStatus'])->name('client_orders.updateStatus');
	Route::get('clients/{client}', [ClientAddressController::class, 'index'])->name('clients.addresses');
	Route::post('clients/{client}/addresses', [ClientAddressController::class, 'store'])->name('clients.addresses.store');
	Route::put('clients/{client}/addresses/{clientAddress}', [ClientAddressController::class, 'update'])->name('clients.addresses.update');
	Route::delete('clients/{client}/addresses/{clientAddress}', [ClientAddressController::class, 'destroy'])->name('clients.addresses.destroy');
	Route::get('/user-profile', [UserProfileController::class, 'showProfile'])->name('user-profile.show');
	Route::post('/user-profile', [UserProfileController::class, 'updateProfile'])->name('user-profile.update');


	// Routes untuk Client Specs
	Route::get('client-addresses/{client_address_id}', [ClientSpecController::class, 'index'])->name('client.specs.index');
	Route::post('client-addresses/{client_address_id}/specs', [ClientSpecController::class, 'store'])->name('client.specs.store');
	Route::put('client-specs/{id}', [ClientSpecController::class, 'update'])->name('client.specs.update');
	Route::delete('client-specs/{id}', [ClientSpecController::class, 'destroy'])->name('client.specs.destroy');

	// Routes untuk Client Payments
	Route::prefix('client_payments')->group(function () {
		Route::get('{orderId}', [ClientPaymentController::class, 'index'])->name('client_payments.index');
		Route::post('{orderId}', [ClientPaymentController::class, 'store'])->name('client_payments.store');
		Route::put('{orderId}/{paymentId}', [ClientPaymentController::class, 'update'])->name('client_payments.update');
		Route::delete('{orderId}/{paymentId}', [ClientPaymentController::class, 'destroy'])->name('client_payments.destroy');
		Route::post('{paymentId}/update-status', [ClientPaymentController::class, 'updateStatus'])->name('client_payments.updateStatus');
	});	

	// Kelompok route untuk client order details
	Route::prefix('client_orders_details')->group(function () {
		Route::get('/{order_id}', [ClientOrderDetailController::class, 'index'])->name('client_order_details.index');
		Route::post('/{order_id}', [ClientOrderDetailController::class, 'store'])->name('client_order_details.store');
		Route::put('/{order_id}/{detail_id}', [ClientOrderDetailController::class, 'update'])->name('client_order_details.update');
		Route::delete('/{order_id}/{detail_id}', [ClientOrderDetailController::class, 'destroy'])->name('client_order_details.destroy');
	});

	// Route untuk update status via AJAX - di luar kelompok prefix
	Route::post('/update-status', [ClientOrderDetailController::class, 'updateStatus'])->name('client_order_details.updateStatus');
	
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');