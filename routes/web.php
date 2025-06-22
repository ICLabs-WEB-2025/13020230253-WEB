<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\Admin\AgentApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/houses', [HomeController::class, 'index'])->name('houses.index');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group(function () {
        Route::get('/', [BuyerController::class, 'index'])->name('index');
        Route::get('/houses/{house}', [BuyerController::class, 'show'])->name('show');
        Route::post('/request/{house}', [BuyerController::class, 'requestPurchase'])->name('request');
        Route::get('/offers', [BuyerController::class, 'offers'])->name('offers');
        Route::delete('/offers/{offer}', [BuyerController::class, 'cancel'])->name('cancel');
        Route::post('/send-message', [BuyerController::class, 'sendMessage'])->name('sendMessage');
    });

    Route::middleware('role:agent')->prefix('agent')->name('agent.')->group(function () {
        Route::get('/listings', [AgentController::class, 'index'])->name('index');
        Route::get('/houses/create', [AgentController::class, 'create'])->name('create');
        Route::post('/houses', [AgentController::class, 'store'])->name('store');
        Route::get('/houses/{house}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('/houses/{house}', [AgentController::class, 'update'])->name('update');
        Route::delete('/houses/{house}', [AgentController::class, 'destroy'])->name('destroy');
        Route::get('/requests', [AgentController::class, 'requests'])->name('requests');
        Route::post('/offers/{offer}/approve', [AgentController::class, 'approveOffer'])->name('approve');
        Route::post('/offers/{offer}/reject', [AgentController::class, 'rejectOffer'])->name('reject');
        Route::get('/conversations', [AgentController::class, 'getConversations'])->name('conversations');
        Route::get('/chat/messages/{conversation}', [AgentController::class, 'getMessages'])->name('chat.messages');
        Route::post('/chat/send/{conversation}', [AgentController::class, 'sendMessage'])->name('chat.send');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/applications', [AgentApplicationController::class, 'index'])->name('agent.applications');
        Route::post('/applications/{id}/approve', [AgentApplicationController::class, 'approve'])->name('agent.approve');
        Route::post('/applications/{id}/reject', [AgentApplicationController::class, 'reject'])->name('agent.reject');
    });
});
?>