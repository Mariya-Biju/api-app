<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(){
         return DB::table('events')->get();

    }
    public function store(Request $request){
        DB::table('events')->insert([
            'name'=> $request->name
        ]);
        return " Event created";

    }

    public function destroy($id){
        $event = DB::table('events')->where('id',$id)->first();
        if(!$event){
           return "Event not found";

        }

        DB::table('events')->where('id', $id)->delete();
      return "Event deleted successfully";

    }

    public function update(Request $request ,$id){
        $event = DB::table('events')->where('id',$id)->first();

        if (!$event) {
            return "Event not found";
        }
         DB::table('events')->update([
            'name' => $request->name
         ]);
        return "Event updated successfully";
        
    }
}
