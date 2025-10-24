<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use App\Models\Etablissements;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DashboardExport;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // valeurs de filtre
        $defaultEtab = Etablissements::first();
        $etablissementId = $request->input('etab_id') ?? ($defaultEtab ? $defaultEtab->id : null);

        $etabGraphId  = $request->input('etab_graph_id');
        // on récupère le tableau d'IDs envoyé par <select name="table_etab_id[]">
        $etabTableIds = $request->input('table_etab_id', []); 

        $etablissements = Etablissements::all();

        
        //  Données pour le GRAPHE
        $graphQuery = Audit::query();
        if ($etabGraphId) {
            $graphQuery->where('etab_id', $etabGraphId);
        }

        $stats = $graphQuery->select(
            DB::raw('YEAR(date_audit) as year'),
            DB::raw('SUM(nb_detenus) as total_detenus'),
            DB::raw('SUM(nb_verified_fingerprints) as total_verified'),
            DB::raw('AVG(full_finger_percent) as avg_percent')
        )
        ->groupBy('year')
        ->orderBy('year')
        ->get();

        $years      = $stats->pluck('year')->map(fn($y) => (string) $y)->toArray();
        $detenus    = $stats->pluck('total_detenus')->toArray();
        $verified   = $stats->pluck('total_verified')->toArray();
        $avgPercent = $stats->pluck('avg_percent')->map(fn($v) => round((float)$v, 2))->toArray();

        
        // Données pour le TABLEAU résumé (agrégé par établissement)
        
        $tableStatsQuery = Audit::join('etablissements', 'audits.etab_id', '=', 'etablissements.id')
            ->select(
                'etablissements.libelle as etablissement',
                DB::raw('SUM(nb_detenus) as detenus'),
                DB::raw('SUM(nb_verified_fingerprints) as verified'),
                DB::raw('SUM(fix_fingerprints) as stabilised'),
                DB::raw('ROUND((SUM(nb_verified_fingerprints) / NULLIF(SUM(nb_detenus), 0)) * 100, 2) as pourcentage')
            )
            ->groupBy('etablissements.libelle');

        if (!empty($etabTableIds)) {
            // s'assure qu'on passe un tableau à whereIn
            $tableStatsQuery->whereIn('audits.etab_id', (array) $etabTableIds);
        }

        $tableStats = $tableStatsQuery->get();

      
        // autre données d'interface
      
        $auditsCount = Audit::count();

        $selectedEtab = $etablissementId ? Etablissements::find($etablissementId) : null;
        $etablissementName = $selectedEtab ? $selectedEtab->libelle : '';

        return view('dashboard', compact(
            'auditsCount',
            'years',
            'detenus',
            'verified',
            'avgPercent',
            'etablissements',
            'etablissementId',
            'etablissementName',
            'tableStats'
        ));
    }

    // Export Excel 
    public function exportExcel(Request $request)
    {
        $etabTableId = $request->input('table_etab_id', []);
        return Excel::download(new DashboardExport($etabTableId), 'audits_dashboard.xlsx');
    }

    // Export PDF 
    public function exportPdf(Request $request)
    {
        $etabTableId = $request->input('table_etab_id', []);

        $query = Audit::with('etablissement');
        if (!empty($etabTableId)) {
            $query->whereIn('etab_id', (array)$etabTableId);
        }

        $audits = $query->get();

       
        $pdf = Pdf::loadView('dashboard_pdf', compact('audits'));

        return $pdf->download('audits_dashboard.pdf');
    }
}
