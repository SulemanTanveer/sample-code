<?php

namespace App\Models\Shipment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order\Order');
    }
    public function deliveryStatus()
    {
        return $this->belongsTo('App\Models\Shipment\DeliveryStatus','delivery_status_id');
    }
}
