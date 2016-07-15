<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
    		'name',
            // info of addtional contact
            'address',
             // info of addtional contact
            'city',
             // info of addtional contact
            'state',
             // info of addtional contact
            'postal_code',
             // info of addtional contact
            'contact_person',
             // info of addtional contact
            'phone',
            // addtiona ifno details of contact
            'email',
            // addtiona ifno details of contact
            'fax',
    ];

    /**
    *   Supplier has additional Contact
    */
    public function additionalContactInfo(){
        return $this->hasMany('App\Model\Components\AdditionalContactInfo','id_supplier','id');
    }

    /**
    *   Supplier has Products
    */
    public function products(){
        return $this->hasMany('App\Model\Components\ProductSupplier','supplier_id','id');
    }
    
    /**
    *   Supplier has SupplierWarehouse
    */
    public function supplierWarehouse(){
        return $this->hasMany('App\Model\Components\SupplierWarehouse','supplier_id','id');
    }
}
