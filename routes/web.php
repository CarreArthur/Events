<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController; // ✅ AJOUTE ÇA

Route::get('/', function () {
    return view('welcome');
});

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
 
