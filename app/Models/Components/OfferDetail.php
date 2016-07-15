<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class OfferDetail extends Model
{
	 protected $table='offer_details';
     protected $fillable = [

     	    'discount',
            'quantity',
            'price',
            'ship_price_per_unit',
            
          
            'store_product_item_id',

     ];

    /**
    *   LabelImage  belong to item
    */
    public function item(){
        return $this->belongTo('App\Model\Components\Item','id','store_product_item_id');
    }
}
