<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\PublicRegisterController;
use Illuminate\Support\Facades\Route;

// Halaman depan = pembeli daftar member sendiri
// Halaman depan: langsung ke kasir (belum login otomatis diarahkan ke login)
Route::get('/', fn () => redirect()->route('kasir.index'))->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('kasir.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/kasir', [CashierController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/checkout', [CashierController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/member/baru', [CashierController::class, 'create'])->name('member.create');
    Route::post('/member', [CashierController::class, 'store'])->name('member.store');
});

Route::get('/kartu/{token}', [MemberCardController::class, 'show'])->name('member.card');
Route::post('/kartu/{token}/tukar/{voucher}', [MemberCardController::class, 'redeem'])
    ->middleware('throttle:20,1')
    ->name('member.redeem');

require __DIR__.'/auth.php';
