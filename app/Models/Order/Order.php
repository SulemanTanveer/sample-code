<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\LatestRecordScope;
use Carbon\Carbon;
use DB;
use Webpatser\Uuid\Uuid;

class Order extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = ['orderStatus'];
    protected $hidden = ['updated_at','deleted_at'];

    protected $appends = ['invoice_url', 'order_date'];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestRecordScope);

        static::creating(function ($model) {
            $model->reference = Uuid::generate()->string;
        });
    }
    public function getInvoiceUrlAttribute()
    {
        return route('orderInvoice', ['reference' => $this->reference]);
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function address()
    {
        return $this->belongsTo('App\Models\User\UserAddress');
    }
//    public function promotionCode()
//    {
//        return $this->belongsTo('App\Models\PromotionCode');
//    }
    public function orderStatus()
    {
        return $this->belongsTo('App\Models\Order\OrderStatus');
    }
    public function products()
    {
        return $this->hasMany('App\Models\Order\OrderProduct');
    }
    public function shipment()
    {
        return $this->belongsTo('App\Models\Shipment\Shipment');
    }
    public function usedPromotion()
    {
        return $this->hasMany('App\Models\Promotion\UsedPromotionalCode');
    }

    public function billing_address()
    {
        return $this->hasOne('App\Models\Order\BillingAddress');
    }

    public function delivery_address()
    {
        return $this->hasOne('App\Models\Order\DeliveryAddress');
    }

    public function getCreatedAtAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->format('d/m/y');
    }

    public function getOrderDateAttribute()
    {
        return  Carbon::parse($this->attributes['created_at'])->format('D, d M y');
    }

    public function scopePending($query)
    {
        return $query->whereHas('orderStatus', function($q){
                $q->pending();
            });// pending
    }
    public function scopeConfirmed($query)
    {
        return $query->whereHas('orderStatus', function($q){
            $q->confirmed();
        });// paid
    }
    public function scopeCompleted($query)
    {
        return $query->whereHas('orderStatus', function($q){
            $q->completed();
        });// complete
    }
    public function scopeCancelled($query)
    {
        return $query->whereHas('orderStatus', function($q){
            $q->cancelled();
        });// error
    }
    protected function totalPendingOrders()
    {
        return $this->pending()->count();
    }
    protected function totalConfirmedOrders()
    {
        return $this->confirmed()->count();
    }
    protected function totalCompletedOrders()
    {
        return $this->completed()->count();
    }
    protected function totalCancelledOrders()
    {
        return $this->cancelled()->count();
    }
    protected function orderByWeek($date)
    {
        $order = $this->where('created_at', '>=', $date)->groupBy('week')->orderBy('week', 'DESC')
                    ->withoutGlobalScope(LatestRecordScope::class)
                    ->get(array(
                        DB::raw('WEEK(created_at) as week'),
                        DB::raw('SUM(orders.total) as total')
                    ));
        return $this->getFormattedAverageResultWeekly($order);
    }
    /**
     * @param $result
     * @return array
     */
    protected function getFormattedAverageResultWeekly($result)
    {
        $ret = [];

        if ($result) {
            foreach ($result as $row) {
                $ret[$row->week] = $row->total;
            }

        $ret = $this->formatWeeklyData($ret);

        }
        

        return $ret;
    }

    /**
     * Return an array of date => value for each requested week
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @param array $data
     * @return array
     */
    protected function formatWeeklyData($data)
    {
        $ret = [];
        $dateTo = Carbon::now()->subWeek();
        $dateFrom = Carbon::now()->subWeeks(5);

        while ($dateTo >= $dateFrom) {
            $clonedDate = clone $dateTo;
            $dateTo = $dateTo->subWeek();
            $clonedDateString = (int) $clonedDate->format('W');
            
            $ret[$clonedDateString] = array_key_exists($clonedDateString, $data) ? $data[$clonedDateString] : 0;
        }
        return $ret;
    }
    public function scopeExceptUnPaid($query)
    {
        return $query->whereHas('orderStatus', function($q){
            return $q->where('id','!=',5);
        });// error
    }

}
