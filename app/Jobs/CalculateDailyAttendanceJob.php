<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CalculateDailyAttendanceSummaryJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle()
    {

        $today = Carbon::today()->toDateString();

        $summary = DB::table('attendances')
            ->select(
                'student_id',
                DB::raw("COUNT(CASE WHEN status = '1' THEN 1 END) as present_count"),
                DB::raw("COUNT(CASE WHEN status = '0' THEN 1 END) as absent_count")
            )
            ->whereDate('date', today())
            ->groupBy('student_id')
            ->get();
        foreach ($summary as $row) {
            DB::table('daily_attendances')->updateOrInsert(
                [
                    'date'       => $today,
                    'student_id' => $row->student_id,
                ],
                [
                    'present_count' => $row->present_count,
                    'absent_count'  => $row->absent_count,
                ]
            );
        }
    }
}
