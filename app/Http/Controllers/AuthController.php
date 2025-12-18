<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function facultyLogin(Request $request)
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

        $user = User::find($record->user_id);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
}
