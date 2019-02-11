<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserProfile extends Model
{
    protected $guarded = [];
    
    protected $hidden = ['created_at','updated_at'];

//    protected $with = ['city'];
    
    protected $appends = ['full_name'];

    public function getfullNameAttribute()
    {
        return $this->firstname.' '.$this->surname;
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
//    public function city()
//    {
//        return $this->belongsTo('App\Models\City', 'city_id');
//    }
//    public function getCityAttribute()
//    {
//        return $this->city()->first();
//    }
}
