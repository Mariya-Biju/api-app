<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = DB::table('departments')->get();
        return $departments;
    }

    public function store(Request $request)
    {

        DB::table('departments')->insert([
            'name' => $request->name,
            'status' => $request->status
        ]);
        return json_encode(["message" => "Department created"]);
    }
}
