<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\Services\LemonWay;
use Log;

class PaymentController extends Controller
{
    protected $lemonWay;

    function __construct(LemonWay $lemonWay)
    {
        auth()->loginUsingId(1, true);

        $this->lemonWay = $lemonWay;
    }
    
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
        try {
            $paymentUrl = $this->lemonWay->callService('MoneyInWebInit', array(
                "amountTot" => $this->totalAmount(auth()->user()->shoppingCart),
                "amountCom" => "2.00",
                "walletIp" => '127.0.0.1',//$request->ip(),
                "walletUa" => $request->header('User-Agent'),
                "isPreAuth" => 1,
                "returnUrl" => $this->successUrl(),
                "cancelUrl" => $this->failureUrl(),
                "errorUrl" => $this->failureUrl()
            ));
            return response()->json(array("paymentUrl" => $paymentUrl));
        }
        catch (\Exception $e) 
        {
            Log::error($e);
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    protected function totalAmount($shoppingCarts)
    {
        $totalAmount = 0;
        foreach ($shoppingCarts as $shoppingCart) {
            foreach ($shoppingCart->cartItems as $cart_item) {
                $totalAmount = $totalAmount + ($cart_item->quantity*$cart_item->price);
            }
        }
        return $totalAmount;
    }
}
