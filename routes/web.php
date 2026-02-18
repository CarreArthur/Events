<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

Route::get('/', function () {
    $latestEvents = \App\Models\Event::where('is_public', true)
        ->orderBy('date_start', 'desc')
        ->limit(3)
        ->get();
    
    return view('welcome', [
        'latestEvents' => $latestEvents,
        'totalEvents' => \App\Models\Event::where('is_public', true)->count(),
        'totalRegistrations' => \App\Models\Registration::count(),
    ]);
});

// ========== ROUTES D'AUTHENTIFICATION (publiques) ==========
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

// ========== ROUTES PROTÉGÉES (authentification requise) ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// ========== ROUTES PUBLIQUES (sans authentification) ==========

Route::get('/events', [EventController::class, 'index'])->name('events.index');

Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/events/{event:slug}/register', [RegistrationController::class, 'create'])
    ->name('registrations.create');

Route::post('/events/{event:slug}/register', [RegistrationController::class, 'store'])
    ->name('registrations.store');

Route::get('/invites/{token}', [RegistrationController::class, 'invite'])
    ->name('registrations.invite');

Route::get('/invites/{token}/confirm', [RegistrationController::class, 'confirmInvite'])
    ->name('registrations.confirm');

Route::get('/registrations/{token}/cancel', [RegistrationController::class, 'cancel'])
    ->name('registrations.cancel');
 
