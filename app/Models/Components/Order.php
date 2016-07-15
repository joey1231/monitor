<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $table='orders';
    protected $fillable = [
    	// info of addtional contact
            'type',
            'order_date',
            'order_id',
            'bill_to_company',
            'bill_to_title',
            'bill_to_first_name',
            'bill_to_last_name',
            'bill_to_address1',
           'bill_to_address2',
            'bill_to_city',
            'bill_to_state',
            'bill_to_zip',
            'bill_to_country',
            'bill_to_country_code',
            'tax_country',
            'ship_to_company',
            'ship_to_title',
            'ship_to_first_name',
            'ship_to_last_name',
            'ship_to_address1',
            'ship_to_address2',
            'ship_to_city',
            'ship_to_state',
            'ship_to_zip',
            'ship_to_country',
            'ship_to_country_code',
            'shipping_method',
            'lift_gate',
            'gift',
            'email',
            'mailing_list',
            'day_phone',
            'evening_phone',
            'fax',
            'card_type',
            'card_number',
            'card_exp_month',
            'card_Exp_year',
            'card_auth_ticket',
            'payment_method',
            'weight',
            'subtotal',
            'tax_rate',
            'tax',
            'shipping_handling_total',
            'shipping_handling_total_discount',
            'surcharge_transaction_percentage',
            'surcharge',
            'total',
            'currency_code',
            'actual_profit_analyzed',
            'actual_profit_review',
            'actual_shipping',
            'actual_other_cost',
            'actual_fullfillment',
            'actual_payment_processing',
            'actual_profit',
            'gift_certificate_amount',

            'special_instructions',
            'merchant_notes',
            'subtotal_discount',
            'gift_charge_accounting_code',
            'gift_wrap_accounting_code',
            'screen_branding_theme_code',
            'insureship_available',
            'insureship_separate',
            'insureship_wanted',
            'has_customer_profile',
            'customer_ip_address',
            'upsell_path_code',
            'test_order',
            'searstat',
            'sent_to_sears',
            'sent_to_sears_date',
            'sent_to_ac',
            'sears_response',
            'sent_to_sears_by',
            'sent_to_ac_by',
            'sent_to_ac_date',
            'current_stage',
            'payment_status',
            'date_modified',
            'subtotal_discount_refunded',
            'subtotal_refunded',
            'tax_refunded',
            'shipping_handling_refunded',
            'buysafe_refunded',
            'total_refunded',
            'refund_by_user',
            'refund_dts',
           'order_stage',
            'order_issue',
            'processing_status',
            'bulk_ac',
            'bulk_sears',
    ];

    /**
    * this order has many item
    */
    public function items(){
      return $this->hasMany('App\Models\Components\Item','order_id','order_id');
    }

    /**
    * this order has many coupons
    */
    public function coupons(){
      return $this->hasMany('App\Models\Components\Coupon','order_id','order_id');
    }

    /**
    * this order has many trackings
    */
    public function trackings(){
      return $this->hasMany('App\Models\Components\Tracking','order_id','order_id');
    }

    /**
    * this order has many transactions
    */
    public function transactions(){
      return $this->hasMany('App\Models\Components\Transaction','order_id','order_id');
    }


    /**
    * Calculate graph
    */
    public static function scopeGrapDataWeekly($query,$d){
       return $query->where('order_date','LIKE',"$d%");
    }

}
