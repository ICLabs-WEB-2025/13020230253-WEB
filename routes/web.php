<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\Admin\AgentApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use Illuminate\Support\Facades\Route;

// --------------------
// RUTE PUBLIK
// --------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/houses', [HouseController::class, 'index'])->name('houses.index');

// --------------------
// RUTE AUTENTIKASI (GUEST)
// --------------------
Route::middleware('guest')->group(function () {
    // Ganti sesuai autentikasi yang kamu pakai (Breeze, Fortify, atau manual)
    Route::get('/login', [App\Http\Controllers\LoginController::class, 'create'])->name('login');
    Route::post('/login', [App\Http\Controllers\LoginController::class, 'store']);
    
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// --------------------
// RUTE AUTENTIKASI (AUTH)
// --------------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'destroy'])->name('logout');

    // --------------------
    // BUYER ROUTES
    // --------------------
    Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group(function () {
        Route::get('/', [BuyerController::class, 'index'])->name('index');
        Route::get('/houses/{house}', [BuyerController::class, 'show'])->name('show');
        Route::post('/request/{house}', [BuyerController::class, 'requestPurchase'])->name('request');
        Route::get('/offers', [BuyerController::class, 'offers'])->name('offers');
        Route::delete('/offers/{offer}', [BuyerController::class, 'cancel'])->name('cancel');
    });

    // --------------------
    // AGENT ROUTES
    // --------------------
    Route::middleware('role:agent')->prefix('agent')->name('agent.')->group(function () {
        Route::get('/listings', [AgentController::class, 'index'])->name('index');
        Route::get('/houses/create', [AgentController::class, 'create'])->name('create');
        Route::post('/houses', [AgentController::class, 'store'])->name('store');
        Route::get('/houses/{house}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('/houses/{house}', [AgentController::class, 'update'])->name('update');
        Route::delete('/houses/{house}', [AgentController::class, 'destroy'])->name('destroy'); // ✅ PENTING
        Route::get('/requests', [AgentController::class, 'requests'])->name('requests');
        Route::post('/offers/{offer}/approve', [AgentController::class, 'approveOffer'])->name('approve');
        Route::post('/offers/{offer}/reject', [AgentController::class, 'rejectOffer'])->name('reject');
    });

    // --------------------
    // ADMIN ROUTES
    // --------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/applications', [AgentApplicationController::class, 'index'])->name('agent.applications');
        Route::post('/applications/{id}/approve', [AgentApplicationController::class, 'approve'])->name('agent.approve');
        Route::post('/applications/{id}/reject', [AgentApplicationController::class, 'reject'])->name('agent.reject');
    });
});
