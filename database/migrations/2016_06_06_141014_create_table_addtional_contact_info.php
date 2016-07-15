<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAddtionalContactInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('additional_contact_info', function (Blueprint $table) {
            
            // primary key of this table;
            $table->increments('id');
            // 
            $table->integer('id_supplier');
            // info of addtional contact
            $table->text('info_name')->nullable();;
            // addtiona ifno details of contact
            $table->text('info_details')->nullable();;
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
        Schema::drop('additional_contact_info');
    }
}
