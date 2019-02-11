<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function order() {
        return $this->belongsTo('App\Models\Order\Order', 'order_id');
    }

}
