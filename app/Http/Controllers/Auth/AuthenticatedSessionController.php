<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche la page de login.
     */
    public function create(): View
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    /**
     * Traite la requête de connexion.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // Breeze / Jetstream : vérifie email/password
        $request->session()->regenerate();

        $user = Auth::user(); // utilisateur connecté

        // Redirection selon le rôle
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'user') {
            return redirect()->route('dashboard');
        }

        // Si le rôle n'est pas défini ou incorrect, déconnexion et message
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'role' => 'Votre compte n’a pas de rôle valide. Contactez l’administrateur.',
        ]);
    }

    /**
     * Déconnecte l’utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect()->route('login'); // retour à la page login après logout
    }
}
