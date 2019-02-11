<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\TitleUpdate;
use App\Classes\LatestRecordScope;

class Product extends Model
{
    use SoftDeletes, TitleUpdate;
    
    protected $guarded = [];

    protected $hidden = ['pivot','created_at','updated_at','deleted_at','totalPurchase'];

    protected $appends = ['total_purchase','bundle_product_list'];

    protected $with = ['pictures'];
    
    protected static function boot()
    {
        parent::boot();
        static::created(function ($product) {
            $product->update([ 'slug' => $product->name ]);
        });

        static::deleting(function($product) {
            foreach ($product->pictures as $picture)
            {
                $picture->deleteImage($picture->url);
            }
        });

        static::addGlobalScope(new LatestRecordScope);
        
        // static::updated(function ($product){
        //     if ($product->replacement_id != null) {
        //         $product->supplyLists()->where('product_id', $product->id)->update([
        //             'product_id' => $product->replacement_id,
        //         ]);
        //     }
        // });
    }
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category\Category');
    }
    public function types()
    {
        return $this->belongsTo('App\Models\Product\ProductType','type_id');
    }
    public function pictures()
    {
        return $this->hasMany('App\Models\Product\ProductPicture');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\Product\ProductStatus');
    }
    public function colors()
    {
        return $this->belongsToMany('App\Models\Product\ProductColor');
    }
    public function sizes()
    {
        return $this->belongsToMany('App\Models\Product\ProductSize');
    }
    public function supplyLists()
    {
//        return $this->belongsToMany('App\Models\SupplyList','product_supply_list','product_id','supply_list_id')
//            ->withPivot('product_quantity','handy');
    }
    public function orderProduct()
    {
        return $this->hasMany('App\Models\Order\OrderProduct');
    }
    public function carts()
    {
        return $this->hasMany('App\Models\Cart\CartItem');
    }

    public function bundleProducts()
    {
        return $this->belongsToMany('App\Models\Product\Product', 'bundle_products',  'bundle_id', 'product_id')
            ->withPivot('product_quantity');
    }

    protected function addNewProduct($request)
    {
        return $this->create([
            'code'=>$request->code,
            'ean' => $request->ean,
            'name' => $request->name,
            'type_id' => $request->type_id,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'order_number' => $request->order_number,
            'purchase_date' => $request->purchase_date,
            'status_id' => 1,
        ]);
    }

    protected function updateQuantity($quantity)
    {
        $this->update(['quantity' => $quantity]);
    }

    protected function checkQuantity($product, $quantity)
    {
        $product = $this->find($product);
        if ($product->quantity > $quantity) {
            return ;
        }
        return $product;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function scopeByCategories($query, $category)
    {
        return $query->whereHas('types', function($q) use ($category){
                $q->where('type_id', $category->id);
            });
    }
    public function totalPurchase()
    {
        return $this->hasOne('App\Models\Order\OrderProduct')
            ->selectRaw('product_id, count(*) as total_purchase')
            ->groupBy('product_id');
    }
    public function getTotalPurchaseAttribute()
    {
        // if relation is not loaded already, let's do it first
        $related = $this->getRelationValue('totalPurchase');
        return ($related) ? (int) $related->total : 0;
    }
    public function scopeBundle($query)
    {
        return $query->whereHas('types', function($q){
                $q->where('id',1);
        });
    }

    public function scopeNoBundle($query)
    {
        return $query->whereHas('types', function($q){
            $q->where('id','!=',1);
        });
    }
    public function getBundleProductListAttribute()
    {
        $prod = $this->bundleProducts()->get();
        return $prod->map(function($p){
            return [
                'id'    =>  $p->pivot->product_id,
                'name'  =>  $p->name,
                'price' => $p->price,
                'quantity'  =>  $p->pivot->product_quantity,
                'pictures'  =>  $p->pictures,
                'slug'=> $p->slug
            ];
        });
    }

    public function getPriceAttribute($price)
    {
        return round($price,'2');
    }
}
