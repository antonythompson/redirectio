<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Website
 * @package App
 * @method public Model tests
 */
class Website extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'active',
        'domain',
        'ip_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    /**
     * @return mixed
     */
    public function latestTest()
    {
        return $this->belongsToMany(Test::class)->orderBy('created_at', 'DESC')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ip()
    {
        return $this->belongsTo(Ip::class);
    }
}
