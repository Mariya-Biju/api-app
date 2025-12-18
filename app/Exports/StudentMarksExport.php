<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentMarksExport implements
    FromCollection,
    WithHeadings

{
    protected $count = 0;
    public $paper_id;
    public $paper_name;
    public function __construct($id, $paper_name)
    {
        $this->paper_id = $id;
        $this->paper_name = $paper_name;
    }

    public function collection()
    {

        $types = DB::table('papers as p')
            ->join('paper_assessments as pa', 'pa.paper_id', '=', 'p.id')
            ->join('assessments as a', 'a.id', '=', 'pa.assessment_id')
            ->select(
                'a.assessment_type',
            )
            ->where('pa.paper_id', $this->paper_id)
            ->get();

        foreach ($types as $type) {
            $data = DB::table('admissions as a')
                ->join('student_marks as sm', 'sm.student_id', '=', 'a.student_id')
                ->join('students as s', 's.id', '=', 'a.student_id')
                ->join('paper_assessments as pa', 'pa.id', '=', 'sm.paper_assessment_id')
                ->join('papers as p', 'pa.paper_id', '=', 'p.id')
                ->join('assessments as at','at.id','=','pa.assessment_id')
                ->select(
                    'a.admission_number',
                    's.first_name',
                    'at.assessment_type',
                    'sm.mark',
                )
                ->where('pa.paper_id', $this->paper_id)
                ->get();
        }
    }

    public function map($row): array
    {
        return [

            ++$this->count,
            $row->admission_number,
            $row->first_name,
            $row->assessment_type,
            $row->total
        ];
    }
    public function headings(): array
    {
        return [
            'Admission Number',
            'Student Name',
            'Assessment type',
            'Mark'
        ];
    }
}
