<?php

namespace App\Http\Controllers;
use App\Models\Etablissement;
use App\Models\Etablissements;
use Illuminate\Http\Request;



class EtablissementsController extends Controller
{
    public function create()
    {
        // Récupérer tous les établissements
        $etablissements = Etablissements::all();

        // Envoyer la variable à la vue
        return view('create', compact('etablissements'));
    }
}
