<?php

namespace App\Http\Controllers;

use App\Models\etablissements;

use App\Models\Audit;
use App\Models\User;                
use Illuminate\Http\Request;
use App\Models\Fonctionnaire;   
use App\Models\ResponseType;  
use Illuminate\Support\Facades\DB; 





class DashboardController extends Controller
{
     public function index()
    {
        // Compter les données
        $prisonsCount        = Etablissements::count();
        $auditsCount         = Audit::count();
        $fonctionnairesCount = Fonctionnaire::count();
        $responseTypesCount  = ResponseType::count();

        // Derniers audits et fonctionnaires
        $lastAudits          = Audit::with('etablissement')->latest()->take(5)->get();
        $lastFonctionnaires  = Fonctionnaire::latest()->take(5)->get();

        // Stats pour le chart
        $stats = Audit::select(
                DB::raw('MONTH(date_audit) as month'),
                'etablissement_id',
                'type',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month', 'etablissement_id', 'type')
            ->get();

        // Retourner la vue avec toutes les variables
        return view('dashboard', compact(
            'prisonsCount',
            'auditsCount',
            'fonctionnairesCount',
            'responseTypesCount',
            'lastAudits',
            'lastFonctionnaires',
            'stats'
        ));
    }

   
}
