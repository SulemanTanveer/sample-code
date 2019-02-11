<?php

namespace App\Models\Shipment;

use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
    protected $table = 'delivery_status';

    protected $guarded = [];

    protected $hidden = ['created_at','updated_at'];
}
