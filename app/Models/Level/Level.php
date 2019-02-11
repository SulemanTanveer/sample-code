<?php

namespace App\Models\Level;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\LatestRecordScope;

class Level extends Model
{
    use SoftDeletes;
    protected $table = 'levels';

    protected $guarded = [];

    protected $hidden = ['pivot','created_at', 'updated_at','deleted_at'];
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
    public function supplyList()
    {
        return $this->hasMany('App\Models\SupplyList','school_level_id');
    }

}
