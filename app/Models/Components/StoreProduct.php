<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
	protected $table='store_products';
    protected $fillable = [
    		'name',
            'sku',
            'upc',
            'cogs',
            'weight',
            'dimension_length',
            'dimension_width',
            'dimension_height',
            'dimension_unit',
           'features',
            'description',
            
            'retail_price',
            'retail_shipping_price',
            'product_type_id',
            'units_per_package',
            'package_unit_id',
            'weight_unit',
    ];

     /**
    *   PackageItem  belong to order
    */
    public function packageItem(){
        return $this->hasMany('App\Model\Components\PackageItem','id','package_unit_id');
    }

}
