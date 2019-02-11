<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at', 'deleted_at'
    ];
    
    protected $with = ['profile', 'address', 'child','roles'];
    
    protected $appends = ['child_count'];

    public function routeNotificationForOneSignal()
    {
        return ['tags' => ['key' => 'user_type', 'relation' => '=', 'value' => 'LOGISTICMANAGER']];
    }
    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }
    public function getCreatedAtAttribute($value)
    {
        return  Carbon::parse($value)->diffForHumans();
    }
    public function getLastActiveAttribute($value)
    {
        return  Carbon::parse($value)->diffForHumans();
    }
    protected function checkStatus($email = null)
    {
        return (boolean) $this->where(['email' => $email, 'status' => 1])->exists();
    }
    public function checkRole($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles);
        } else {
            return $this->hasRole($roles);
        }

        return false;
    }
    public function hasAnyRole($roles)
    {
        return (boolean) $this->roles()->whereIn('name', $roles)->exists();
    }
    public function hasRole($role)
    {
        return (boolean) $this->roles()->where('name', $role)->exists();
    }

    public function generateHash() {
        return md5( $this->salt. $this->email);
    }
    
    public function getProfileAttribute()
    {
        return $this->profile()->first();
    }
    protected function addUser(Request $request)
    {
        $user = $this->create([
            'email' => $request->email,
            'password' => $request->password,
            //need to change status to 1 before goes to production 
            'status' => 2,
            'remember_token' => str_random(19),
            'confirmation_token' => str_random(25)
        ]);

        $user->profile()->create([
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'sexe' => $request->sexe,
            'birthdate' => $request->birthdate,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'description' => $request->description
        ]);
        $user->address()->create([
            'name' => $request->name,
            'street_1' => $request->street_1,
            'bte' => $request->bte,
            'locale' =>request('locale'),
            'zip' => $request->zip,
            'city_id' => $request->city_id,
        ]);
        $user->roles()->attach(3);
        return $user;
    }
    public function confirm()
    {
        $this->status = 2;
        $this->confirmation_token = null;
        $this->save();
    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role\Role')->withTimestamps();
    }
    public function profile()
    {
        return $this->hasOne('App\Models\User\UserProfile');
    }

    public function address()
    {
        return $this->hasOne('App\Models\User\UserAddress');
    }
    public function billing_address()
    {
        return $this->hasOne('App\Models\User\Billing');
    }
    public function delivery_address()
    {
        return $this->hasOne('App\Models\User\Delivery');
    }

    public function city()
    {
        return $this->hasManyThrough('App\Models\City', 'App\Models\User\UserAddress');
    }
    public function products()
    {
        return $this->hasMany('App\Models\Product\Product');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order\Order');
    }

    public function shoppingCart()
    {
        return $this->hasMany('App\Models\Cart\ShoppingCart');
    }

    public function promotionCodes()
    {
        return $this->hasMany('App\Models\Promotion\PromotionCode');
    }
    public function cartItems()
    {
        return $this->hasManyThrough(
            'App\Models\Cart\CartItem',
            'App\Models\Cart\ShoppingCart');
    }

    public function getAddressAttribute()
    {
        return $this->address()->first();
    }

    public function children()
    {
        return
            $this->hasMany('App\Models\Children\Child','parent_id')
                ->with(['school:id,name','level:id,name']);
    }
    public function child()
    {
        return $this->hasMany('App\Models\Children\Child','parent_id');
    }

    public function getChildCountAttribute()
    {
        return count($this->child);
    }
    public function scopeLogisticManager($query)
    {
        return $this->whereHas('roles', function($q){
                $q->where('name', 'LOGISTICMANAGER');
            });
    }
    public function scopeParent($query)
    {
        return $query->whereHas('roles', function($q){
                $q->where('name', 'Parent');
            });
    }
    public function usedPromotion()
    {
        return $this->hasMany('App\Models\Promotion\UsedPromotionalCode');
    }

    public function promos()
    {
        return $this->promotionCodes()->validateCode();
    }

    public function scopeByRole($query, $role)
    {
        return $this->whereHas('roles', function($q) use($role){
            $q->where('name', $role);
        });
    }

    public function unUsedPromos()
    {
        return $this->usedPromotion()->whereUsed(0)->get();
    }
}
