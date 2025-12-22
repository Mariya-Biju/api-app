<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithCustomStartCell
{
    protected $data;
    protected $paperName;
    protected $institute;
    protected $assessmentColumns;

    public function __construct($data, $paperName, $institute)
    {
        $this->data = $data;
        $this->paperName = $paperName;
        $this->institute = $institute;

        $this->assessmentColumns = collect($data)
            ->flatMap(fn ($row) => array_keys((array) $row))
            ->unique()
            ->reject(fn ($key) => in_array($key, ['admission_number', 'student_name', 'total']))
            ->values()
            ->toArray();
    }

    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return array_merge(
            ['Admission Number', 'Student Name'],
            array_map(fn ($c) => ucwords(str_replace('_', ' ', $c)), $this->assessmentColumns),
            ['Total']
        );
    }

    public function map($row): array
    {
        $mapped = [
            $row->admission_number,
            $row->student_name,
        ];

        foreach ($this->assessmentColumns as $col) {
            $mapped[] = $row->$col ?? 0;
        }

        $mapped[] = $row->total;

        return $mapped;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', $this->institute);
        $sheet->setCellValue('A2', $this->paperName . ' - Assessment Report');

        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['bold' => true, 'size' => 13]],
            4 => ['font' => ['bold' => true]],
        ];
    }
}
