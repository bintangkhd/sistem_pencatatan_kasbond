<?php

use App\Http\Controllers\KasbondKontroller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
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

Route::get('/', function () {
    return view('home.index');
});

Route::get('/pesirah-page', function () {
    return view('home.pesirah');
});

Route::get('/kur-page', function () {
    return view('home.kur');
});

Route::get('/ksg-page', function () {
    return view('home.ksg');
});

Route::get('/employee-room', function () {
    return view('home.employee_room');
})->middleware('auth');

Route::get('/umum-page', function () {
    return view('home.umum');
})->middleware('auth');


// Route::get('/kasbond', 'KasbondController@index');
Route::get('/data_kasbond', [KasbondKontroller::class, 'index'])->name('user.index')->middleware('auth');
Route::get('/data_kasbond/tambah', [KasbondKontroller::class, 'tambah'])->name('user.tambah');
Route::post('/data_kasbond/tambah', [KasbondKontroller::class, 'store'])->name('user.store');
Route::get('/data_kasbond/edit/{id}', [KasbondKontroller::class, 'edit'])->name('user.edit');
Route::post('/data_kasbond/update', [KasbondKontroller::class, 'update'])->name('user.update');
Route::delete('/data_kasbond/hapus/{id}', [KasbondKontroller::class, 'hapus'])->name('user.hapus');
Route::get('/data_kasbond/download_data/{tanggal}', [KasbondKontroller::class, 'download'])->name('user.download');

// Route for login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// Route for register
Route::get('/register', [RegisterController::class, 'index'])->middleware('admin');
Route::post('/register', [RegisterController::class, 'store']);

// Route::get('/', [KasbondKontroller::class. 'index']);

Route::resource('/data_kasbond/register', RegisterController::class)->middleware('admin');
