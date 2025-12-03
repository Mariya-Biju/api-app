<?php

namespace App\Http\Controllers;

use App\Models\paperFaculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaperFacultyController extends Controller
{
    public function index()
    {
        $assigned = DB::table('paper_faculties')->join('faculities', 'paper_faculties.faculty_id', '=', 'faculities.id')
            ->join('papers', 'papers.id', '=', 'paper_faculties.paper_id')
            ->select('papers.name as paper', 'faculities.name as teacher')->get();
        return $assigned;
    }

    public function store(Request $request)
{
        $request->validate([
            'paper_id' => 'required|exists:papers,id',
            'faculties' => 'required|array'
        ]);

        $paper_id = $request->paper_id;
        $data = [];
        $errors = [];

        foreach ($request->faculties as $faculty) {
            $faculty_id = $faculty['faculty_id'] ?? null;
            $paper_id = $faculty['paper_id'] ?? null;

            if (!DB::table('faculities')->where('id', $faculty_id)->exists()) {
                $errors[] = "Faculty  does not exist.";
                continue;
            }

            
            if (!DB::table('papers')->where('id', $paper_id)->exists()) {
                $errors[] = "Paper  does not exist.";
                continue;
            }


            if (DB::table('paper_faculties')->where('paper_id', $paper_id)->where('faculty_id', $faculty_id)->exists()) {
                $errors[] = "The pair already exists.";
                continue;
            }

            $data[] = [
                'paper_id' => $paper_id,
                'faculty_id' => $faculty_id
            ];
        }

        if (!empty($data)) {
            DB::table('paper_faculties')->insert($data);
        }

        return response()->json([
            'message' => 'Assignment completed',
            'errors' => $errors
        ]);
    }

}
