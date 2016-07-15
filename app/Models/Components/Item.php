<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $table='items';
     protected $fillable = [
    	// info of addtional contact
           'item_id',
            'item_index',
            'quantity',
            'description',
           'cost',
            'unit_cost_with_discount',
            'total_cost_with_discount',
            'cogs',
            'actual_cogs',
            'country_of_origin',
            'item_weight',
            'tax_free',
            'special_product_type',
            'free_shipping',
            'accounting_code',
            'discount',
            'distribution_center_code',
            'kit',
           'kit_component',
            'send_to_sears',
            'quantity_refunded',
            'toal_refunded',
            'manufacturer_sku',
            'merchant_id',
            'barcode',
            'order_id',
    ];

    /**
    *   Itam  has to Inventory
    */
    public function inventory(){
        return $this->hasMany('App\Model\Components\Inventory','item_id','id');
    }

    /**
    *   Itam  belong to order
    */
    public function order(){
        return $this->belongsTo('App\Models\Components\Order','order_id','order_id');
    }

}
