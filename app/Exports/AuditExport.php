<?php

namespace App\Exports;

use App\Models\Audit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AuditExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    // Variables utilisées pour filtrer les données exportées
    protected $etab_id;
    protected $from_date;
    protected $to_date;

    /**
     * Constructeur : initialise les filtres
     */
    public function __construct($etab_id = null, $from_date = null, $to_date = null)
    {
        $this->etab_id = $etab_id;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    /**
     * Méthode principale : récupère et structure les données à exporter
     */
    public function collection()
    {
        // Chargement de la relation avec les établissements et les fonctionnaires
        $query = Audit::with(['etablissement', 'fonctionnaires']);

        // Application du filtre par établissement si précisé
        if ($this->etab_id) {
            $query->where('etab_id', $this->etab_id);
        }

        // Application du filtre par date si précisé
        if ($this->from_date && $this->to_date) {
            $query->whereBetween('date_audit', [$this->from_date, $this->to_date]);
        }

        // Retour des données sous forme de collection formatée
        return $query->get()->map(function ($audit) {
            return [
                'المؤسسة السجنية'               => $audit->etablissement->libelle ?? '',
                'الموظف القائم بالبصمة'         => $audit->fonctionnaires->pluck('full_name')->join(', '),
                'تاريخ '                        => $audit->date_audit,
                'عدد المعتقلين'                  => $audit->nb_detenus,
                'عدد البصمات المستقرة'           => $audit->fix_fingerprints,
                'عدد البصمات المحينة '           => $audit->nb_edited_fingerprints,
                'محينة ب 10 اصابع'              => $audit->nb_verified_fingerprints,
                'معاينة عينة دون اخد بصماتهم'    => $audit->nb_without_fingerprints,
            ];
        });
    }

    /**
     *  En-têtes du fichier Excel (titres + sous-titres + colonnes)
     */
    public function headings(): array
    {
        // Formatage des dates pour affichage clair dans le titre
        $from = $this->from_date ? date('Y-m-d', strtotime($this->from_date)) : '';
        $to   = $this->to_date ? date('Y-m-d', strtotime($this->to_date)) : '';

        return [
            // Titre principal du rapport
            ['(وضعية عمليات تحيين البصمات البيومترية التي يقوم بها موضفو قسم نظم المعلوميات بمختلف المؤسسات السجنية للتأكد من مدى تطبيق مدكرة السيد المندوب العام (رقم 32 عدد بتاريخ 29/03/2022'],
            
            // Sous-titre avec la période de filtrage
            ['محينة من ' . $from . ' إلى ' . $to],
            
            // Ligne d'en-têtes des colonnes
            [
                'المؤسسة السجنية',
                'الموظف القائم بالبصمة',
                'التاريخ',
                'عدد المعتقلين',
                'عدد البصمات المستقرة',
                'عدد البصمات المحينة ',
                'محينة ب 10 اصابع',
                'معاينة عينة دون اخد بصماتهم',
            ],
        ];
    }

    /**
     * Événements Excel : mise en forme après la génération du fichier
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Récupération de la feuille Excel
                $sheet = $event->sheet->getDelegate();

                //  Inverser le sens d’écriture (droite → gauche pour l’arabe)
                $sheet->setRightToLeft(true);

                // Mise en forme du titre principal
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Mise en forme du sous-titre (dates)
                $sheet->mergeCells('A2:H2');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setARGB(Color::COLOR_RED);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Mise en forme de la ligne d’en-têtes (A3:H3)
                $sheet->getStyle('A3:H3')->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4CAF50'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // pour Centrer le contenu de certaines colonnes
                $highestRow = $sheet->getHighestRow();
                foreach (['B', 'D', 'E', 'F', 'G'] as $col) {
                    $sheet->getStyle("{$col}4:{$col}{$highestRow}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
