<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('login');
})->name('login');
Route::post('/postlogin',[AuthController::class,'login'])->name('postlogin');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');

Route::prefix('pustakawan')->group(function () {
    Route::get('/home', [BookController::class, 'index'])->name('home');

    Route::get('/log', [BookController::class, 'log'])->name('log');

    Route::post('/post-keranjang/{id}', [BookController::class, 'postkeranjang'])->name('post-keranjang');
    Route::get('/keranjang', [BookController::class, 'keranjang'])->name('keranjang');

    // Route::get('/checkout/{tranID}', [BookController::class, 'checkout'])->name('checkout');
    Route::post('/postcheckout/{tranID}', [BookController::class, 'postcheckout'])->name('postcheckout');

    Route::get('/history', [BookController::class, 'history'])->name('history');

    Route::get('/caribuku', [BookController::class, 'caribuku'])->name('caribuku');
    Route::get('/hapuskeranjang/{id}', [BookController::class, 'hapuskeranjang'])->name('hapuskeranjang');
});

Route::prefix('admin')->group(function () {
    Route::get('/home', [AdminController::class, 'index'])->name('home.admin');
    Route::get('/home-filtered', [AdminController::class, 'bookTidakDijual'])->name('filteredhome.admin');
    Route::get('/add-book', [AdminController::class, 'addBook'])->name('add-book.admin');
    Route::post('/post-add-book', [AdminController::class, 'postAddBook'])->name('post-add-book.admin');
    Route::get('/edit-book/{book}', [AdminController::class, 'editBook'])->name('edit-book.admin');
    Route::post('/post-edit-book/{book}', [AdminController::class, 'postEditBook'])->name('post-edit-book.admin');
    Route::get('/nonaktifkan-buku/{book}', [AdminController::class, 'nonaktifkanBuku'])->name('nonaktifkan-buku.admin');
    Route::get('/aktifkan-buku/{book}', [AdminController::class, 'aktifkanBuku'])->name('aktifkan-buku.admin');
});

Route::prefix('owner')->group(function () {
    Route::get('/home', [OwnerController::class, 'index'])->name('home.owner');
    Route::get('/homes', [OwnerController::class, 'filteredChart'])->name('filteredhome.owner');
});
