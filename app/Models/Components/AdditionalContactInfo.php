<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class AdditionalContactInfo extends Model
{
    //
    // the table bieng used of this model
    protected $table='additional_contact_info';
    protected $fillable=[
    
            'id_supplier',
            // info of addtional contact
            'info_name',
            // addtiona ifno details of contact
           'info_details',
    ];

    /**
    *   Supplier belong additional Contact
    */
    public function supplier(){
        return $this->belongTo('App\Model\Users\Supplier','id','id_supplier');
    }

}
