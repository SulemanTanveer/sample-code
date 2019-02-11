<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;
use App\Classes\LatestRecordScope;

class Role extends Model
{
	
    protected $guarded = [];
    protected $hidden = ['pivot','created_at','updated_at'];
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

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
