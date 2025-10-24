<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fonctionnaire;

class FonctionnaireController extends Controller
{
    /**
     *Affiche la liste des fonctionnaires actifs (non supprimés)
     */  
    public function index()
    {
        $fonctionnaires = Fonctionnaire::where('is_deleted', false)->get();
        return view('admin.fonctionnaires.index', compact('fonctionnaires'));
    }

    /**
     *  Affiche le formulaire d'ajout d'un nouveau fonctionnaire
     */
    public function create()
    {
        return view('admin.fonctionnaires.create');
    }

    /**
     *  Enregistre un nouveau fonctionnaire dans la base de données
     */
    public function store(Request $request)
    {
        // Validation des champs
        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'matricule' => 'required',
        ]);

        // Création du fonctionnaire
        Fonctionnaire::create($request->all());

        return redirect()->route('admin.fonctionnaires.index')
                         ->with('success', 'تمت إضافة الموظف بنجاح');
    }

    /**
     *  (Optionnel) Affiche les détails d’un fonctionnaire spécifique
     */
    public function show(string $id)
    {
        // Non utilisé ici
    }

    /**
     *  Affiche le formulaire d'édition d'un fonctionnaire existant
     */
    public function edit(string $id)
    {
        $fonctionnaire = Fonctionnaire::findOrFail($id);
        return view('admin.fonctionnaires.edit', compact('fonctionnaire'));
    }

    /**
     *  Met à jour les informations d’un fonctionnaire
     */
    public function update(Request $request, Fonctionnaire $fonctionnaire)
    {
        // Validation des champs
        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'matricule' => 'required',
        ]);

        // Mise à jour des données
        $fonctionnaire->update($request->all());

        return redirect()->route('admin.fonctionnaires.index')
                         ->with('success', 'تم تعديل الموظف بنجاح');
    }

    /**
     *  Marque un fonctionnaire comme supprimé (suppression logique)
     */
    public function destroy($id)
    {
        $fonctionnaire = Fonctionnaire::findOrFail($id);
        $fonctionnaire->is_deleted = true; // Suppression logique
        $fonctionnaire->save();

        return redirect()->route('admin.fonctionnaires.index')
                         ->with('success', 'تم الحذف بنجاح');
    }
}
