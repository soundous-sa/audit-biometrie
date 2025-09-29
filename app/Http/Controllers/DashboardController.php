<?php

namespace App\Http\Controllers;

use App\Models\Etablissements;

use App\Models\Audit;
use App\Models\User;                
use Illuminate\Http\Request;    

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'usersCount'     => User::count(),
            'prisonsCount'   => Etablissements::count(),
            'auditsCount'    => Audit::count(), 
            'lastUsers'      => User::latest()->take(5)->get(),
            'lastAudits'     => Audit::latest()->take(5)->get(),
        ]);
    }
}
