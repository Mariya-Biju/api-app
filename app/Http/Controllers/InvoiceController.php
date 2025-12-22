<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ledger_id' => 'required|integer',
            'due_date'  => 'required|date',
            'amount'    => 'required|numeric',
            'user_id'   => 'required|integer',
            'user_type' => 'required'
        ]);

        $exist = DB::table('invoices')
                    ->where('ledger_id', $validated['ledger_id'])
                    ->where('user_id', $validated['user_id'])
                    ->exists();

        if ($exist) {
            return response()->json([
                'message' => 'Invoice already exists for this ledger and user'
            ], 409);
        }

        DB::table('invoices')->insert([
            'amount'      => $validated['amount'],
            'ledger_id'   => $validated['ledger_id'],
            'status'      => 0,
            'due_date'    => $validated['due_date'],
            'issued_date' => Carbon::now()->toDateString(),
            'user_id'     => $validated['user_id'],
            'user_type'   => $validated['user_type']
        ]);

        return response()->json([
            'message' => 'Invoice created'
        ], 201); 
    }
}
