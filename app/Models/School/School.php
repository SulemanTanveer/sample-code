<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\LatestRecordScope;

class School extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = ['city'];
    protected $hidden = ['created_at','updated_at','deleted_at'];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestRecordScope);
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Models\Level\Level','school_levels');
    }
    public function supplyList()
    {
        return $this->hasMany('App\Models\SupplyList','school_id');
    }
}
