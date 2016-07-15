<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class CostCategory extends Model
{
	 protected $table='cost_category';
     protected $fillable = [
     		'supplier_product_id',
            'category_name',
     ];

    /**
    *   cost category  belong supplier product
    */
    public function costCategory(){
        return $this->belongTo('App\Model\Components\ProductSupplier','id','supplier_product_id');
    }

}
