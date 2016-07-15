<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
	protected $table='product_supplier';
    protected $fillable = [
    		'supplier_id',
            'item_id',
    ];
    
     /**
    *   PackageItem  belong to order
    */
    public function item(){
        return $this->hasMany('App\Model\Components\Item','id','item_id');
    }

    /**
    *   PackageItem  belong to order
    */
    public function supplier(){
        return $this->hasMany('App\Model\Users\Supplier','id','supplier_id');
    }

}
