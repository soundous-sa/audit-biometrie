<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use App\Models\Etablissements;
use App\Models\Fonctionnaire;


class AuditController extends Controller
{

    // Afficher la liste des audits
    public function index()
    {
        $audits = Audit::with(['etablissement', 'fonctionnaire'])->get();
        return view('user.audits.index', compact('audits'));
    }

    public function dashboard()
    {
        $audits = Audit::all();

        //dd($audits); // Pour vérifier que les audits sont bien récupérés

        return view('dashboard', compact('audits'));
    }




    // Formulaire de création
    public function create()
    {
        $fonctionnaires = Fonctionnaire::all();
        $etablissements = Etablissements::all();
        return view('user.audits.create', compact('etablissements', 'fonctionnaires'));
    }

    public function edit($id)
    {
        $audit = Audit::findOrFail($id);
        $etablissements = Etablissements::all();
        $fonctionnaires = Fonctionnaire::all();

        return view('user.audits.edit', compact('audit', 'etablissements', 'fonctionnaires'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'etab_id' => 'required|exists:etablissements,id',
            'fonct_id' => 'required|exists:fonctionnaires,id',
            'date_audit' => 'required|date',
            'nb_detenus' => 'required|integer|min:0',
            'nb_edited_fingerprints' => 'required|integer|min:0',
            'nb_verified_fingerprints' => 'required|integer|min:0',
            'nb_without_fingerprints' => 'required|integer|min:0',
        ]);

        $audit = Audit::findOrFail($id);
        $audit->update($request->all());

        return redirect()->route('audits.index')->with('success', 'تم تعديل عملية التحيين بنجاح');
    }

    // Enregistrer un nouvel audit dans la BDD.
    //’objet $request qui contient toutes les données envoyées depuis ton formulaire d’ajout d’audit.
    public function store(Request $request)
    {   //validation des données du formulaire.
        // dd($request->all());
        $request->validate([
            'etab_id' => 'required|exists:etablissements,id',
            'fonct_id' => 'required|exists:fonctionnaires,id',
            'date_audit' => 'required|date',
            'nb_detenus' => 'required|integer|min:0',
            'nb_edited_fingerprints' => 'required|integer|min:0',
            'nb_verified_fingerprints' => 'required|integer|min:0',
            'nb_without_fingerprints' => 'required|integer|min:0',
        ]);



        Audit::create([
            'user_id' => Auth::id(),
            'etab_id' => $request->etab_id,
            'fonct_id' => $request->fonct_id,
            'date_audit' => $request->date_audit,
            'nb_detenus' => $request->nb_detenus,
            'nb_edited_fingerprints' => $request->nb_edited_fingerprints,
            'nb_verified_fingerprints' => $request->nb_verified_fingerprints,
            'nb_without_fingerprints' => $request->nb_without_fingerprints,
        ]);

        //Tu rediriges l’utilisateur vers la liste des audits avec un message de succès.
        return redirect()->route('audits.index')->with('success', 'تمت إضافة عملية التحيين بنجاح');
    }
}
