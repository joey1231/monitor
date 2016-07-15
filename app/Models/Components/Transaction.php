<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table='transactions';
    protected $fillable= [

    		'gateway',
            // info of addtional contact
            'successful',
            // addtiona ifno details of contact
           'order_id',
    ];
    /**
    *   Itam  belong to order
    */
    public function order(){
        return $this->hasMany('App\Model\Components\Order','order_id','order_id');
    }

}
