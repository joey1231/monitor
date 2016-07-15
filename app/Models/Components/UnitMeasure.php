<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
	protected $table='unit_measure';
    protected $fillable= [
    		'name',
            'abbrev',
            'measure_id',
    ];
    /**
    *   Itam  belong to order
    */
    public function measure(){
        return $this->hasMany('App\Model\Components\Measure','id','measure_id');
    }

}
