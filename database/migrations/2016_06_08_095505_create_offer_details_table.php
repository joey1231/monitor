<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('offer_details', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('discount',65,2);
            $table->decimal('quantity',65,2);
            $table->decimal('price',65,2);
            $table->decimal('ship_price_per_unit',65,2);
            
          
            $table->integer('store_product_item_id');
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
        Schema::drop('offer_details');
    }
}
