<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function manualPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payments' => 'required|array',
            'payments.*.invoice_id' => 'required|integer',
            'payments.*.amount' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();

        try {
            $invoice_ids = collect($request->payments)->pluck('invoice_id');

            $invoices = DB::table('invoices')
                ->whereIn('id', $invoice_ids)
                ->select('id', 'user_id', 'user_type', 'amount', 'status')
                ->get();

            if ($invoices->isEmpty()) {
                throw new \Exception('Invalid invoices');
            }

            $first = $invoices->first();

            $invalidUser = $invoices->first(function ($inv) use ($first) {
                return $inv->user_id !== $first->user_id
                    || $inv->user_type !== $first->user_type;
            });

            if ($invalidUser) {
                throw new \Exception('All invoices must belong to the same user');
            }

            foreach ($request->payments as $pay) {
                $invoice = $invoices->firstWhere('id', $pay['invoice_id']);

                if (!$invoice || $invoice->status != 0) {
                    continue;
                }

                $paidAmount = DB::table('remittances')
                    ->where('invoice_id', $invoice->id)
                    ->sum('amount_paid');

                $payable = $invoice->amount - $paidAmount;

                if ($pay['amount'] > $payable) {
                    throw new \Exception(
                        "Payment exceeds payable amount for the invoice"
                    );
                }
            }

            $total = collect($request->payments)->sum('amount');
            $challanId = DB::table('challans')->insertGetId([
                'user_id'   => $first->user_id,
                'user_type' => $first->user_type,
                'amount'    => $total,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($request->payments as $pay) {
                $invoice = $invoices->firstWhere('id', $pay['invoice_id']);

                if (!$invoice || $invoice->status != 0) {
                    throw new \Exception(
                        "Invoice id  {$invoice->id} - Amount is fully paid"
                    );
                }

                $paidAmount = DB::table('remittances')
                    ->where('invoice_id', $invoice->id)
                    ->sum('amount_paid');

                $payable = $invoice->amount - $paidAmount;
                $balance = $payable - $pay['amount'];

                DB::table('remittances')->insert([
                    'challan_id'   => $challanId,
                    'invoice_id'   => $invoice->id,
                    'amount_paid'  => $pay['amount'],
                    'payment_date' => Carbon::now()->toDateString(),
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now(),
                ]);

                if ($balance == 0) {
                    DB::table('invoices')
                        ->where('id', $invoice->id)
                        ->update(['status' => 1]);
                }
            }
            DB::commit();
            return response()->json([
                'message' => 'Successful'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
