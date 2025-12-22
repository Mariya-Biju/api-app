<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Invoices extends Migration
{
    public function up()
    {
        Schema::create('invoices',function(Blueprint $table){
            $table->id();
            $table->decimal('amount',10, 2);
            $table->unsignedBigInteger('ledger_id');
            $table->integer('status')->default(0);
            $table->date('due_date');
            $table->date('issued_date');
            $table->unsignedBigInteger('user_id');
            $table->integer('user_type');
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
