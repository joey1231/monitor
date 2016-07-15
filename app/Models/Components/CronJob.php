<?php

namespace App\Models\Components;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $table = 'cron_jobs';
    protected $fillable = [
        // info of addtional contact
        'name',
        'status',
    ];
}
