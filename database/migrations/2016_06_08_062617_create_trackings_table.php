<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('trackings', function (Blueprint $table) {
            
            $table->increments('id');
            $table->text('number')->nullable();

            $table->text('url')->nullable();
            $table->text('order_id')->nullable();
            $table->text('sku')->nullable();
           
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
         Schema::drop('trackings');
    }
}
