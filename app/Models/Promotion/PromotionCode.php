<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Classes\LatestRecordScope;

class PromotionCode extends Model
{
    
    protected $guarded = [];
    
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
    	'limit_per_user' => 'boolean'
    ];
//    protected $appends = ['total_used', 'expiry_days_left'];
    protected $appends = ['total_used_promotion_code', 'expiry_days_left'];
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
    public function usedPromotion()
    {
    	return $this->hasMany('App\Models\Promotion\UsedPromotionalCode');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Promotion\PromotionCodeType','promotion_type_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product','product_id');
    }

   public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function getExpiryDaysLeftAttribute()
    {
        $created = new Carbon($this->expiry_date);
        $now = Carbon::now();
        return $now < $created ? $created->diffForHumans($now, true).' left' : 'expired';
    }
//    public function gettotalUsedAttribute()
//    {
//    	return $this->orders()->count();
//    }
    public function getTotalUsedPromotionCodeAttribute()
    {
    	return $this->usedPromotion()->count();
    }

    public function scopeValidateCode($query)
    {
        return $query->where('expiry_date', '>' ,Carbon::now());
    }

    public function scopeUserSpecific($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeAlreadyUsedCodeByUser($query,$user)
    {
        return $query->whereHas('usedPromotion',function($q) use($user)
        {
           $q->whereUserId($user)
               ->whereUsed(1);
        });
    }






}
