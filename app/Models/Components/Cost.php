<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
	protected $table='cost';

    protected $fillable = [
    		'amount',
           'name',
            'cost_category_id',
    ];

    /**
    *   Cost belong cost category 
    */
    public function costCategory(){
        return $this->belongTo('App\Model\Components\CostCategory','id','cost_category_id');
    }

}
