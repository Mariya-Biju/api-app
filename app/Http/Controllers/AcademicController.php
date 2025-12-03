<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicController extends Controller
{
      public function index(){
        $academics = DB::table('academic_years')->get();
        return $academics;

    }

    public function store(Request $request){

       DB::table('academic_years')->insert([
            'year'=> $request->year,
            'status' =>$request->status
        ]);
      return json_encode(["message" => "Year  created"]);
    }
}
