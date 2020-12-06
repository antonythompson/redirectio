<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'active',
        'domain',
        'ip_id'
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    public function latestTest()
    {
        return $this->belongsToMany(Test::class)->orderBy('created_at', 'DESC')->first();
    }

    public function ip()
    {
        return $this->belongsTo(Ip::class);
    }
}
