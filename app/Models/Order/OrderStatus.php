<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
	protected $guarded = [];
    
    protected $table = 'order_statuses';
	
    protected $hidden = ['created_at','updated_at'];
	
	const PENDING = 1;
	const CONFIRMED = 2;
	const COMPELTED = 3;
	const CANCELLED = 4;
	const UNPAID = 5;

 	public function order()
    {
        return $this->hasMany('App\Models\Order\Order');
    }
    public function scopePending($query)
    {
    	return $query->whereId(static::PENDING);
    }
    public function scopeConfirmed($query)
    {
    	return $query->whereId(static::CONFIRMED);
    }
    public function scopeCompleted($query)
    {
    	return $query->whereId(static::COMPELTED);
    }
    public function scopeCancelled($query)
    {
    	return $query->whereId(static::CANCELLED);
    }
    public function scopeUnpaid($query)
    {
    	return $query->whereId(static::UNPAID);
    }

}
