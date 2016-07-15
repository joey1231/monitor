<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {
         Schema::create('orders', function (Blueprint $table) {
            
            $table->increments('id');
            $table->text('type')->nullable();
            $table->dateTime('order_date');
            $table->text('bill_to_company')->nullable();
            $table->text('order_id')->nullable();
            $table->text('bill_to_title')->nullable();
            $table->text('bill_to_first_name')->nullable();
            $table->text('bill_to_last_name')->nullable();
            $table->text('bill_to_address1')->nullable();
            $table->text('bill_to_address2')->nullable();
            $table->text('bill_to_city')->nullable();
            $table->text('bill_to_state')->nullable();
            $table->text('bill_to_zip')->nullable();
            $table->text('bill_to_country')->nullable();
            $table->text('bill_to_country_code')->nullable();
            $table->text('tax_country')->nullable();
            $table->text('ship_to_company')->nullable();
            $table->text('ship_to_title')->nullable();
            $table->text('ship_to_first_name')->nullable();
            $table->text('ship_to_last_name')->nullable();
            $table->text('ship_to_address1')->nullable();
            $table->text('ship_to_address2')->nullable();
            $table->text('ship_to_city')->nullable();
            $table->text('ship_to_state')->nullable();
            $table->text('ship_to_zip')->nullable();
            $table->text('ship_to_country')->nullable();
            $table->text('ship_to_country_code')->nullable();
            $table->text('shipping_method')->nullable();
            $table->text('lift_gate')->nullable();
            $table->text('gift')->nullable();
            $table->text('email')->nullable();
            $table->text('mailing_list')->nullable();
            $table->text('day_phone')->nullable();
            $table->text('evening_phone')->nullable();
            $table->text('fax')->nullable();
            $table->text('card_type')->nullable();
            $table->text('card_number')->nullable();
            $table->text('card_exp_month')->nullable();
            $table->text('card_Exp_year')->nullable();
            $table->text('card_auth_ticket')->nullable();
            $table->text('payment_method')->nullable();
            $table->decimal('weight',65,2);
            $table->decimal('subtotal',65,2);
            $table->decimal('tax_rate',65,2);
            $table->decimal('tax',65,2);
            $table->decimal('shipping_handling_total',65,2);
            $table->decimal('shipping_handling_total_discount',65,2);
            $table->decimal('surcharge_transaction_percentage',65,2);
            
            $table->decimal('surcharge_transaction_fee',65,2);
            $table->decimal('surcharge',65,2);
            $table->decimal('total',65,2);
            $table->text('currency_code')->nullable();
            $table->text('actual_profit_analyzed')->nullable();
            $table->text('actual_profit_review')->nullable();
            $table->text('actual_shipping')->nullable();
            $table->text('actual_other_cost')->nullable();
            $table->text('actual_fullfillment')->nullable();
            $table->text('actual_payment_processing')->nullable();
            $table->text('actual_profit')->nullable();
            $table->decimal('gift_certificate_amount',65,2);

            $table->text('special_instructions')->nullable();
            $table->text('merchant_notes')->nullable();
            $table->decimal('subtotal_discount',65,2);
            $table->text('gift_charge_accounting_code')->nullable();
            $table->text('gift_wrap_accounting_code')->nullable();
            $table->text('screen_branding_theme_code')->nullable();
            $table->text('insureship_available')->nullable();
            $table->text('insureship_separate')->nullable();
            $table->text('insureship_wanted')->nullable();
            $table->text('has_customer_profile')->nullable();
            $table->text('customer_ip_address')->nullable();
            $table->text('upsell_path_code')->nullable();
            $table->text('test_order')->nullable();
            $table->text('searstat')->nullable();
            $table->string('sent_to_sears')->default('N');
            $table->dateTime('sent_to_sears_date');
            $table->string('sent_to_ac')->default('N');
            $table->text('sears_response')->nullable();
            $table->text('sent_to_sears_by')->nullable();
            $table->text('sent_to_ac_by')->nullable();
            $table->dateTime('sent_to_ac_date');
            $table->text('current_stage')->nullable();
            $table->text('payment_status')->nullable();
            $table->dateTime('date_modified');
            $table->decimal('subtotal_discount_refunded',65,2);
            $table->decimal('subtotal_refunded',65,2);
            $table->decimal('tax_refunded',65,2);
            $table->decimal('shipping_handling_refunded',65,2);
            $table->decimal('buysafe_refunded',65,2);
            $table->decimal('total_refunded',65,2);
            $table->text('refund_by_user')->nullable();
            $table->text('refund_dts')->nullable();
            $table->text('order_stage')->nullable();
            $table->text('order_issue')->nullable();
            $table->text('processing_status')->nullable();
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
         Schema::drop('orders');
    }
}
