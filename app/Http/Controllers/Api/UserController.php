<?php

namespace App\Http\Controllers\Api;

use App\Mail\PasswordReset;
use App\Models\Promotion\PromotionCode;
use App\Models\Promotion\UsedPromotionalCode;
use App\Models\User\Billing;
use App\Models\User\UserAddress;
use App\Models\User\UserProfile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Mail, Validator, Lang;
use Illuminate\Support\Facades\Hash;
use App\Models\Order\Order;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->first();
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        $user = Auth::user();
//        dd($request->delivery_address);

        try
        {
            if ($request->password) {
                $user->password = $request->password;
                $user->save();
            }

            if($request->profile) {
               $res = $this->updateProfile($user,$request->profile);
               if(!$res)
                   return response()->json([
                       'success'    =>  false,
                       'message'    =>  Lang::get('messages.failed_update_profile')
                   ]);
            }
            if($request->address) {
                $res =  $this->updateAddress($user, $request->address);
                if(!$res)
                    return response()->json([
                        'success'    =>  false,
                        'message'    =>  Lang::get('messages.failed_update_address')
                    ]);
            }
            if($request->billing_address) {
                $res = $this->updateBillingAddress($user, $request->billing_address);
//                return $res;
                if(!$res)
                    return response()->json([
                        'success'    =>  false,
                        'message'    =>  Lang::get('messages.failed_update_billing_address')
                    ]);
            }
//            dd($request->delivery_address);
            if($request->delivery_address) {
                $res = $this->updateDeliveryAddress($user, $request->delivery_address);
//            return response()->json($res);
                if(!$res)
                    return response()->json([
                        'success'    =>  false,
                        'message'    =>  Lang::get('messages.failed_update_billing_address')
                    ]);
            }


            return response()->json([
                'success'   =>  true,
                'message'   =>  Lang::get('messages.profile_update')
            ]);

        }
        catch (\Throwable $t)
        {
            return response()->json([
                'success'   =>  false,
                'message'   =>  'something went wrong'
            ]);

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @return mixed
     */
    public function userProfile()
    {
        $id = Auth::id();
        $user = User::whereId($id)->with(['billing_address','delivery_address'])->first();
        return response()->json(['user' => $user]);
    }


    public function updateProfile($user,$data)
    {
        try {

            if($user->profile) {
                $profile = $user->profile;
            }
            else {
                $profile = new UserProfile();
                $profile->user_id = $user->id;
            }

            if (isset($data['surname'])) {
                $profile->surname = $data['surname'];
            }
            if (isset($data['firstname'])) {
                $profile->firstname = $data['firstname'];
            }
            if (isset($data['phone'])){
                $profile->phone = $data['phone'];
            }
            if (isset($data['mobile'])) {
                $profile->mobile = $data['mobile'];
            }
            if (isset($data['job'])) {
                $profile->job = $data['mobile'];
            }
            if (isset($data['description'])) {
                $profile->description = $data['description'];
            }
            if (isset($data['picture'])) {
                $profile->picture = $data['picture'];
            }
            if (isset($data['sexe'])) {
                $profile->sexe = $data['sexe'];
            }

            $profile->save();
            return true;
        }
        catch (\Throwable $t)
        {
            return false;
        }
    }


    public function updateAddress($user,$data)
    {
        try {

            if($user->address) {
                $address = $user->address;
            }
            else {
                $address = new UserAddress();
                $address->user_id = $user->id;
            }

            if (isset($data['name'])) {
                $address->name = $data['name'];
            }
            if (isset($data['city_id'])) {
                $address->city_id = $data['city_id'];
            }

            $address->save();
            return true;
        }
        catch (\Throwable $t)
        {
            return false;
        }
    }

    public function updateBillingAddress($user,$data)
    {
        try {
            $user->billing_address()->updateOrCreate(['user_id'=>$user->id],$data);
            return true;
        }
        catch (\Throwable $t)
        {
            return $t->getMessage();
        }
    }

    public function updateDeliveryAddress($user,$data)
    {

        try {
            $user->delivery_address()->updateOrCreate(['user_id'=>$user->id],$data);
            return true;
        }
        catch (\Throwable $t)
        {
            return $t->getMessage();
        }
    }


    public function changePassword()
    {
        $validator = Validator::make(request()->all(),[
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors'=>$validator->errors()
            ], 401);
        }

        $user = Auth::user();
        $user->password = request('password');
        $user->save();
        return response()->json([
            'success'   =>  true,
            'message'   => Lang::get('messages.password_changed')
        ],200);

    }

    public function getChildren()
    {
        return Auth::user()->children()->get();
    }

    /**
     * @return Mixed
     */
    
    public function getOrders()
    {
        return Auth::user()->orders()->exceptUnPaid()->with(['user','products','address','orderStatus','shipment.deliveryStatus'])->get();
    }

    public function showOrder($id)
    {
        return Order::with(['user','products','address','orderStatus','shipment.deliveryStatus','delivery_address','billing_address'])->where('reference', $id)->first();
    }

    public function searchUser($keyword)
    {
        return User::where('email','like','%'.$keyword.'%')
            ->orWhereHas('profile',function($query)use ($keyword){
                $query->where('firstname','like','%'.$keyword.'%')
                        ->orWhere('surname','like','%'.$keyword.'%');
            })
            ->take(20)->get();
    }

    public function unUsedPromoCodes()
    {
        $promos = UsedPromotionalCode::whereUserId(Auth::id())
            ->whereUsed(0)
            ->with('codes')
            ->get();
        $codes = $promos->map(function ($promo){
            return $promo->codes;
        });
        return $codes;
    }

    public function savePromoCodes()
    {
        $promos = PromotionCode::whereIn('code',request('promotions'))->get();
        $user = Auth::user();
        $user->usedPromotion()->whereUsed(0)->delete();

        foreach ( $promos as $promo)
        {
            UsedPromotionalCode::create([
                'user_id'=>$user->id,
                'promotion_code_id' => $promo->id,
                'used'=>0
            ],200);
        }
        return response()->json([
            'success'   => true,
            'message'   =>  'Updated successfully'
        ],201);
    }


}
