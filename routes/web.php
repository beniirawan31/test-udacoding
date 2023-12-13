<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuntController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return auth()->check() ? redirect()->route('siswa.index') : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::controller(AuntController::class)->group(function () {
        Route::get('register', 'register')->name('register');
        Route::post('register', 'registerSave')->name('register.save');

        Route::get('login', 'login')->name('login');
        Route::post('login', 'loginAction')->name('login.action');
    });
});

Route::middleware('auth')->group(function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::prefix('siswa')->group(function () {
        Route::get('/index', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/tambah', [SiswaController::class, 'tambah'])->name('siswa.tambah');
        Route::post('/store', [SiswaController::class, 'store'])->name('siswa.store');

        Route::get('/siswa/edit/{id}', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::post('/update', [SiswaController::class, 'update'])->name('siswa.update');

        Route::post('/delete', [SiswaController::class, 'delete'])->name('siswa.delete');
    });

    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});
