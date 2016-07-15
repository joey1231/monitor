<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class SupplierWarehouse extends Model
{
	protected $table='supplier_warehouse';
    protected $fillable = [
    		'name',
            'address',
            'city',
            'state',
            'postal_code',
            'supplier_id',
    ];
    /**
    *   PackageItem  belong to order
    */
    public function supplier(){
        return $this->hasMany('App\Model\Users\Supplier','id','supplier_id');
    }
    
}
