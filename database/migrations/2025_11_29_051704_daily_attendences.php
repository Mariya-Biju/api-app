<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_attendances', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->unsignedBigInteger('student_id');

            $table->unsignedSmallInteger('present_count')->default(0);
            $table->unsignedSmallInteger('absent_count')->default(0);

            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->unique(['date', 'student_id'], 'daily_attendance_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_attendances');
    }
};
