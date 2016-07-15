<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
         Schema::create('store_products', function (Blueprint $table) {
             $table->increments('id');
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->string('upc')->nullable();
            $table->decimal('cogs',65,2);
            $table->decimal('weight',65,2);
            $table->decimal('dimension_length',65,2);
            $table->decimal('dimension_width',65,2);
            $table->decimal('dimension_height',65,2);
            $table->decimal('dimension_unit',65,2);
            $table->string('features')->nullable();
            $table->string('description')->nullable();
            
            $table->decimal('retail_price',65,2);
            $table->decimal('retail_shipping_price',65,2);
            $table->integer('product_type_id');
            $table->integer('units_per_package');
            $table->integer('package_unit_id');
            $table->integer('weight_unit');
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
        Schema::drop('store_products');
    }
}
