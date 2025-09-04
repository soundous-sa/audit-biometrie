<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FonctionnaireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
*/

// -----------------------------
// Redirection automatique depuis "/" selon le rôle
// -----------------------------
Route::get('/', function () {
    if (!Auth::check()) {
        // Non connecté → login
        return redirect()->route('login');
    }

    $user = Auth::user();

    /* Redirection selon le rôle
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }*/

   /* if ($user->role === 'user') {
        return redirect()->route('user.dashboard');
    }*/

    // Si rôle non défini → déconnexion
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect()->route('login')->withErrors([
        'role' => 'Votre compte n’a pas de rôle valide. Contactez l’administrateur.',
    ]);
})->name('home');

// -----------------------------
// Routes login/logout (Breeze/Jetstream)
// -----------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // ✅ Dashboard commun (admin + user)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// -----------------------------
// Routes admin
// -----------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    /*Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');*/

    // CRUD admin
    Route::resource('fonctionnaires', FonctionnaireController::class);
    Route::resource('users', UserController::class);
});


// -----------------------------
// Routes user
// -----------------------------
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
   /* Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('user.dashboard');*/

    // Routes spécifiques aux users
});

// -----------------------------
// Routes auth complémentaires (mot de passe, inscription, etc.)
// -----------------------------
require __DIR__.'/auth.php';
