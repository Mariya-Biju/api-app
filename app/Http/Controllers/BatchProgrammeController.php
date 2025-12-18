<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchProgrammeController extends Controller
{
    public function index()
    {

        $programme_id = request('programme_id');
        $date  = request('date');

        $attendance = DB::table('batches as b')
            ->join('admissions as ad', 'ad.batch_id', '=', 'b.id')
            ->leftJoin('attendances as at', function ($join) use ($date) {
                $join->on('at.student_id', '=', 'ad.student_id')
                    ->where('at.date', '=', $date);
            })
            ->where('b.programme_id', $programme_id)
            ->select(
                'b.id as batch_id',
                'b.name as batch_name',
                DB::raw("SUM(CASE WHEN at.attendance = '1' THEN 1 ELSE 0 END) AS present_count"),
                DB::raw("SUM(CASE WHEN at.attendance = '0' THEN 1 ELSE 0 END) AS absent_count"),
                DB::raw("SUM(CASE WHEN at.attendance = '2' THEN 1 ELSE 0 END) AS late_count")
            )
            ->groupBy('b.id', 'b.name')
            ->get();

        return response()->json([
            'programme_id' => $programme_id,
            'date' => $date,
            'batches' => $attendance
        ]);
    }

    public function getStudents()
    {
        $programme_id = request('programme_id');

        $details = DB::table('admissions as ad')
            ->join('students as st', 'ad.student_id', '=', 'st.id')
            ->join('batches as b', 'ad.batch_id', '=', 'b.id')
            ->join('student_papers as sp', 'st.id', '=', 'sp.student_id')
            ->join('papers as p', 'p.id', '=', 'sp.paper_id')
            ->where('b.programme_id', $programme_id)
            ->select('b.id as batch_id', 'b.name as batch_name', 'ad.student_id', 'st.first_name', 'sp.paper_id','p.name as paper_name')
            ->orderBy('b.id')
            ->orderBy('st.id')
            ->get();
        $papers = [];
        $batches = [];
        $students = [];
        $batch_index = 0;
        $student_index = 0;
        $previous_batch_id = null;
        $previous_student_id = null;
        foreach ($details as $data) {
            $current_batch_id = $data->batch_id;
            $current_student_id = $data->student_id;
            if ($previous_batch_id == $current_batch_id) {

                if ($previous_student_id == $current_student_id) {
                     $papers[] = [
                        'papers' => $data->paper_name
                     ];
                    $students[$student_index-1]['papers'] = $papers;
                } else {
                    $papers = [];
                    $papers[] = [
                        'papers' => $data->paper_name
                     ];
                    $students[$student_index] = [
                        'student_id' => $data->student_id,
                        'first_name' => $data->first_name,
                        'papers' => $papers
                    ];
                    $student_index++;
                }
                 $batches[$batch_index - 1]['students'] = $students;

            } else {

                $students = [];
                $papers = [];

                $papers[] = [
                    'papers' => $data->paper_name
                ];
                $students[$student_index] = [
                    'student_id' => $data->student_id,
                    'first_name' => $data->first_name,
                    'papers' => $papers

                ];
                $student_index++;
                $batches[$batch_index] = [
                    'batch_id' => $data->batch_id,
                    'batch_name' => $data->batch_name,
                    'students' => $students
                ];
                $batch_index++;
            }
            $previous_batch_id = $current_batch_id;
            $previous_student_id = $current_student_id;
        }

        return response()->json([
            'programme_id' => $programme_id,
            'batches' => $batches
        ]);
    }
}

