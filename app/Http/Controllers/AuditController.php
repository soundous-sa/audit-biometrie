<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use App\Models\Etablissements;
use App\Models\Fonctionnaire;
use App\Exports\AuditExport;
use Maatwebsite\Excel\Facades\Excel;



class AuditController extends Controller
{

    // Afficher la liste des audits
    public function index()
    {
    $audits = Audit::with(['etablissement', 'fonctionnaires'])->get();
        return view('user.audits.index', compact('audits'));
    }

    public function dashboard()
    {
        $audits = Audit::all();

        //dd($audits); // Pour vérifier que les audits sont bien récupérés

        return view('dashboard', compact('audits'));
    }


    // Affichage du formulaire de filtre
    public function showExportForm()
    {
        $etablissements = Etablissements::all();
        return view('user.audits.export-form', compact('etablissements'));
    }

    // Filtrage des audits selon les critères
    public function filter(Request $request)
    {
        $etablissements = Etablissements::all();

    $query =  Audit::with(['etablissement', 'fonctionnaires']);

        if ($request->etab_id) {
            $query->where('etab_id', $request->etab_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date_audit', [$request->from_date, $request->to_date]);
        }

        $audits = $query->get();

        return view('user.audits.export-form', compact('etablissements', 'audits'));
    }

    // Export Excel selon les mêmes filtres
    public function export(Request $request)
    {
        return Excel::download(
            new AuditExport($request->etab_id, $request->from_date, $request->to_date),
            'audits.xlsx'
        );
        // Récupérer tous les fonctionnaires, même is_deleted = 1
        // $fonctionnaires = Fonctionnaire::withTrashed()->get();
    }

    // Formulaire de création
    public function create()
    {
        $fonctionnaires = Fonctionnaire::where('is_deleted', false)->get();;
        $etablissements = Etablissements::all();
        return view('user.audits.create', compact('etablissements', 'fonctionnaires'));
    }




    // Formulaire d'édition

    public function edit($id)
    {
        $audit = Audit::with('fonctionnaires')->findOrFail($id);
        $etablissements = Etablissements::all();
        $fonctionnaires = Fonctionnaire::where('is_deleted', false)->get();
        $selectedFonctionnaires = $audit->fonctionnaires->map(function ($f) {
            return [
                'id' => $f->id,
                'full_name' => $f->full_name,
            ];
        })->values()->toArray();

        return view('user.audits.edit', compact('audit', 'etablissements', 'fonctionnaires', 'selectedFonctionnaires'));
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'etab_id' => 'required|exists:etablissements,id',
            'fonctionnaires' => 'required|array',
            'fonctionnaires.*' => 'exists:fonctionnaires,id',
            'date_audit' => 'required|date',
            'nb_detenus' => 'required|integer|min:0',
            'nb_edited_fingerprints' => 'required|integer|min:0',
            'nb_verified_fingerprints' => 'required|integer|min:0',
            'nb_without_fingerprints' => 'required|integer|min:0',
        ]);

        $audit = Audit::findOrFail($id);

        $audit->update([
            'etab_id' => $request->etab_id,
            'date_audit' => $request->date_audit,
            'nb_detenus' => $request->nb_detenus,
            'nb_edited_fingerprints' => $request->nb_edited_fingerprints,
            'nb_verified_fingerprints' => $request->nb_verified_fingerprints,
            'nb_without_fingerprints' => $request->nb_without_fingerprints,
        ]);

        // Mettre à jour les fonctionnaires liés
        $audit->fonctionnaires()->sync($request->fonctionnaires ?? []);

        return redirect()->route('audits.index')->with('success', 'تم تعديل عملية التحيين بنجاح');
    }


    // Enregistrer un nouvel audit dans la BDD.
    //’objet $request qui contient toutes les données envoyées depuis ton formulaire d’ajout d’audit.
    public function store(Request $request)
    {
        $request->validate([
            'etab_id' => 'required|exists:etablissements,id',
            'fonctionnaires' => 'required|array', // tableau d'IDs
            'fonctionnaires.*' => 'exists:fonctionnaires,id',
            'date_audit' => 'required|date',
            'nb_detenus' => 'required|integer|min:0',
            'nb_edited_fingerprints' => 'required|integer|min:0',
            'nb_verified_fingerprints' => 'required|integer|min:0',
            'nb_without_fingerprints' => 'required|integer|min:0',
        ]);

        $audit = Audit::create([
            'user_id' => Auth::id(),
            'etab_id' => $request->etab_id,
            'date_audit' => $request->date_audit,
            'nb_detenus' => $request->nb_detenus,
            'nb_edited_fingerprints' => $request->nb_edited_fingerprints,
            'nb_verified_fingerprints' => $request->nb_verified_fingerprints,
            'nb_without_fingerprints' => $request->nb_without_fingerprints,
        ]);

        // Associer les fonctionnaires via la table pivot
        $audit->fonctionnaires()->sync($request->fonctionnaires);

        return redirect()->route('audits.index')->with('success', 'تمت إضافة عملية التحيين بنجاح');
    }
}
