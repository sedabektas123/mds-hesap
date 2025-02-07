<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EndustriyelFirmController;
use App\Http\Controllers\IsletmecilikFirmController;
use App\Http\Controllers\EndustriyelTransactionController;
use App\Http\Controllers\IsletmecilikTransactionController;
use App\Http\Controllers\EndustriyelCariAccountController;
use App\Http\Controllers\IsletmecilikCariAccountController;

// Anasayfa
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Kullanıcı Profili İşlemleri
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Yetkilendirme route'ları
require __DIR__.'/auth.php';

// Endüstriyel Modülü
Route::prefix('endustriyel')->name('endustriyel.')->middleware(['auth'])->group(function () {
    Route::resource('firms', EndustriyelFirmController::class);
    Route::resource('cari', EndustriyelCariAccountController::class);
    Route::resource('transactions', EndustriyelTransactionController::class);
});

// İşletmecilik Modülü
Route::prefix('isletmecilik')->name('isletmecilik.')->middleware(['auth'])->group(function () {
    Route::resource('firms', IsletmecilikFirmController::class);
    Route::resource('cari', IsletmecilikCariAccountController::class);
    Route::resource('transactions', IsletmecilikTransactionController::class);
});

// Özel Endüstriyel Route'ları (Zaten `Route::resource` ile oluşturulduğu için gerek yoktu, kaldırıldı)
Route::get('endustriyel/cari/{id}/edit', [EndustriyelCariAccountController::class, 'edit'])->name('endustriyel.cari.edit');
Route::put('endustriyel/cari/{id}', [EndustriyelCariAccountController::class, 'update'])->name('endustriyel.cari.update');

Route::get('/endustriyel/cari/create', [EndustriyelCariAccountController::class, 'create'])->name('endustriyel.cari.create');
Route::post('/endustriyel/cari', [EndustriyelCariAccountController::class, 'store'])->name('endustriyel.cari.store');

// Özel İşletmecilik Route'ları
Route::prefix('isletmecilik')->group(function () {
    Route::get('/transactions', [IsletmecilikTransactionController::class, 'index'])->name('isletmecilik.transactions.index');
    Route::post('/transactions', [IsletmecilikTransactionController::class, 'store'])->name('isletmecilik.transactions.store');
    Route::delete('/transactions/{id}', [IsletmecilikTransactionController::class, 'destroy'])->name('isletmecilik.transactions.destroy');
});

Route::get('isletmecilik/cari/{id}/edit', [IsletmecilikCariAccountController::class, 'edit'])->name('isletmecilik.cari.edit');
Route::put('isletmecilik/cari/{id}', [IsletmecilikCariAccountController::class, 'update'])->name('isletmecilik.cari.update');

// Veritabanındaki NULL firma isimlerini güncellemek için özel işlem
Route::get('/update-null-firm-names', [EndustriyelTransactionController::class, 'updateNullFirmNames']);
