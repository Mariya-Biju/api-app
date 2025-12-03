<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Http\Requests\AdmissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;

class AdmissionController extends Controller
{
    public function index()
    {
        return DB::table('admissions')->get();
    }

    public function show($id)
    {
        $admission = DB::table('admissions')->where('id', $id);

        if (!$admission) {
            return "Admission not found";
        }
        return $admission;
    }

    public function store(Request $request)
    {
        // $request->validated();

        $data = [];

        $last_roll_number = DB::table('admissions')
            ->where('batch_id', $request->batch_id)
            ->max('roll_number') ?? 0;

        foreach ($request->students as $student) {

            $student_id = $student['student_id'];
            $exists = DB::table('admissions')
                ->where('student_id', $student_id)
                ->exists();
            if (!$exists) {

                $data[] = [
                    'batch_id' => $request->batch_id,
                    'student_id' => $student['student_id'],
                    'admission_number' => $student['admission_number'],
                    'roll_number' => $last_roll_number + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $last_roll_number++;
            }
        }
        if (!empty($data)) {
            DB::table('admissions')->insert($data);
        }
        return response()->json(['message' => 'Admissions created successfully']);
    }

    public function update(Request $request, $id)
    {
        $admission = DB::table('admissions')->where('id', $id);

        if (!$admission) {
            " Not found";
        }

        DB::table('admissions')->update([
            'admission_number' => $request->admission_number,
            'roll_number'      => $request->roll_number
        ]);
        return response()->json(['message' => 'Admission updated successfully']);
    }

    public function destroy($id)
    {
        $value =  DB::table('admissions')->where('id', $id);
        if (!$value) {
            return "Not found";
        }

        DB::table('admissions')->where('id', $id)->delete();
        return response()->json(['message' => 'Admission deleted successfully']);
    }
}
