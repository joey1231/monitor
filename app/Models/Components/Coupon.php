<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
	protected $table='coupons';
    protected $fillable = [
    	   	'order_id',
            'coupon_code',
            'coupon_accounting_code',
            'base_coupon_code',
    ];

    /**
    *   Coupon  belong to order
    */
    public function order(){
        return $this->belongTo('App\Model\Components\Order','order_id','order_id');
    }

}
