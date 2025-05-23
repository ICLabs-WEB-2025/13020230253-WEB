<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\Admin\AgentApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Rute Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/houses', [HomeController::class, 'houses'])->name('houses.index');

// Rute Autentikasi (untuk pengguna yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Rute untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Rute Buyer
    Route::middleware('role:buyer')->group(function () {
        Route::get('/buyer', [BuyerController::class, 'index'])->name('buyer.index');
        Route::get('/buyer/{id}', [BuyerController::class, 'show'])->name('buyer.show');
        Route::post('/buyer/request/{id}', [BuyerController::class, 'requestPurchase'])->name('buyer.request');
        Route::get('/buyer/offers', [BuyerController::class, 'offers'])->name('buyer.offers');
        Route::delete('/buyer/offers/{offer}', [BuyerController::class, 'cancel'])->name('buyer.cancel');
    });

    // Rute Agent
    Route::middleware('role:agent')->prefix('agent')->group(function () {
        Route::get('/listings', [AgentController::class, 'index'])->name('agent.index');
        Route::get('/houses/create', [AgentController::class, 'create'])->name('agent.create');
        Route::post('/houses', [AgentController::class, 'store'])->name('agent.store');
        Route::get('/houses/{house}/edit', [AgentController::class, 'edit'])->name('agent.edit');
        Route::put('/houses/{house}', [AgentController::class, 'update'])->name('agent.update');
        Route::delete('/houses/{house}', [AgentController::class, 'destroy'])->name('agent.destroy');
        Route::get('/requests', [AgentController::class, 'requests'])->name('agent.requests');
        Route::post('/offers/{offer}/approve', [AgentController::class, 'approveOffer'])->name('agent.approve');
        Route::post('/offers/{offer}/reject', [AgentController::class, 'rejectOffer'])->name('agent.reject');
    });

    // Rute Admin
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/applications', [AgentApplicationController::class, 'index'])->name('admin.agent.applications');
        Route::post('/applications/{id}/approve', [AgentApplicationController::class, 'approve'])->name('admin.agent.approve');
        Route::post('/applications/{id}/reject', [AgentApplicationController::class, 'reject'])->name('admin.agent.reject');
    });
});