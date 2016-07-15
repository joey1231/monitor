<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtendedDetailsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('extended_details', function (Blueprint $table) {
            
            // primary key of this table;
            $table->increments('id');
            // 
            $table->integer('transaction_id');
            // info of addtional contact
            $table->text('name')->nullable();;
            // addtiona ifno details of contact
            $table->text('value')->nullable();
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
        Schema::drop('extended_details');
    }
}
