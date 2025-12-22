<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentMarkController extends Controller
{
    public function index()
    {
        $paper_assessment_id = request('paper_assessment_id');
        $data = DB::table('student_marks as sm')
            ->join('paper_assessments as ps', 'ps.id', '=', 'sm.paper_assessment_id')
            ->join('students as s', 's.id', '=', 'sm.student_id')
            ->join('assessments as a', 'a.id', '=', 'ps.assessment_id')
            ->select(
                'sm.paper_assessment_id',
                'a.assessment_type',
                'sm.student_id',
                's.first_name',
                'sm.mark',
                'sm.grade',
                'sm.grade_point'
            )
            ->where('sm.paper_assessment_id', $paper_assessment_id)
            ->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'paper_assessment_id' => 'required|integer',
            'students' => 'required|array',
            'students.*.student_id' => 'required|integer',
            'students.*.mark' => 'required|numeric|min:0',
        ]);
        $paper_assessment_id = $validated['paper_assessment_id'];
        $values = DB::table('paper_assessments')
            ->where('id', $paper_assessment_id)->select('maximum_mark', 'scale_id', 'paper_id')
            ->first();

        $scale_id = $values->scale_id;
        $maximum_mark = $values->maximum_mark;
        $paper_id = $values->paper_id;

        $grades = DB::table('range_scales as rs')
            ->select(
                'rs.start_mark',
                'rs.end_mark',
                'rs.grade',
                'rs.grade_point'
            )
            ->where('rs.scale_id', $scale_id)
            ->get();
        $id =  DB::table('student_papers')->where('paper_id', $paper_id)
            ->select('student_id')
            ->get();
        foreach ($request->students as $student) {

            $check = $id
                ->where('student_id', $student['student_id'])->first();

            if (is_null($check)) {
                continue;
            }
            $data = [];
            $mark = $student['mark'];
            $percentage = ($mark / $maximum_mark) * 100;
            $percentage = round($percentage, 2);
            $filtered = $grades
                ->where('start_mark', '<=', $percentage)
                ->where('end_mark', '>=', $percentage)
                ->first();

            $grade = $filtered ? $filtered->grade : null;
            $grade_point = $filtered ? $filtered->grade_point : null;

            $student_id = $student['student_id'];
            $exist = DB::table('student_marks')->where('student_id', $student_id)
                ->where('paper_assessment_id', $paper_assessment_id)->exists();
            if (!$exist) {
                $data[] = [
                    'paper_assessment_id' => $paper_assessment_id,
                    'student_id' => $student_id,
                    'mark' => $mark,
                    'grade' => $grade,
                    'grade_point' => $grade_point,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now()
                ];
                DB::table('student_marks')->insert($data);
            } else {
                DB::table('student_marks')
                    ->where('student_id', $student_id)
                    ->where('paper_assessment_id', $paper_assessment_id)
                    ->update([
                        'mark' => $mark,
                        'grade' => $grade,
                        'grade_point' => $grade_point,
                        'updated_at' => Carbon::now()

                    ]);
            }
        }
        return response()->json(["message" => "Mark Added"]);
    }

    public function destroy($id)
    {
        $data =  DB::table('student_marks')->where('id', $id)->first();
        if (is_null($data)) {

            return response()->json(["message" => "Details not found"]);
        }
        DB::table('student_marks')->where('id', $id)->delete();
        return response()->json(["message" => " Deleted"]);
    }
}
