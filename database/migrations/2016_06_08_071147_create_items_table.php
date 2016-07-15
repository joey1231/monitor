<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_id')->nullable();
            $table->integer('item_index');
            $table->integer('quantity')->nullable();
            $table->text('description')->nullable();
            $table->decimal('cost',65,2)->nullable();
            $table->decimal('unit_cost_with_discount',65,2);
            $table->decimal('total_cost_with_discount',65,2);
            $table->decimal('cogs',65,2);
            $table->string('actual_cogs')->nullable();
            $table->string('country_of_origin')->nullable();;
            $table->string('item_weight')->nullable();
            $table->string('tax_free')->nullable();
            $table->string('special_product_type')->nullable();
            $table->string('free_shipping');
            $table->string('accounting_code');
            $table->decimal('discount',65,2);
            $table->string('distribution_center_code');
            $table->string('kit');
            $table->string('kit_component');
            $table->string('send_to_sears')->default('Y');
            $table->decimal('quantity_refunded',65,2);
            $table->decimal('total_refunded',65,2);
            $table->string('manufacturer_sku');
            $table->string('merchant_id');
            $table->string('barcode');
            $table->string('order_id');
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
        Schema::drop('items');
    }
}
