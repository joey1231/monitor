<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
	protected $table='package_items';
    //
    protected $fillable = [
    	// info of addtional contact
            'product_id',

            'item_id',
            'numbers',
    ];

    /**
    *   PackageItem  belong to order
    */
    public function product(){
        return $this->belongTo('App\Model\Components\ProductSupplier','id','product_id');
    }

    /**
    *   PackageItem  belong to order
    */
    public function item(){
        return $this->belongTo('App\Model\Components\Item','id','item_id');
    }

}
