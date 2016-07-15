<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('package_items', function (Blueprint $table) {
            
            // primary key of this table;
            $table->increments('id');
           
            // info of addtional contact
            $table->integer('product_id');

            $table->integer('item_id');
            $table->text('numbers')->nullable();
           
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
         Schema::drop('package_items');
    }
}
