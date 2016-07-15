<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
	protected $table='trackings';
    protected $fillable = [
    	// info of addtional contact
            'number',

            'url',
            'order_id',
            'sku',
    ];
    /**
    *   Itam  belong to order
    */
    public function order(){
        return $this->hasMany('App\Model\Components\Order','order_id','order_id');
    }

}
