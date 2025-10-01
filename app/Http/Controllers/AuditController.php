<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use App\Models\Etablissements;
use App\Models\Fonctionnaire;
use App\Exports\AuditExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ResponseType;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\FontMetrics;
use Dompdf\Options;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;


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
        $responseTypes = \App\Models\ResponseType::all();
        return view('user.audits.create', compact('responseTypes', 'etablissements', 'fonctionnaires'));
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
        $responseTypes = \App\Models\ResponseType::all();

        return view('user.audits.edit', compact('audit', 'responseTypes', 'etablissements', 'fonctionnaires', 'selectedFonctionnaires'));
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
        $validated = $request->validate([
            'response_type_id' => 'nullable|exists:response_types,id',
        ]);

        $audit->update($validated);
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
            'response_type_id' => 'nullable|exists:response_types,id',
        ]);


        $audit = Audit::create([

            'etab_id' => $request->etab_id,
            'date_audit' => $request->date_audit,
            'nb_detenus' => $request->nb_detenus,
            'nb_edited_fingerprints' => $request->nb_edited_fingerprints,
            'nb_verified_fingerprints' => $request->nb_verified_fingerprints,
            'nb_without_fingerprints' => $request->nb_without_fingerprints,
            'response_type_id' => $request->response_type_id,
        ]);

        // Associer les fonctionnaires via la table pivot
        $audit->fonctionnaires()->sync($request->fonctionnaires ?? []);

        return redirect()->route('audits.index')->with('success', 'تمت إضافة عملية التحيين بنجاح');
    }



    // Générer et télécharger le PDF d'un audit
    // AuditController
    public function generatePdf($id)
    {
        $audit = Audit::with('etablissement')->findOrFail($id);

        $etablissement = $audit->etablissement ? $audit->etablissement->libelle : 'غير محدد';


        $dateAudit = \Carbon\Carbon::parse($audit->date_audit)->format('Y/m/d');

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),   // 👈 add this
            ]),
            'fontdata' => $fontData + [
                'amiri' => [
                    'R' => 'Amiri-Regular.ttf'
                ]
            ],
            'default_font' => 'amiri',  // 👈 force Amiri as default
            'autoScriptToLang' => true,  // 👈 important for Arabic
            'autoLangToFont' => true,    // 👈 fallback if glyph missing
        ]);

        /// calcule de: معتقلين استقرت وضعية بصماتهم
        $settled =  $audit->nb_edited_fingerprints - $audit->nb_verified_fingerprints;

        // calcule de: معتقلين أخذت لهم البصمات لجميع الأصابع (10 أصابع) بنسبة
        $fullFingerPercent = 0; // toujours définir la variable avant usage
        $totalFingers = $audit->nb_verified_fingerprints + $audit->nb_edited_fingerprints + $settled;

        if ($totalFingers > 0) {
            $fullFingerPercent = ($audit->nb_verified_fingerprints / $totalFingers) * 100;
        }
        $audit = Audit::with('responseType')->findOrFail($id);
        // tu peux soit passer $audit seul, soit aussi extraire $responseType
        $responseType = $audit->responseType;


        // Envoyer toutes les variables à la vue
        $html = view('user.audits.pdf', compact('audit', 'etablissement', 'dateAudit', 'settled', 'fullFingerPercent', 'responseType'))->render();

        $mpdf->WriteHTML($html);
        return $mpdf->Output('audit_' . $audit->id . '.pdf', 'I');
    }

    // helper function
    private function rtl($text)
    {
        return "\u{202B}" . $text . "\u{202C}";
    }


    public function generateWordFromTemplate($id)
    {
        $audit = Audit::with('etablissement', 'responseType')->findOrFail($id);
        $etablissement = $audit->etablissement ? $audit->etablissement->libelle : 'غير محدد';
        $dateAudit = \Carbon\Carbon::parse($audit->date_audit)->format('Y/m/d');

        $settled =  $audit->nb_edited_fingerprints - $audit->nb_verified_fingerprints;
        $fullFingerPercent = 0;
        $totalFingers = $audit->nb_verified_fingerprints + $audit->nb_edited_fingerprints + $settled;
        if ($totalFingers > 0) {
            $fullFingerPercent = ($audit->nb_verified_fingerprints / $totalFingers) * 100;
        }

        // **Ajouter la date d'aujourd'hui**
        $today = Carbon::now()->format('d/m/Y'); // ou 'Y/m/d' selon préférence

        // Load Word template
        $templatePath = storage_path('app/templates/audit.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Replace placeholders
        $templateProcessor->setValue('etablissement', $etablissement);
        $templateProcessor->setValue('dateAudit', $dateAudit);
        $templateProcessor->setValue('nb_verified', $audit->nb_verified_fingerprints);
        $templateProcessor->setValue('percent', number_format($fullFingerPercent, 2) . '%');
        $templateProcessor->setValue('settled', $settled);

        // line1
        if ($audit->nb_edited_fingerprints > 0) {
            $templateProcessor->setValue(
                'line1',
                $this->rtl("{$audit->nb_edited_fingerprints} معتقلين ارتفع عدد الاصابع الماخودة لبصماتهم عن السابق بالرغم من انه لم يصل الى 10 اصابع.")
            );
        } else {
            $templateProcessor->setValue('line1', '');
        }

        // line2
        if ($settled > 0) {
            $templateProcessor->setValue(
                'line2',
                $this->rtl("{$settled} معتقلين استقرت وضعية بصماتهم.")
            );
        } else {
            $templateProcessor->setValue('line2', '');
        }

        // line3
        if ($audit->nb_without_fingerprints > 0) {
            $templateProcessor->setValue(
                'line3',
                $this->rtl("{$audit->nb_without_fingerprints} معتقلين تمت معاينتهم عينيا دون اللجوء الى لاخد بصماتهم )بتر، إعاقة، تشوه (....")
            );
        } else {
            $templateProcessor->setValue('line3', '');
        }



        // extra paragraph
        $extraText = '';

        if (optional($audit->responseType)->id == 3) {
            $extraText = $this->rtl("
        و من خلال هذه الارقام، تبين أن بعض الموظفين المكلفين بهذه العملية لم يتقيدوا بالإجراءات المنصوص عليها في بدليل الاستعمال التقنية البيومترية)مدكرة السيد المندوب العام رقم 32 عدد(  ولا يقومون بتحيين دوري لأخذ بصمات المعتقلين الذين لم يتم اخذ البصمات لجميع اصابعهم.
        
        لذا ونظرا لأهمية الموضوع في ضبط هوية المعتقل وحالة العود، ندعوكم لإعطاء تعليماتكم لمصالحكم المختصة قصد ايلاء العناية القصوى لهذه العملية والتقيد بالتوجيهات المنظمة بالدليل والحرص على عدم تعيين موظفين غير مدربين للقيام بها. وللمزيد من التوجيهات والارشاد والتكوين يمكن الاتصال بقسم نظم المعلوميات الذي يبقى رهن الاشارة.
    ");
        } elseif (optional($audit->responseType)->id == 1) {
            $extraText = $this->rtl("
          ومن خلال ما سبق، ننوه بالعمل الذي يقوم به الموظفون المكلفون بهذه العملية، وحرصكم على التطبيق السليم للإجراءات المضمنة
           )في دليل استعمال التقنية البيومترية ) مدكرة السيد المندوب العام رقم 32 عدد  2211068/80 بتاريخ

وللإشارة، يمكن للمعنيين الاتصال بـقسم نظم المعلوميات الذي يبقى رهن إشارتهم عند الاقتضاء
    ");
        } elseif (optional($audit->responseType)->id == 2) {
            $extraText = $this->rtl("
            ومن خلال ما سبق يتعين عليكم اعطاء تعليماتكم للموظفين المكلفين بهده العملية من احل بدل جهد اضافي حتى يتسنى لهم تطبيق الاجراءات المضمنة بدليل الاستعمال التقنية البيومترية )مدكرة السيد المندوب العام رقم 32 عدد 2211068/80
           ( بتاريخ 29/03/2022   
        لذا ونظرا لأهمية الموضوع في ضبط هوية المعتقل وحالة العود، ندعوكم لإعطاء تعليماتكم لمصالحكم المختصة قصد ايلاء العناية القصوى لهذه العملية والتقيد بالتوجيهات المنظمة بالدليل والحرص على عدم تعيين موظفين غير مدربين للقيام بها. وللمزيد من التوجيهات والارشاد والتكوين يمكن الاتصال بقسم نظم المعلوميات الذي يبقى رهن الاشارة
    ");
        }

        // نعمر placeholder فـ Word
        $templateProcessor->setValue('extra_paragraph', $extraText);


        // etc… (add all values you need from Blade)

        $fileName = 'audit_' . $audit->id . '.docx';
        $path = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
