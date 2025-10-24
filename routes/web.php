<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtablissementsController;
use App\Http\Controllers\FonctionnaireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;

// Page d’accueil redirige vers dashboard
Route::get('/', fn() => redirect()->route('dashboard'))->name('home');

// Auth (login / logout)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware(['auth'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');

});


// Routes ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('fonctionnaires', FonctionnaireController::class);
    Route::resource('users', UserController::class);
});

// Routes USERS (+ admin aussi)
Route::middleware(['auth', 'user'])->group(function () {
    Route::resource('audits', AuditController::class)->except(['show']);

    Route::get('audits/export-form', [AuditController::class, 'showExportForm'])->name('audits.exportForm');
    Route::post('audits/export-form', [AuditController::class, 'filter'])->name('audits.filter');
    Route::get('audits/export', [AuditController::class, 'export'])->name('audits.export');
    Route::get('audits/{id}/word', [AuditController::class, 'generateWordFromTemplate'])->name('audits.word');
    Route::get('audits/{id}/pdf', [AuditController::class, 'generatePdf'])->name('audits.pdf');
});

require __DIR__ . '/auth.php';
