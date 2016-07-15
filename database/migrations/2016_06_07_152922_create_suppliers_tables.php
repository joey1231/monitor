<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('suppliers', function (Blueprint $table) {
            
            // primary key of this table;
            $table->increments('id');
            // 
            $table->text('name')->nullable();
            // info of addtional contact
            $table->text('address')->nullable();
             // info of addtional contact
            $table->text('city')->nullable();
             // info of addtional contact
            $table->text('state')->nullable();
             // info of addtional contact
            $table->text('postal_code')->nullable();
             // info of addtional contact
            $table->text('contact_person')->nullable();
             // info of addtional contact
            $table->text('phone')->nullable();
            // addtiona ifno details of contact
            $table->text('email')->nullable();
            // addtiona ifno details of contact
            $table->text('fax')->nullable();
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
         Schema::drop('suppliers');
    }
}
