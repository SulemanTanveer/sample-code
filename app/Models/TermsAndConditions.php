<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    protected $table = 'terms_and_conditions';
    protected $guarded = [];

    protected $hidden = ['created_at','updated_at'];
}
