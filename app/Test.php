<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'ssl',
        'ssl_redirect',
        'google_analytics',
        'ip_correct',
        'is_down'
    ];

    public function parseResult($value)
    {
        return $value ?  "<span class='ok'>YES</span>" : "<span class='fail'>NO</span>";
    }

    public function getSslAttribute($value)
    {
        return $this->parseResult($value);
    }
    public function getSslRedirectAttribute($value)
    {
        return $this->parseResult($value);
    }
    public function getGoogleAnalyticsAttribute($value)
    {
        return $this->parseResult($value);
    }
    public function getIpCorrectAttribute($value)
    {
        return $this->parseResult($value);
    }
    public function getIsDownAttribute($value)
    {
        return $this->parseResult($value);
    }
}
