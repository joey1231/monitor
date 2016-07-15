<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class LabelImage extends Model
{
	protected $table='label_image';
    protected $fillable = [
    		'main_jpg_image',
            'png_image',
            'psd_image',
            'ai_image',
            'item_id',
    ];

     /**
    *   LabelImage  belong to item
    */
    public function item(){
        return $this->belongTo('App\Model\Components\Item','id','item_id');
    }
}
