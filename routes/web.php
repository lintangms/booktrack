<?php

use App\Http\Controllers\BookReturnController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Landing
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return view('landing');
})->name('home');

// Autentikasi Pengguna
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Terproteksi (Wajib Login)
Route::middleware('auth')->group(function () {

    // Global Root Redirects (Mengarahkan otomatis sesuai role)
    Route::get('/dashboard', function () {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::get('/data-buku', function () {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.books.index')
            : redirect()->route('user.books.index');
    })->name('books.index');

    Route::get('/data-anggota', function () {
        if (!Auth::user()->isAdmin()) abort(403);
        return redirect()->route('admin.members.index');
    })->name('members.index');

    Route::get('/peminjaman', function () {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.loans.index')
            : redirect()->route('user.loans.index');
    })->name('loans.index');

    Route::get('/pengembalian', function () {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.returns.index')
            : redirect()->route('user.returns.index');
    })->name('returns.index');

    // ==========================================
    // AREA ADMINISTRATOR (/admin/...)
    // ==========================================
    Route::prefix('admin')->name('admin.')->middleware('role:Administrator')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Modul 1: Data Buku (Full CRUD)
        Route::prefix('data-buku')->name('books.')->group(function () {
            Route::get('/', [BookController::class, 'index'])->name('index');
            Route::post('/', [BookController::class, 'store'])->name('store');
            Route::put('/{book}', [BookController::class, 'update'])->name('update');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        });

        // Modul 2: Data Pengunjung (Full CRUD)
        Route::prefix('data-pengunjung')->name('members.')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('index');
            Route::post('/', [MemberController::class, 'store'])->name('store');
            Route::put('/{member}', [MemberController::class, 'update'])->name('update');
            Route::delete('/{member}', [MemberController::class, 'destroy'])->name('destroy');
        });
        Route::get('/data-anggota', fn() => redirect()->route('admin.members.index'));

        // Modul 3: Peminjaman (Full CRUD)
        Route::prefix('peminjaman')->name('loans.')->group(function () {
            Route::get('/', [LoanController::class, 'index'])->name('index');
            Route::post('/', [LoanController::class, 'store'])->name('store');
            Route::put('/{loan}', [LoanController::class, 'update'])->name('update');
            Route::delete('/{loan}', [LoanController::class, 'destroy'])->name('destroy');
        });

        // Modul 4: Pengembalian & Denda (Full CRUD)
        Route::prefix('pengembalian')->name('returns.')->group(function () {
            Route::get('/', [BookReturnController::class, 'index'])->name('index');
            Route::post('/', [BookReturnController::class, 'store'])->name('store');
            Route::put('/{return}', [BookReturnController::class, 'update'])->name('update');
            Route::delete('/{return}', [BookReturnController::class, 'destroy'])->name('destroy');
        });
    });

    // ==========================================
    // AREA PENGUNJUNG / USER (/user/...)
    // ==========================================
    Route::prefix('user')->name('user.')->middleware('role:Pengunjung,Administrator')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/data-buku', [BookController::class, 'index'])->name('books.index');
        Route::get('/peminjaman', [LoanController::class, 'index'])->name('loans.index');
        Route::get('/pengembalian', [BookReturnController::class, 'index'])->name('returns.index');
    });

    // Profil Akun (Bisa diakses Admin maupun Pengunjung)
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
