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
use Mpdf\Mpdf;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Affiche la liste complète des audits
     */
    public function index()
    {
        $audits = Audit::with(['etablissement', 'fonctionnaires'])->get();
        return view('user.audits.index', compact('audits'));
    }

    /**
     * Page du tableau de bord général
     */
    public function dashboard()
    {
        $audits = Audit::all();
        return view('dashboard', compact('audits'));
    }

    /**
     * Affiche le formulaire de filtrage avant export
     */
    public function showExportForm()
    {
        $etablissements = Etablissements::all();
        return view('user.audits.export-form', compact('etablissements'));
    }

    /**
     * Filtre les audits selon les critères choisis
     */
    public function filter(Request $request)
    {
        $etablissements = Etablissements::all();
        $query = Audit::with(['etablissement', 'fonctionnaires']);

        if ($request->etab_id) {
            $query->where('etab_id', $request->etab_id);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date_audit', [$request->from_date, $request->to_date]);
        }

        $audits = $query->get();

        return view('user.audits.export-form', compact('etablissements', 'audits'));
    }

    /**
     *  Exportation Excel des audits selon les filtres
     */
    public function export(Request $request)
    {
        return Excel::download(
            new AuditExport($request->etab_id, $request->from_date, $request->to_date),
            'audits.xlsx'
        );
    }

    /**
     *  Formulaire de création d’un audit
     */
    public function create()
    {
        $fonctionnaires = Fonctionnaire::where('is_deleted', false)->get();
        $etablissements = Etablissements::all();
        $responseTypes = ResponseType::all();

        return view('user.audits.create', compact('responseTypes', 'etablissements', 'fonctionnaires'));
    }

    /**
     *  Formulaire d’édition d’un audit existant
     */
    public function edit($id)
    {
        $audit = Audit::with('fonctionnaires')->findOrFail($id);
        $etablissements = Etablissements::all();
        $fonctionnaires = Fonctionnaire::where('is_deleted', false)->get();

        // Liste des fonctionnaires déjà liés à cet audit
        $selectedFonctionnaires = $audit->fonctionnaires->map(function ($f) {
            return [
                'id' => $f->id,
                'full_name' => $f->full_name,
            ];
        })->values()->toArray();

        $responseTypes = ResponseType::all();

        return view('user.audits.edit', compact('audit', 'responseTypes', 'etablissements', 'fonctionnaires', 'selectedFonctionnaires'));
    }

    /**
     *  Mise à jour d’un audit existant
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'etab_id' => 'required|exists:etablissements,id',
            'fonctionnaires' => 'required|array',
            'fonctionnaires.*' => 'exists:fonctionnaires,id',
            'date_audit' => 'required|date',
            'nb_detenus' => 'required|integer|min:0',
            'fix_fingerprints' => 'required|integer|min:0',
            'nb_edited_fingerprints' => 'required|integer|min:0',
            'nb_verified_fingerprints' => 'required|integer|min:0',
            'nb_without_fingerprints' => 'required|integer|min:0',
        ]);

        $audit = Audit::findOrFail($id);

        // Mise à jour des champs principaux
        $audit->update([
            'etab_id' => $request->etab_id,
            'date_audit' => $request->date_audit,
            'nb_detenus' => $request->nb_detenus,
            'fix_fingerprints' => $request->fix_fingerprints,
            'nb_edited_fingerprints' => $request->nb_edited_fingerprints,
            'nb_verified_fingerprints' => $request->nb_verified_fingerprints,
            'nb_without_fingerprints' => $request->nb_without_fingerprints,
        ]);

        // Mise à jour du type de réponse (facultatif)
        $validated = $request->validate([
            'response_type_id' => 'nullable|exists:response_types,id',
        ]);
        $audit->update($validated);

        // Synchronisation des fonctionnaires associés
        $audit->fonctionnaires()->sync($request->fonctionnaires ?? []);

        return redirect()->route('audits.index')->with('success', 'تم تعديل عملية التحيين بنجاح');
    }

    /**
     *  Enregistrement d’un nouvel audit
     */
    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $validated = $request->validate([
            'etab_id' => 'required|exists:etablissements,id',
            'fonctionnaires' => 'required|array',
            'fonctionnaires.*' => 'exists:fonctionnaires,id',
            'date_audit' => 'required|date',
            'nb_detenus' => 'required|integer|min:0',
            'fix_fingerprints' => 'nullable|integer|min:0',
            'nb_edited_fingerprints' => 'required|integer|min:0',
            'nb_verified_fingerprints' => 'required|integer|min:0',
            'nb_without_fingerprints' => 'required|integer|min:0',
            'response_type_id' => 'nullable|exists:response_types,id',
        ]);

        // Création du nouvel audit
        $audit = Audit::create([
            'etab_id' => $request->etab_id,
            'date_audit' => $request->date_audit,
            'nb_detenus' => $request->nb_detenus,
            'fix_fingerprints' => $request->fix_fingerprints,
            'nb_edited_fingerprints' => $request->nb_edited_fingerprints,
            'nb_verified_fingerprints' => $request->nb_verified_fingerprints,
            'nb_without_fingerprints' => $request->nb_without_fingerprints,
            'response_type_id' => $request->response_type_id,
        ]);

        // Liaison avec les fonctionnaires
        $audit->fonctionnaires()->sync($request->fonctionnaires ?? []);

        return redirect()->route('audits.index')->with('success', 'تمت إضافة عملية التحيين بنجاح');
    }

    /**
     *  Génération et affichage du PDF d’un audit
     */
    public function generatePdf($id)
    {
        $audit = Audit::with('etablissement')->findOrFail($id);
        $etablissement = $audit->etablissement ? $audit->etablissement->libelle : 'غير محدد';
        $dateAudit = Carbon::parse($audit->date_audit)->format('Y/m/d');

        // Configuration du moteur mPDF
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
            'fontdata' => $fontData + ['amiri' => ['R' => 'Amiri-Regular.ttf']],
            'default_font' => 'amiri',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Calcul du pourcentage des empreintes complètes
        $fix_fingerprints = $audit->fix_fingerprints;
        $fullFingerPercent = 0;
        $totalFingers = $audit->nb_verified_fingerprints + $audit->nb_edited_fingerprints + $fix_fingerprints;

        if ($totalFingers > 0) {
            $fullFingerPercent = ($audit->nb_verified_fingerprints / $totalFingers) * 100;
        }

        // Récupération du type de réponse
        $audit = Audit::with('responseType')->findOrFail($id);
        $responseType = $audit->responseType;

        // Chargement de la vue PDF
        $html = view('user.audits.pdf', compact('audit', 'etablissement', 'dateAudit', 'fix_fingerprints', 'fullFingerPercent', 'responseType'))->render();

        $mpdf->WriteHTML($html);
        return $mpdf->Output('audit_' . $audit->id . '.pdf', 'I');
    }

    /**
     *  Helper RTL (texte arabe dans les fichiers Word)
     */
    private function rtl($text)
    {
        return "\u{202B}" . $text . "\u{202C}";
    }

    /**
     * Génération d’un rapport Word à partir d’un modèle DOCX
     */
    public function generateWordFromTemplate($id)
    {
        $audit = Audit::with('etablissement', 'responseType')->findOrFail($id);
        $etablissement = $audit->etablissement ? $audit->etablissement->libelle : 'غير محدد';
        $dateAudit = Carbon::parse($audit->date_audit)->format('Y/m/d');

        // Calcul du pourcentage des empreintes
        $fix_fingerprints = $audit->fix_fingerprints;
        $fullFingerPercent = 0;
        $totalFingers = $audit->nb_verified_fingerprints + $audit->nb_edited_fingerprints + $fix_fingerprints;
        if ($totalFingers > 0) {
            $fullFingerPercent = ($audit->nb_verified_fingerprints / $totalFingers) * 100;
        }

        // Date actuelle en arabe
        $today = now()->locale('ar')->translatedFormat('d F Y');
        $today = htmlspecialchars($today, ENT_QUOTES, 'UTF-8');

        // Chargement du modèle Word
        $templatePath = storage_path('app/templates/audit.docx');
        $templateProcessor = new TemplateProcessor($templatePath);

        // Remplacement des variables dans le modèle
        $templateProcessor->setValue('etablissement', $etablissement);
        $templateProcessor->setValue('dateAudit', $dateAudit);
        $templateProcessor->setValue('nb_verified', $audit->nb_verified_fingerprints);
        $templateProcessor->setValue('percent', number_format($fullFingerPercent, 2) . '%');
        $templateProcessor->setValue('fix_fingerprints', $fix_fingerprints);
        $templateProcessor->setValue('today', $today);

        // Lignes dynamiques selon les valeurs
        if ($audit->nb_edited_fingerprints > 0) {
            $templateProcessor->setValue(
                'line1',
                $this->rtl("{$audit->nb_edited_fingerprints} معتقلين ارتفع عدد الاصابع الماخودة لبصماتهم عن السابق بالرغم من انه لم يصل الى 10 اصابع.")
            );
        } else {
            $templateProcessor->setValue('line1', '');
        }

        if ($fix_fingerprints > 0) {
            $templateProcessor->setValue(
                'line2',
                $this->rtl("{$fix_fingerprints} معتقلين استقرت وضعية بصماتهم.")
            );
        } else {
            $templateProcessor->setValue('line2', '');
        }

        if ($audit->nb_without_fingerprints > 0) {
            $templateProcessor->setValue(
                'line3',
                $this->rtl("{$audit->nb_without_fingerprints} معتقلين تمت معاينتهم عينيا دون اللجوء الى لاخد بصماتهم )بتر، إعاقة، تشوه (....")
            );
        } else {
            $templateProcessor->setValue('line3', '');
        }

        // Paragraphe additionnel selon le type de réponse
        $extraText = '';

        if (optional($audit->responseType)->id == 3) {
            $extraText = $this->rtl("..."); // (texte complet inchangé)
        } elseif (optional($audit->responseType)->id == 1) {
            $extraText = $this->rtl("...");
        } elseif (optional($audit->responseType)->id == 2) {
            $extraText = $this->rtl("...");
        }

        $templateProcessor->setValue('extra_paragraph', $extraText);

        // Sauvegarde et téléchargement du fichier généré
        $fileName = 'audit_' . $audit->id . '.docx';
        $path = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
}
