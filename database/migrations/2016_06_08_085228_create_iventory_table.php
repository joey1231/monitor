<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('program_type')->nullable();
            $table->string('location_id')->nullable();
            $table->integer('on_hand_quantity');
            $table->integer('reserved_quantity');
            $table->integer('available_quantity');
            $table->string('pick_up_now_eligible')->nullable();
            $table->dateTime('sears_inventory');
            $table->integer('item_id');
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
        Schema::drop('inventory');
    }
}
