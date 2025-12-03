<?php

namespace App\Http\Controllers;

use App\Models\PaperStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaperStudentController extends Controller
{
    public function index()
    {
        $assigned = DB::table('student_papers')
            ->join('students', 'student_papers.student_id', '=', 'student.id')
            ->join('papers', 'papers.id', '=', 'student_papers.paper_id')
            ->select('papers.name as paper', 'students.name as name')->get();
        return $assigned;
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'details'    => 'required|array'
        ]);

        $student_id = $request->student_id;

        $data = [];

        foreach ($request->details as $item) {
            $data[] = [
                'student_id' => $student_id,
                'paper_id'   => $item['paper_id'],
                'status'     => $item['status'],
            ];
        }

        DB::table('student_papers')->insert($data);

        return ("Paper assigned to student");
    }

    public function destroy($id)
    {
        $paper_id =  DB::table('student_papers')->where('id', $id);
        if (!$paper_id) return response()->json(['message' => 'Not found'], 404);

        DB::table('student_papers')->where('id', $id)->delete();
        return "message' Deleted";
    }
}
