<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart\CartItem;

class ShoppingCart extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $with = ['cartItems'];
    protected static function boot()
    {
        parent::boot();
    
        static::deleting(function($shoppingCart) {
            $shoppingCart->cartItems()->delete();
        });

        static::updated(function ($shoppingCart){
            if ($shoppingCart->cartItems->count() < 1) {
                $shoppingCart->cartItems->delete();
            }
        });

   }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    public function child()
    {
        return $this->hasOne('App\User\Children\Child');
    }
    public function cartItems()
    {
    	return $this->hasMany('App\Models\Cart\CartItem');
    }
    public function scopeByChild($query, $childId)
    {
        return $query->where('user_id', auth()->user()->id)
                ->when($childId, function($cart) use ($childId){
                    $cart->where('child_id', $childId);
                });
    }
    protected function addShoppinCart(array $data, $child = null)
    {
        $shoppingCart = $this->firstOrCreate([
                'user_id' => auth()->user()->id,
                'child_id' => $child
            ]);

        $shoppingCart->syncCartItems($data['supply_list']);
    }
    public function syncCartItems($cart_items)
    {
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                $this->cartItems()->updateOrCreate([
                    'product_id' => $cart_item['product_id'],
                ],[
                    'product_id' => $cart_item['product_id'],
                    'quantity' => $cart_item['quantity']
                ]);
            }
            $this->deleteItem($cart_items);
        } else {
            $this->cartItems()->delete();
            $this->delete();
        }
    }
    public function deleteItem(array $cart_items)
    {
        $this->cartItems()
            ->whereNotIn('product_id', array_column($cart_items, 'product_id'))
            ->delete();
    }
}
