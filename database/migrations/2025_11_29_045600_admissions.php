<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Admissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id(); 

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('batch_id');

            $table->string('admission_number')->unique(); 
            $table->string('roll_number'); 

            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');

            // Unique constraints
            $table->unique(['student_id', 'batch_id']);
            $table->unique(['batch_id', 'roll_number']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('admissions');
    }
}
