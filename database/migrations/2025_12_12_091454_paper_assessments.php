<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaperAssessments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paper_assessments',function(Blueprint $table){
                $table->id();
                $table->unsignedBigInteger('paper_id');
                $table->unsignedBigInteger('assessment_id');
                $table->unsignedBigInteger('scale_id');
                $table->integer('maximum_mark');
                $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');
                $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
                $table->foreign('scale_id')->references('id')->on('scales')->onDelete('cascade');
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
        Schema::dropIfExists('paper_assessments');
    }
}
