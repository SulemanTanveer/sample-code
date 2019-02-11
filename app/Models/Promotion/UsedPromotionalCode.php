<?php

namespace App\Models\Promotion;

use App\Classes\LatestRecordScope;
use Illuminate\Database\Eloquent\Model;
use Auth;
class UsedPromotionalCode extends Model
{
    protected $guarded = [];
    protected $table = 'used_promotional_code';

    protected $hidden = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new LatestRecordScope());
    }
    public function orders()
    {
        return $this->belongsTo('App\Models\Order\Order', 'order_id');
    }

    public function users()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function codes()
    {
        return $this->belongsTo('App\Models\Promotion\PromotionCode','promotion_code_id');
    }

    public function scopeUsed($query)
    {
        return $query->where('used', '=' ,1);
    }

    public function scopeUsedSpecificCode($query,$user)
    {
        return $query->whereUsed(1)->whereUserId($user);
    }

    public function scopePendingUsedPromoOnCart($query,$code)
    {
        if($code->promotion_type_id == 1):
            return $query->whereHas('codes',function($q){
                    $q->where('promotion_type_id','=',1);
                })
                ->whereUsed(0)
                ->whereUserId(Auth::id());
        else:
            return $query->whereHas('codes',function($q) use($code){
                $q->where('promotion_type_id','=',2)
                    ->whereProductId($code->product_id);
            })
                ->whereUsed(0)
                ->whereUserId(Auth::id());
        endif;
    }

    public function scopePendingUsedPromoOnProduct($query,$product_id)
    {
        return $query->whereHas('codes',function($q) use($product_id){
                $q->where('promotion_type_id','=',2)
                ->whereProductId($product_id);
            })
            ->whereUsed(0)
            ->whereUserId(Auth::id());
    }





}
