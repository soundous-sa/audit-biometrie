<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fonctionnaire;

class FonctionnaireController extends Controller
{
    /**
     *  // لائحة الموظفين
     */
    public function index()
    {
        $fonctionnaires = Fonctionnaire::where('is_deleted', false)->get();
        return view('admin.fonctionnaires.index', compact('fonctionnaires'));
    }

    /**
     *  // صفحة الإضافة
     */
    public function create()
    {
        return view('admin.fonctionnaires.create');
    }

    /**
     *  // حفظ الموظف
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'matricule' => 'required',
        ]);
        Fonctionnaire::create($request->all());

        return redirect()->route('admin.fonctionnaires.index')
            ->with('success', 'تمت إضافة الموظف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * // تعديل
     */
    public function edit(string $id)
    {
        $fonctionnaire = Fonctionnaire::findOrFail($id);
        return view('admin.fonctionnaires.edit', compact('fonctionnaire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fonctionnaire $fonctionnaire)
    {
        $request->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'matricule' => 'required',
        ]);
        $fonctionnaire->update($request->all());

        return redirect()->route('admin.fonctionnaires.index')
            ->with('success', 'تم تعديل الموظف بنجاح');
    }

    /**
     * حذف
     */
  /*  public function destroy(Fonctionnaire $fonctionnaire)
    {
        $fonctionnaire->update(['is_deleted' => true]);
        return redirect()->route('admin.fonctionnaires.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }*/
         public function destroy($id)
{
    $fonctionnaire = Fonctionnaire::findOrFail($id);
    $fonctionnaire->is_deleted = true; // تعيين كأنه محذوف
    $fonctionnaire->save();

    return redirect()->route('admin.fonctionnaires.index')
                     ->with('success', 'تم الحذف بنجاح');
}

}
