<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaperController extends Controller
{
    public function index()
    {
        return DB::table('papers')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'total_mark' =>'required'
        ]);

         DB::table('papers')->insert([
            'name' => $request->name,
            'total_mark' =>$request->total_mark
        ]);

        return response()->json(['message' => 'Paper created']);
    }

    public function update(Request $request, $id)
    {
        $paper = DB::table('papers')->where('id', $id)->first();
        if (!$paper) return (['message' => 'Not found']);

        DB::table('papers')->update([
            'name' => $request->name,
            'total_mark' =>$request->total_mark
        ]);
        return response()->json(['message' => 'Paper updated']);
    }

    public function destroy($id)
    {
        $paper = DB::table('papers')->where('id', $id)->first();
        if (!$paper) return response()->json(['message' => 'Not found'], 404);

        DB::table('papers')->where('id', $id)->delete();

        return response()->json(['message' => 'Paper deleted']);
    }
}
