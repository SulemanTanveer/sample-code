<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $guarded = [];

    protected $table = 'user_billing';

    protected $hidden = ['updated_at','deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
