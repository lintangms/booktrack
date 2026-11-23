<?php

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => ['message' => 'BookTrack API berjalan dengan baik']);

Route::get('/books', function () {
    return response()->json([
        'success' => true,
        'data' => Book::all(),
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    // Transaksi Pengembalian & Denda
    Route::prefix('pengembalian')->name('returns.')->group(function () {
        Route::get('/', [BookReturnController::class, 'index'])->name('index');
        Route::post('/', [BookReturnController::class, 'store'])->name('store');
        Route::put('/{return}', [BookReturnController::class, 'update'])->name('update');
        Route::delete('/{return}', [BookReturnController::class, 'destroy'])->name('destroy');
    });
