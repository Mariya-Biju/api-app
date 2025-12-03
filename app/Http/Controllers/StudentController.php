<?php

namespace App\Http\Controllers;

use App\Models\Pending;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $record = DB::table('users')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (! Hash::check($request->password, $record->password)) {
            return "incorect password";
        }

        return "welcome ";
    }
}
