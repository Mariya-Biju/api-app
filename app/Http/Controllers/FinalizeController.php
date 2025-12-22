<?php

namespace App\Http\Controllers;

use App\Exports\StudentMarksExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FinalizeController extends Controller
{
    public function index()
    {
        $student_id = request('student_id');
        $paper_id = request('paper_id');
        $total = DB::table('paper_assessments')->where('paper_id', $paper_id)->sum('maximum_mark');
        $marks = DB::table('student_marks as sm')
            ->join('paper_assessments as pa', 'pa.id', '=', 'sm.paper_assessment_id')
            ->join('assessments as a', 'a.id', '=', 'pa.assessment_id')
            ->where('pa.paper_id', $paper_id)
            ->where('sm.student_id', $student_id)
            ->sum('sm.mark');

        $percentage = ($marks / $total) * 100;
        $percentage = round($percentage, 2);
        return response()->json([
            'student_id' => $student_id,
            'percentage' => $percentage
        ]);
    }

    public function exportAssessmentReport($id)
    {
        $details = DB::table('papers')
            ->select('name')
            ->where('id', $id)
            ->first();
        $paper_name = $details->name;
        return Excel::download(
            new StudentMarksExport($id,$paper_name),
            'assessment_report.xlsx'
        );
    }
}
