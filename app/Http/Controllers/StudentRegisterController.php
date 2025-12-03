<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRegisterRequest;
use App\Models\Pending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentRegisterController extends Controller
{
    public function register(StudentRegisterRequest $request)
    {
        $data = $request->validated();

        $data['status'] = 'pending';
        $data['password'] = bcrypt($data['password']);

        DB::table('pending')->insert($data);
        // Pending::create($data);

        return response()->json([
            'message' => 'Registration submitted. Waiting for faculty approval.'
        ], 201);
    }
}
