<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    //
    protected $guarded = [];

    protected $table = 'billing_address';

    protected $hidden = ['updated_at','deleted_at'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order\Order');
    }
    
}
