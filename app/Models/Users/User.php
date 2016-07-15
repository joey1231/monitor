<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //
    protected $fillable=[
    		'first_name',
            'last_name',
           	'middle_name',
            'email',
            'password',
    ];
    protected $hidden=[
    	'password','remember_token'
    ];
}
