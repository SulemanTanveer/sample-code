<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    protected $guarded = [];

    protected $table = 'delivery_address';

    protected $hidden = ['updated_at','deleted_at'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order\Order');
    }

}
