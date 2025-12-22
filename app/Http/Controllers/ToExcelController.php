<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToExcelController extends Controller
{
    public function exportAssessmentReport($paper_id)
    {
        $institute = request('institute');
        $paper_name = DB::table('papers')->where('id', $paper_id)->value('name');
        $data = DB::table('student_papers as sp')
            ->leftJoin('admissions as a', 'sp.student_id', '=', 'a.student_id')
            ->leftJoin('students as s', 's.id', '=', 'a.student_id')
            ->leftJoin('paper_assessments as pa', 'pa.paper_id', '=', 'sp.paper_id')
            ->leftJoin('assessments as at', 'at.id', '=', 'pa.assessment_id')
            ->leftJoin('student_marks as sm', function ($join) {
                $join->on('sm.paper_assessment_id', '=', 'pa.id')
                    ->on('sm.student_id', '=', 'sp.student_id');
            })
            ->where('sp.paper_id', $paper_id)
            ->select(
                's.first_name as student_name',
                'a.admission_number',
                'at.assessment_type',
                'sm.mark',
            )
            ->get();
        $values = $data->groupBy('admission_number')->map(function ($student_marks) {

            $first = $student_marks->first();

            $row = [
                'admission_number' => $first->admission_number,
                'student_name'     => $first->student_name,
            ];

            $total = 0;

            foreach ($student_marks as $item) {

                if ($item->assessment_type) {
                    $alias = strtolower(str_replace(' ', '_', $item->assessment_type));
                    $row[$alias] = $item->mark ?? 0;
                }

                $total += ($item->mark ?? 0);
            }

            $row['total'] = $total;

            return (object) $row;
        })->values();


        return Excel::download(
            new AssessmentReportExport($values,$paper_name, $institute),
            'assessment_report.xlsx'
        );
    }
}
