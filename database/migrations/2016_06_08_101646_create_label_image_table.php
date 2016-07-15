<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('label_image', function (Blueprint $table) {
             $table->increments('id');
            $table->string('main_jpg_image')->nullable();
            $table->string('png_image')->nullable();
            $table->string('psd_image')->nullable();
            $table->string('ai_image')->nullable();
            $table->integer('item_id')->nullable();
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
        Schema::drop('label_image');
    }
}
