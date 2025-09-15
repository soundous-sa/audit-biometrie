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
    protected $etab_id;
    protected $from_date;
    protected $to_date;

    public function __construct($etab_id = null, $from_date = null, $to_date = null)
    {
        $this->etab_id = $etab_id;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function collection()
    {
        $query = Audit::with(['etablissement', 'fonctionnaires']);

        if ($this->etab_id) {
            $query->where('etab_id', $this->etab_id);
        }

        if ($this->from_date && $this->to_date) {
            $query->whereBetween('date_audit', [$this->from_date, $this->to_date]);
        }

        return $query->get()->map(function ($audit) {
            return [
                'المؤسسة السجنية'       => $audit->etablissement->libelle ?? '',
                'الموظف القائم بالبصمة' => $audit->fonctionnaires->pluck('full_name')->join(', '),
                'تاريخ '                 => $audit->date_audit,
                'عدد المعتقلين'           => $audit->nb_detenus,
                'عدد البصمات المحينة '   => $audit->nb_edited_fingerprints,
                'محينة ب 10 اصابع'       => $audit->nb_verified_fingerprints,
                'معاينة عينة دون اخد بصماتهم' => $audit->nb_without_fingerprints,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['(وضعية عمليات تحيين البصمات البيومترية التي يقوم بها موضفو قسم نظم المعلوميات بمختلف المؤسسات السجنية للتأكد من مدى تطبيق مدكرة السيد المندوب العام (رقم 32 عدد بتاريخ 29/03/2022'], 
             ['محينة من ' . ($this->from_date ?? '') . ' إلى ' . ($this->to_date ?? '')], // السطر الثاني مع التواريخ 
            [
                'المؤسسة السجنية',
                'الموظف القائم بالبصمة',
                'التاريخ',
                'عدد المعتقلين',
                'عدد البصمات المحينة ',
                'محينة ب 10 اصابع',
                'معاينة عينة دون اخد بصماتهم',
            ]
        ];
    }

    public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();

            // RTL
            $sheet->setRightToLeft(true);

            // Fusionner la première ligne (titre)
            $sheet->mergeCells('A1:G1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Ligne des dates (ligne 2)
             // 🔹 Fusionner السطر الثاني "محينة من ... إلى ..."
                $sheet->mergeCells('A2:G2');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->getColor()->setARGB(Color::COLOR_RED);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Styles pour les en-têtes (3ème ligne)
            $sheet->getStyle('A3:G3')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '000000'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            // Centrer certaines colonnes (2ème et 4,5,6,7)
            $highestRow = $sheet->getHighestRow();
            foreach (['B', 'D', 'E', 'F', 'G'] as $col) {
                $sheet->getStyle("{$col}4:{$col}{$highestRow}")
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        },
    ];
}}