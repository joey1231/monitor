<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class ExtendedDetail extends Model
{
    //
    protected $table='extended_details';
    protected $fillable = [
     		'transaction_id',
            // info of addtional contact
            'name',
            // addtiona ifno details of contact
           'value',
    ];

    /**
    *   ExtendedDetail  belong to order
    */
    public function transaction(){
        return $this->belongTo('App\Model\Components\Transaction','id','transaction_id');
    }
}
