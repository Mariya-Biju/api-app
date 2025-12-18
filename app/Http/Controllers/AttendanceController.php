<?php

namespace App\Http\Controllers;

use Egulias\EmailValidator\Exception\UnclosedComment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {

        $date = request('date');
        $attendance_hour = request('hour');
        $batch_id = request('batch_id');
        $results = DB::table('admissions as ad')
            ->leftJoin('attendances as at', function ($join) use ($attendance_hour, $date) {
                $join->on('ad.student_id', '=', 'at.student_id')
                    ->where('at.hour', $attendance_hour)
                    ->where('at.date', $date);
            })
            ->leftJoin('papers as p', function ($join) {
                $join->on('p.id', '=', 'at.programme_id')
                    ->where('at.programme_type', '=', '1');
            })
            ->leftJoin('events as e', function ($join) {
                $join->on('e.id', '=', 'at.programme_id')
                    ->where('at.programme_type', '=', '2');
            })
            ->select(
                'at.programme_type',
                DB::raw("
            CASE WHEN at.programme_type = 1 THEN p.name
            WHEN at.programme_type = 2 THEN e.name
            END AS name
            "),
                'at.marked_by as faculty_id',
                'ad.student_id',
                'at.attendance'
            )->where('ad.batch_id', $batch_id)->get();

        return response()->json([
            'batch_id' => $batch_id,
            'date' => $date,
            'hour' => $attendance_hour,
            'attendance' => $results
        ]);
    }

    public function markAttendance(Request $request)
    {

        $date = $request->date;
        $hour = $request->hour;
        $programmeId = $request->programme_id;
        $programmeType = $request->programme_type;
        $facultyId = $request->faculty_id;
        $students = $request->students;

        $created = 0;
        $updated = 0;

        DB::transaction(function () use (
            $date,
            $hour,
            $programmeId,
            $programmeType,
            $facultyId,
            $students,
            &$created,
            &$updated
        ) {

            foreach ($students as $item) {

                $studentId = $item['student_id'];
                $attendanceValue = $item['attendance'];
                $existing = DB::table('attendances')
                    ->where([
                        'date' => $date,
                        'hour' => $hour,
                        'student_id' => $studentId
                    ])->first();

                if (!$existing) {

                    DB::table('attendances')->insert([
                        'date' => $date,
                        'hour' => $hour,
                        'programme_id' => $programmeId,
                        'programme_type' => $programmeType,
                        'student_id' => $studentId,
                        'attendance' => $attendanceValue,
                        'marked_by' => $facultyId,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    $created++;
                    continue;
                }

                if ($existing->attendance == 0) {

                    if ($attendanceValue == 1) {
                        DB::table('attendances')
                            ->where('id', $existing->id)
                            ->update([
                                'programme_id' => $programmeId,
                                'programme_type' => 2,
                                'attendance' => 1,
                                'updated_at' => Carbon::now()
                            ]);

                        $updated++;
                        continue;
                    }
                }

                if ($existing->marked_by == $facultyId) {
                    DB::table('attendances')
                        ->where('id', $existing->id)
                        ->update([
                            'attendance' => $attendanceValue,
                            'updated_at' => Carbon::now()
                        ]);

                    $updated++;
                    continue;
                }
            }
        });

        return response()->json([
            "created" => $created,
            "updated" => $updated,

        ]);
    }
}
