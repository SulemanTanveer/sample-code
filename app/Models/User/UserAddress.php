<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at','updated_at'];
    protected $with = ['city'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_id');
    }


}
