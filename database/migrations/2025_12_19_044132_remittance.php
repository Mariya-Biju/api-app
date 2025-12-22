<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Remittance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittances',function(Blueprint $table){
                $table->id();
                $table->unsignedBigInteger('challan_id');
                $table->unsignedBigInteger('invoice_id');
                $table->decimal('amount_paid',10,2);
                $table->date('payment_date')->nullable();
                $table->foreign('challan_id')->references('id')->on('challans')->onDelete('cascade');
                $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
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
        Schema::dropIfExists('remittances');
    }
}
