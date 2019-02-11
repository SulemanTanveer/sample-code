<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    
    protected $guarded = [];

    public $timestamps =false;

    public $appends = ['name','slug'];
    
    public $with = ['product'];
       
    public $hidden = ['product'];

    public function getNameAttribute()
    {
        return $this->product ? $this->product->name : null;
    }
    public function getSlugAttribute()
    {
        return $this->product ? $this->product->slug : null;
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Order\Order', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product', 'product_id');
    }
}
