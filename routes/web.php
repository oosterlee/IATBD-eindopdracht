<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Product;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ReviewController;

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

Route::get('/', [ProductController::class, 'index']);

Route::middleware(['auth'])->group(function() {
	Route::get('/product/create', [ProductController::class, 'create']);
	Route::post('/product/create', [ProductController::class, 'store']);

	Route::get('/product/{id}/edit', [ProductController::class, 'edit']);
	Route::get('/product/{id}/remove', [ProductController::class, 'destroy']);
	Route::post('/product/{id}/edit', [ProductController::class, 'update']);

	Route::get('/product/{id}/loan', [ProductController::class, 'loanProduct']);
	Route::get('/product/{id}/returned', [ProductController::class, 'productReturned']);

	Route::post('/review/create', [ReviewController::class, 'store']);
	Route::get('/review/{id}/remove', [ReviewController::class, 'destroy']);

	Route::get('/users/{id}/block', [ProductController::class, 'blockUser']);
	Route::get('/users/{id}/unblock', [ProductController::class, 'unblockUser']);
});

Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'showUserProducts']);

Route::get('/image/{id}', [ImageController::class, 'show']);



require __DIR__.'/auth.php';