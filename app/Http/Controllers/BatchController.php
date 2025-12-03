<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
       public function index(){
        $batches = DB::table('batches')->get();
        return $batches;

    }

    public function store(Request $request){

        DB::table('batches')->insert([
            'name'=> $request->name,
            'programme_id'=> $request->programme_id,
            'academic_year_id' => $request->academic_year_id
        ]);
      return "Batches  created";
    }
}
