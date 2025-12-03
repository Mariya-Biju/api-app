<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgrammeController extends Controller
{
     public function index(){

        $programmes = DB::table('programes')->get();
        return $programmes;
        // $programmes = Programme::all();
        //  return response()->json($programmes);

    }

    public function store(Request $request){

        DB::table('programmes')->insert(['name'=> $request->name,
            'department_id' =>$request->department_id
          ]);
          
      return json_encode(["message" => "programme created"]);
    }

    
}
