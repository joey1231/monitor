<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class SearTrackUploadLog extends Model
{
	protected $table='sear_track_upload_log';
    protected $fillable = [
    	  'uploaded_by',
    ];
}
