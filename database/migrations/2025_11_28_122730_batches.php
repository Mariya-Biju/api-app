<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Batches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_years',function(Blueprint $table){
                $table->id();
                 $table->string('year');
                 $table->string('status');
                 $table->timestamps();
        });

        Schema::create('batches',function(Blueprint $table){
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('programme_id');
                $table->unsignedBigInteger('academic_year_id');
                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');

                $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
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
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('batches');
    }
}
