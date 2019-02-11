<?php

namespace App\Models\Children;

use Illuminate\Database\Eloquent\Model;
use App\Classes\LatestRecordScope;

class Child extends Model
{

    protected $table = 'children';

    protected $guarded = [];

    protected $with = ['supplyList','city'];

    protected $hidden = ['city_id', 'school_id', 'school_level_id' ,'created_at', 'updated_at'];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestRecordScope);
    }
    public function parent()
    {
        return $this->belongsTo('App\User','parent_id');
    }
    public function school()
    {
        return $this->belongsTo('App\Models\School\School');
    }
    public function level()
    {
        return $this->belongsTo('App\Models\Level\Level','school_level_id');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    public function shoppingCart()
    {
        return $this->hasOne('App\Models\Cart\ShoppingCart');
    }
    public function supplyList()
    {
        return $this->hasManyThrough(
            'App\Models\Cart\CartItem',
            'App\Models\Cart\ShoppingCart');
    }
    protected function addChild($data)
    {
        $child = self::whereId($data['id'])->whereParentId(auth()->user()->id)->first();
        $info = [
                'firstname' => isset($data['firstname']) ? $data['firstname'] : NULL,
                'city_id'   => $data['city_id'],
                'school_id' =>  $data['school_id'],
                'school_level_id'   =>  $data['school_level_id'],
                'parent_id' =>  auth()->user()->id
            ];
        if ($child) {
            $child->update($info);
            return $child;
        }
        $child = self::create($info);
        return $child;
    }
}
