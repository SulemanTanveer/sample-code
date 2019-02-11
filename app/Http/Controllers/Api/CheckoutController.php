<?php

namespace App\Http\Controllers\Api;

use App\Models\Order\{Order, BillingAddress, DeliveryAddress, OrderProduct};

use App\Models\Product\Product;
use App\Models\Promotion\PromotionCode;
use App\Library\Services\LemonWay;
use App\Models\Promotion\UsedPromotionalCode;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth, Lang, Validator;
use Log;
use DB;
class CheckoutController extends Controller
{
    protected $lemonWay;

    function __construct(LemonWay $lemonWay)
    {
       $this->lemonWay = $lemonWay;
    }
    public function index(Request $request)
    {
        $shipment = request('shipment_id');
        $total = 0;
        $totalDiscount = 0;

        // create order
        $order = Order::create([
            'user_id'       => Auth::id(),
//            'shipment_id'   =>  1,
            'address_id'    => 1,
            'discount'      => $totalDiscount,
            'total'         => $total,
            'payment'       => 'paypal',
            'comment'       => 'paypal',
            'order_status_id' => 5
        ]);


        //fetch user cart
        $carts = collect(auth()->user()->shoppingCart);

        //serialize products
        $products_list = $carts->map(function ($cart) {
            return $cart->cartItems;
        })->flatten();

        // create ordered products
        foreach ($request->products as $list):
            $already_added = OrderProduct::whereOrderId($order->id)
                                        ->whereProductId($list['product_id'])
                                        ->first();
            if($already_added)
            {
                $already_added->quantity = $already_added->quantity+$list['quantity'];
                $already_added->save();
            }
            else
            {
                OrderProduct::create([
                    'order_id'      =>  $order->id,
                    'product_id'    =>  $list['product_id'],
                    'quantity'      =>  $list['quantity'],
                    'price'         =>  $list['price']
                ]);
            }
        endforeach;



//        $order = Order::where('id',14)->first();
        // get used promotion codes
        $codes = UsedPromotionalCode::whereUserId(Auth::id())->whereUsed(0)->pluck('promotion_code_id');

        $promotion_codes =  PromotionCode::whereIn('id',$codes)->get();

        //calculate invoice/bill
        // foreach ($carts as $cart)
        // {
            foreach($request->products as $item)
            {
                $price = $item['price']*$item['quantity'];
                $discount = 0;
                foreach ($promotion_codes as $pc)
                {
                    if($pc->promotion_type_id == 2 && $item['product_id'] == $pc->product_id)
                    {
                        $discount = $price*$pc->discount/100;
                        break;
                    }
                    if($pc->promotion_type_id == 1){
                        $discount = $price*$pc->discount/100;
                    }
                }
                $total += $price - $discount;
                $totalDiscount += $discount;
            }
        // }

        //add shipment charges
        $order_shipment = $order->shipment;
//        if(!$order_shipment->is_free)
//            $total +=$order_shipment->cost;
        if($total<30)
        {
            $order->shipment_id = 2;
            $total += 5.95;
        }
        else {
            $order->shipment_id = 1;
        }
        //update oder discount and bill
        // $order->discount = $totalDiscount;
        $order->discount = $request->discount;
        $order->total = number_format((float)$request->total, 2, '.', '');
        
        $order->save();


        //Create Billing Address
        $billing_address = $request->billing_address;
        $order->billing_address()->save(new BillingAddress($billing_address));

        //Create Delivery Address
        $delivery_address = $request->delivery_address;
        $order->delivery_address()->save(new DeliveryAddress($delivery_address));

        $res = $this->makePayment($request, $order);
        return $res;
    }

    protected function makePayment($request, $order)
    {
        try {
            $paymentPage = $this->lemonWay->callService('MoneyInWebInit', array(
                "amountTot" => $order->total,
                "walletIp"  => $request->ip(),
                "walletUa"  => $request->header('User-Agent'),
                "registerCard" => 1,
                "returnUrl" => env('PAYMENT_REDIRECT').'/api/v1/order/success/'.$order->reference,
                "cancelUrl" => env('PAYMENT_REDIRECT').'/api/v1/order/failure/'.$order->reference,
                "errorUrl"  => env('PAYMENT_REDIRECT').'/api/v1/order/failure/'.$order->reference
            ));
            return response()->json(['paymentPage' => $paymentPage]);
        }
        catch (\Exception $e)
        {
            Log::error($e);
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
    public function successUrl()
    {
        return action('Api\OrderController@store', [
            'id' => auth()->user()->id
        ]);
    }
    public function failureUrl()
    {
        return action('Api\OrderController@store', [
            'id' => auth()->user()->id,
        ]);
    }

    public function checkPromoCodeLogged()
    {
       $validator = Validator::make(request()->all(),[
            'promo_code' => 'required|exists:promotion_codes,code',
        ]);
       if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => Lang::get('messages.promotion_not_exists')
            ], 403);
       }
        $code = request('promo_code');
        $already_used = false;

        $code = PromotionCode::whereCode($code)->validateCode()->first();
        if(!$code)
            return response()->json([
                'success'   =>  false,
                'message'   =>  Lang::get('messages.promotion_code_expire')
            ],403);

        // promo code already applied on whole cart
        $used_cart_promo =  UsedPromotionalCode::pendingUsedPromoOnCart($code)->first();
        if ($used_cart_promo)
            $already_used = true;

        if($already_used)
        {
            return response()->json([
                'success'   =>  false,
                'message'   =>  Lang::get('messages.same_promo_type')
            ],403);
        }

        $used_promo = UsedPromotionalCode::whereUserId(Auth::id())
                        ->wherePromotionCodeId($code->id)
                        ->first();

        if($code->limit_per_user)
        {
            if($code->user_id == Auth::id())
                {
                    if($used_promo)// already used
                    {
                        return response()->json([
                            'success'   =>  false,
                            'message'   =>  Lang::get('messages.promotion_code_used')
                        ],403);
                    }
                    UsedPromotionalCode::firstOrcreate([
                        'user_id'           =>  Auth::id(),
                        'promotion_code_id' =>  $code->id,
                        'used'              =>  0
                    ]);
                   return $code;
                }
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  Lang::get('messages.promotion_unauthorized')
                ],403);
        }

        if($used_promo['user_id'] == Auth::id() && $used_promo['used']==0)
            return response()->json([
                'success'   =>  false,
                'message'   =>  Lang::get('messages.promotion_code_used')
            ],403);

        UsedPromotionalCode::firstOrcreate([
            'user_id'           =>  Auth::id(),
            'promotion_code_id' =>  $code->id,
            'used'              =>  0
        ]);
        return $code;

    }

    public function checkPromoCode()
    {
        $validator = Validator::make(request()->all(),[
            'promo_code' => 'required|exists:promotion_codes,code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'message'   => Lang::get('messages.promotion_not_exists')
            ], 403);
        }
        $code = request('promo_code');
        $code = PromotionCode::whereCode($code)->validateCode()
                                ->whereLimitPerUser(0)
                                ->first();
        if(!$code)
            return response()->json([
                'success'   =>  false,
                'message'   =>  Lang::get('messages.promotion_code_expire')
            ],403);
        return $code;


    }


    public function removePromo()
    {
        $code = PromotionCode::whereCode(request('promo'))->first();
        UsedPromotionalCode::whereUserId(Auth::id())
                            ->wherePromotionCodeId($code->id)
                            ->whereUsed(0)
                            ->delete();

        return response()->json([
            'success'   => false,
            'message'   => Lang::get('messages.promo_removed')
        ],200);
    }

}
