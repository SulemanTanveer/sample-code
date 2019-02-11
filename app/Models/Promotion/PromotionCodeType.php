<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class PromotionCodeType extends Model
{
    protected $guarded = [];

    protected $table = 'promotion_code_type';

    protected $hidden = ['created_at', 'updated_at'];

}
