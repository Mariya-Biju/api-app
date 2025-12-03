<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); 
            $table->date('date');
            $table->unsignedTinyInteger('hour'); 
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('programme_id'); 
            $table->unsignedTinyInteger('programme_type'); 
            $table->unsignedTinyInteger('attendance'); 
            $table->unsignedBigInteger('marked_by');

            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('marked_by')->references('id')->on('faculities')->onDelete('cascade');
            $table->unique(['student_id', 'date', 'hour', 'programme_id', 'programme_type'], 'attendance_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
