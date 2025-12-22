<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaperAssessmentController extends Controller
{
    public function index()
    {
        $id = request('paper_id');
        $assessments = DB::table('paper_assessments as ps')
            ->join('assessments as a', 'a.id', '=', 'ps.assessment_id')
            ->join('papers as p', 'p.id', '=', 'ps.paper_id')
            ->select(
                'ps.id as paper_assessment_id',
                'p.name as paper name',
                'a.assessment_type as assessment name',
                'ps.maximum_mark'
            )
            ->where('ps.paper_id', $id)
            ->get();
        return response()->json($assessments);
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'paper_id'      => 'required|integer',
            'assessment_id' => 'required|integer',
            'maximum_mark'  => 'required|integer',
            'scale_id'      => 'required|integer'

        ]);
        $exists =  DB::table('paper_assessments as ps')
            ->join('assessments as a', 'a.id', '=', 'ps.assessment_id')
            ->where('ps.paper_id', $validated['paper_id'])
            ->where('assessment_id', $validated['assessment_id'])
            ->exists();
        if ($exists) {
            return response()->json(["message" => "Assesment type is already exist for this paper"]);
        } else {
            DB::table('paper_assessments')->insert($validated);
            return response()->json(["message" => "Assesment type is added"]);
        }
    }
    public function edit(Request $request, $id)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|integer',
            'maximum_mark'  => 'required|integer',
            'scale_id'      => 'required|integer'
        ]);

        $paper_id = DB::table('paper_assessments as p')->where('p.id', $id)
            ->select('p.paper_id')->first();
        $exists = DB::table('paper_assessments as p')
            ->where('p.assessment_id', $validated['assessment_id'])
            ->where('p.paper_id', $paper_id->paper_id)
            ->where('p.id', '!=', $id)
            ->first();
        if ($exists) {

            return response()->json(['message' => 'Assessment Cant be updated']);
        }
        DB::table('paper_assessments')
            ->where('id', $id)
            ->update($validated);
        return response()->json(['message' => 'Assessment updated successfully']);
    }

    public function destroy($id)
    {


        $exists = DB::table('paper_assessments')->where('id', $id)->exists();
        if ($exists) {
            $values = DB::table('student_marks')
                ->where('paper_assessment_id', $id)
                ->exists();
            if (!$values) {
                DB::table('paper_assessments')->where('id', $id)->delete();
                return response()->json(["message" => "Assesment type  deleted"]);
            }
        }
        return response()->json(["message" => " Paper Assesment type cannot be deleted"]);
    }
}
