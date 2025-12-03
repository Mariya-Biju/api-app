<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Papers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->timestamps();
        });
        Schema::create('paper_faculties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paper_id');
            $table->unsignedBigInteger('faculty_id');
            $table->foreign('paper_id')->references('id')->on('papers')->onDelete('cascade');

            $table->foreign('faculty_id')->references('id')->on('faculities')->onDelete('cascade');

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
        Schema::dropIfExists('papers');
        Schema::dropIfExists('paper_faculties');
    }
}
