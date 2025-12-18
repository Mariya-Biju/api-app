<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_marks',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('paper_assessment_id');
            $table->unsignedBigInteger('student_id');
            $table->decimal('mark',5,2)->default(0);
            $table->string('grade')->nullable();
            $table->string('grade_point')->default(0);
            $table->timestamps();
            $table->foreign('paper_assessment_id')->references('id')->on('paper_assessments')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_marks');
    }
}
