<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $guarded = [];

    protected $table = 'user_delivery';

    protected $hidden = ['updated_at','deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
