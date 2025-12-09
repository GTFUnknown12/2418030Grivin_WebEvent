<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Authenticated user routes
Route::middleware(['auth:pembeli'])->group(function () {
    // User dashboard
    Route::get('/user/dashboard', [HomeController::class, 'userDashboard'])->name('index.user');
    
    // Tickets routes - PAKAI INI
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    
    
    Route::get('/export-pdf', [TicketController::class, 'exportPDF'])->name('tickets.export.pdf');
    
    // Event registration
    Route::post('/events/{id}/register', [EventController::class, 'registerEvent'])->name('events.register');
});

// Admin routes
Route::prefix('admin')->middleware(['auth:pembeli', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [DashboardController::class, 'admin'])->name('admin.users');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions');
    Route::get('/transactions/{id}', [TransactionController::class, 'viewTransaction'])->name('admin.transactions.view');
    
    // Tickets management
    Route::get('/tickets', [TicketController::class, 'index'])->name('admin.tickets');
    Route::put('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('admin.tickets.status');
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');
    
    // Events management
    Route::resource('events', EventController::class)->except(['show']);
    
    // Transactions API
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('home');
});