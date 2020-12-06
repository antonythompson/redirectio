<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'ssl',
        'ssl_redirect',
        'google_analytics',
        'ip_correct'
    ];
}
