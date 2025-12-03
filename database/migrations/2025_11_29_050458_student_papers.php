<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentPapers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
           Schema::create('student_papers',function(Blueprint $table){
                $table->id();
                 $table->unsignedBigInteger('student_id');
                 $table->unsignedBigInteger('paper_id');
                 $table->string('status');
               $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');

                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

                 $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_papers');
    }
}
