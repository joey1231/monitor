<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
	protected $table='images';
    protected $fillable = [
    		'main_jpg_image',
            'png_image',
            'psd_image',
            'ai_image',
            'item_id',
    ];
     /**
    *   PackageItem  belong to order
    */
    public function item(){
        return $this->belongTo('App\Model\Components\Item','id','item_id');
    }

}
