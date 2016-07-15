<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('transactions', function (Blueprint $table) {
            
            // primary key of this table;
            $table->increments('id');
            // 
            $table->string('gateway');
            // info of addtional contact
            $table->text('successful')->nullable();
            // addtiona ifno details of contact
            $table->text('order_id')->nullable();
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
        Schema::drop('transactions');
    }
}
