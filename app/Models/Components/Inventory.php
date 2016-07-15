<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
	protected $table='inventory';
    protected $fillable = [
    		'program_type',
            'location_id',
            'on_hand_quantity',
            'reserved_quantity',
            'available_quantity',
            'pick_up_now_eligible',
            'sears_inventory',
           	'item_id',
    ];

    /**
    *   Inventory  belong to item
    */
    public function item(){
        return $this->belongTo('App\Model\Components\Item','id','item_id');
    }
}
