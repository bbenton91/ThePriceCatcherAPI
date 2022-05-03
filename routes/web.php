<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/recently_changed', [ProductController::class, 'getRecentlyChanged']);
Route::get('/recently_added', [ProductController::class, 'getRecentlyAdded']);
Route::get('/most_viewed', [ProductController::class, 'getMostViewed']);
Route::get('/history', [HistoryController::class, 'getHistory']);
