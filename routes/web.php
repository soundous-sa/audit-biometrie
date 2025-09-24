<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FonctionnaireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\EtablissementsController;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
*/

// -----------------------------
// Redirection automatique depuis "/" selon le rôle
// -----------------------------
Route::get('/', function () {
    return redirect()->route('dashboard');
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
    Route::get('/dashboard', [AuditController::class, 'dashboard'])->name('dashboard');
    Route::get('/formulaire', [EtablissementsController::class, 'create'])->name('formulaire.create');
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
Route::middleware(['auth', 'user'])->prefix('audits')->group(function () {
    /* Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('user.dashboard');*/

    // Routes spécifiques aux users
    Route::get('/create', [AuditController::class, 'create'])->name('audits.create');
    Route::post('/store', [AuditController::class, 'store'])->name('audits.store');
    Route::resource('audits', AuditController::class)->except(['show']);

    Route::get('/audits/export-form', [AuditController::class, 'showExportForm'])->name('audits.exportForm');
    Route::post('/audits/export-form', [AuditController::class, 'filter'])->name('audits.filter');
    Route::get('/audits/export', [AuditController::class, 'export'])->name('audits.export');
    Route::get('/audits/{id}/pdf', [AuditController::class, 'generatePdf'])->name('audits.pdf');

});

// -----------------------------
// Routes auth complémentaires (mot de passe, inscription, etc.)
// -----------------------------
require __DIR__ . '/auth.php';
