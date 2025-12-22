<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListPaymentController extends Controller
{
    public function list($id){

        $user_id = $id;
        $from_date = request('from_date');
        $to_date = request('to_date');
        $invoices = DB::table('invoices as i')
            ->leftJoin('remittances as r','i.id','=','r.invoice_id')
            ->where('i.user_id',$user_id)
            ->whereBetween('i.issued_date', [$from_date, $to_date])
            ->select(
                'i.id as invoice_id',
                'i.amount as total_amount',
                DB::raw('COALESCE(sum(r.amount_paid),0) as paid'),
                DB::raw('i.amount - COALESCE(SUM(r.amount_paid), 0) as balance')
            )
            ->groupby('i.id', 'i.amount')
            ->get();
        return response()->json([
            'user' =>$user_id,
            'invoices' =>$invoices
        ]);
    }

}
