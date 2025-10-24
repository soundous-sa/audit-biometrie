<?php

namespace App\Exports;

use App\Models\Audit;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DashboardExport implements FromView
{
    // Identifiant de l'établissement à filtrer (optionnel)
    protected $etabTableId;

    /**
     *  Constructeur : initialise le filtre d'établissement
     */
    public function __construct($etabTableId = null)
    {
        $this->etabTableId = $etabTableId;
    }

    /**
     *  Méthode principale : prépare les données pour la vue Excel
     * Elle utilise une vue Blade (`dashboard_excel`) pour générer le fichier.
     */
    public function view(): View
    {
        //  Construction de la requête principale sur la table `audits`
        $query = Audit::select(
            'etablissements.libelle as etablissement',
            DB::raw('SUM(nb_detenus) as detenus'),
            DB::raw('SUM(nb_verified_fingerprints) as verified'),
            DB::raw('SUM(fix_fingerprints) as stabilised'),
            DB::raw('ROUND((SUM(nb_verified_fingerprints) / NULLIF(SUM(nb_detenus), 0)) * 100, 2) as pourcentage')
        )
        //  Jointure avec la table `etablissements` pour obtenir le libellé
        ->join('etablissements', 'audits.etab_id', '=', 'etablissements.id')
        ->groupBy('etablissements.libelle');

        //  Application du filtre si un établissement spécifique est sélectionné
        if ($this->etabTableId) {
            $query->where('audits.etab_id', $this->etabTableId);
        }

        //  Exécution de la requête et récupération des résultats
        $tableStats = $query->get();

        // Retourne la vue utilisée pour générer le fichier Excel
        // La vue 'dashboard_excel' affichera le tableau avec les données collectées
        return view('dashboard_excel', compact('tableStats'));
    }
}
