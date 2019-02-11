<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\LatestRecordScope;

class SupplyList extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected  $with = ['city'];
    protected $hidden = ['pivot','created_at','updated_at','deleted_at'];
    protected $appends = ['products_left','products_right'];
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
    public function products()
    {
        return $this->belongsToMany('App\Models\Product\Product','product_supply_list','supply_list_id','product_id')
            ->withPivot('product_quantity','handy');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    public function school()
    {
        return $this->belongsTo('App\Models\School\School');
    }
    public function level()
    {
        return $this->belongsTo('App\Models\Level\Level','school_level_id');
    }
    public function getProductsLeftAttribute()
    {
        $prods = $this->products()->get();
        return $prods->map(function($p){
            if($p->pivot->handy == 1)
            {
            return [
                'id'    =>  $p->pivot->product_id,
                'name'  =>  $p->name,
                'price' => $p->price,
                'quantity'  =>  $p->pivot->product_quantity,
                'slug'  => $p->slug,
                'pictures'=> $p->pictures,
            ];
            }
        })->filter()->values();
    }
    public function getProductsRightAttribute()
    {
        $prod = $this->products()->get();
         return $h = $prod->map(function($p){
            if($p->pivot->handy == 2){
                return [
                    'id'    =>  $p->pivot->product_id,
                    'name'  =>  $p->name,
                    'price' => $p->price,
                    'quantity'  =>  $p->pivot->product_quantity,
                    'slug'  => $p->slug,
                    'pictures'=> $p->pictures,

                ];
            }
        })->filter()->values();
    }

    public function scopeLefty($query)
    {
        $pivot = $this->products()->getTable();

      return   $query->whereHas('products', function ($q) use ($pivot) {
            $q->where("{$pivot}.handy", 1);
        });
        return $this->products()->wherePivot('handy','=',1);
    }
}
