<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithStyles
{
    protected $data;
    protected $assessmentTypes;
    protected $count = 0;
    protected $paperName;
    protected $institute;

    public function __construct($data, $assessmentTypes, $paperName,$institute)
    {
        $this->data = $data;
        $this->assessmentTypes = $assessmentTypes;
        $this->paperName = $paperName;
        $this->institute = $institute;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return array_merge(
            ['S.No', 'Admission Number', 'Student Name'],
            $this->assessmentTypes->toArray(),
            ['Total']
        );
    }

    public function map($row): array
    {
        $rowData = [
            ++$this->count,
            $row->admission_number,
            $row->first_name
        ];

        foreach ($this->assessmentTypes as $type) {
            $rowData[] = $row->$type ?? 0;
        }

        $rowData[] = $row->total;

        return $rowData;
    }

    public function startCell(): string
    {
        return 'A4';
    }                                                                                                       

    public function styles(Worksheet $sheet)
    {
        $colCount = count($this->assessmentTypes) + 4;
        $lastCol = chr(64 + $colCount);

        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', $this->institute);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $this->paperName.' - Assessment Report');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 13]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}
