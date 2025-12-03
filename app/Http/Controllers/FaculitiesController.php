<?php

namespace App\Http\Controllers;

use App\Events\StudentApproved;
use App\Jobs\SendStudentApprovalEmailJob;
use App\Mail\StudentApprovesMail;
use App\Models\Faculities;
use App\Models\Pending;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class FaculitiesController extends Controller
{

    public function store(Request $request)
    {

        $hashed_password =  Hash::make($request->password);
        $id =  DB::table('faculities')->insertGetId([
            'name' => $request->name,
            'designation' => $request->designation,
            'email' => $request->email,
            'password'  => $hashed_password
        ]);

        DB::table('users')->insert([
            'user_id' => $id,
            'user_type' => 2,
            'email' => $request->email,
            'password'  => $hashed_password,
            'status'    => 1,
        ]);
        return response()->json(["message" => "successfull"]);
    }

    public function approve($student_id)
    {

        $pending_student = DB::table('pending')->where('id', $student_id)->where('status', 0)->first();

        if (!$pending_student) {
            return response()->json(["error" => "No data found"], 404);
        }

        $id =  DB::table('students')->insertGetId([
            'first_name' => $pending_student->first_name,
            'last_name' => $pending_student->last_name

        ]);
        $approvedStudent = DB::table('pending')
            ->where('id', $student_id)->first();
        // $email = DB::table('pending')
        //     ->where('id', $student_id)->value('email');

        DB::table('users')->insert([
            'user_id' => $id,
            'user_type' => 1,
            'email' => $pending_student->email,
            'password'  => $pending_student->password,
            'status'    => 1,
        ]);

        Db::table('pending')->where('id', $pending_student->id)->update([
            'status' => 1
        ]);

        // Mail::to($email)->send(new StudentApprovesMail($approvedStudent));

        event(new StudentApproved($approvedStudent));

        // SendStudentApprovalEmailJob::dispatch($approvedStudent);
        return response()->json(["message" => "Student approved successfully"]);
    }
}
