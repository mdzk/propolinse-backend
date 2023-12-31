<?php

use App\Models\User;
use App\Models\CartItem;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Produk\CartController;
use App\Http\Controllers\UploadGambarController;
use App\Http\Controllers\Produk\ControllerBarang;
use App\Http\Controllers\Auth\CodeCheckController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Produk\CheckoutController;
use App\Http\Controllers\Produk\PembayaranController;
use App\Http\Controllers\Auth\ForgotPaswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/bestseller', [PembayaranController::class, 'bestseller']);
Route::get('/barang', [ControllerBarang::class, 'index']);
Route::get('/barang/{id}', [ControllerBarang::class, 'show']);
Route::post('/barang', [ControllerBarang::class, 'store'])->middleware(['auth:sanctum']);
Route::get('/kategori', [ControllerBarang::class, 'kategori']);
Route::get('/jumlah', [ControllerBarang::class, 'hitungJumlahBarang']);
Route::post('/update/{id}', [ControllerBarang::class, 'update']);
Route::post('/ubah/{id}', [ControllerBarang::class, 'gbrupdate']);
Route::delete('/delete/{id}', [ControllerBarang::class, 'destroy']);

Route::post('/login', [RegisterController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/logout', [RegisterController::class, 'logout'])->middleware(['auth:sanctum']);

Route::post('password/email',  ForgotPaswordController::class);
Route::post('password/code/check', CodeCheckController::class);
Route::post('password/reset', ResetPasswordController::class);

// Dapatkan semua data user
Route::get('/user/all', [UserController::class, 'getAll']);
// Dapatkan data user berdasarkan token
Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:sanctum');
Route::delete('/user/{id}', [UserController::class, 'delete']);

Route::post('/update/prof/{id}', [RegisterController::class, 'update']);

Route::delete('carts/del/{cart_id}', [CartController::class, 'removeFromCart'])->middleware('auth:sanctum');
Route::post('keranjang', [CartController::class, 'keranjang'])->middleware('auth:sanctum');
Route::get('carts/user', [CartController::class, 'getUserCart'])->middleware('auth:sanctum');
Route::get('carts/user/home', [CartController::class, 'getUserCartHome'])->middleware('auth:sanctum');

Route::post('checkout/input', [CheckoutController::class, 'inputcheckout2'])->middleware(['auth:sanctum']);

Route::post('konfirm/{id}', [PembayaranController::class, 'konfirmasi']);
Route::get('admin/pesanan', [PembayaranController::class, 'pesanan'])->middleware(['auth:sanctum']);
Route::get('admin/pembayaran', [PembayaranController::class, 'pembayaran'])->middleware(['auth:sanctum']);
Route::get('admin/dashboard', [PembayaranController::class, 'dashboard']);
// Route::get('pesanan', [PembayaranController::class, 'jumlahpesanan']);
// Route::get('pembayaran', [PembayaranController::class, 'jumlahpembayaran']);
