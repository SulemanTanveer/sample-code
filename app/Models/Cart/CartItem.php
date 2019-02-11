<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];

    protected $with = ['product:id,name,quantity,price'];
    
    protected $hidden = ['created_at', 'updated_at','product'];

    protected $appends = ['product_name', 'price','pictures'];

    public function getProductNameAttribute()
    {
        return $this->product->name;
    }
    public function getPicturesAttribute()
    {
        return $this->product->pictures;
    }
    public function getIdAttribute()
    {
        return $this->product->id;
    }
    public function getPriceAttribute()
    {
        return $this->product->price;
    }
    public function product()
    {
    	return $this->belongsTo('App\Models\Product\Product');
    }
    public function shoppingCart()
    {
    	return $this->belongsTo('App\Models\Cart\ShoppingCart');
    }
}
