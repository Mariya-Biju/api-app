<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RangeScales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_scales',function(Blueprint $table){
                $table->id();
                $table->unsignedBigInteger('scale_id');
                $table->integer('mark_starting');
                $table->integer('end_mark');
                $table->string('grade');
                $table->string('grade_point');
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
        Schema::dropIfExists('range_scales');
    }
}
