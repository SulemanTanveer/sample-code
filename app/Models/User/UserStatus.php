<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    protected  $table = 'user_status';
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

}
