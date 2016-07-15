<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    //
    protected $table='measures';
    protected $fillable=[
    	 // info of addtional contact
          'name',
    ];
}
