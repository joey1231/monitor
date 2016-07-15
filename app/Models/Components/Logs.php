<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
	protected $table='logs';
    protected $fillable = [
    	 'transaction',
    ];
}
