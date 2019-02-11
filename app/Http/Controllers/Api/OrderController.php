<?php

namespace App\Http\Controllers\Api;

use App\Models\Order\BillingAddress;
use App\Models\Order\DeliveryAddress;
use App\Models\Promotion\UsedPromotionalCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Invoice;
use App\Events\NewOrder;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Auth;
use Log,Mail,Lang;

class OrderController extends Controller
{
    // function __construct()
    // {
    //     auth()->loginUsingId(1, true);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with('products')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $shoppingCarts = auth()->user()->shoppingCart;

        foreach ($shoppingCarts as $shoppingCart) {
            
        }

        // NewOrder::dispatch($order);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::with(['products','billing_address','delivery_address'])->where('id',$id)->first();
        
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

    public function successPost(Request $request, $reference){
        //$order = Order::whereReference($reference)->first();
        $order = Order::with(['delivery_address', 'billing_address', 'user','products','address','orderStatus','shipment.deliveryStatus'])
            ->where('reference', $reference)
            ->first();
        $order->transaction_id = $request->response_transactionId;
        $order->order_status_id = 1;
        $order->save(); 

        $order->user->shoppingCart()->delete();
        UsedPromotionalCode::whereUserId($order->user_id)->whereUsed(0)->update(['used'=>1,'order_id'=>$order->id]);

//        Mail::to($order->user->email)->send(new Invoice($order));

       NewOrder::dispatch($order);

        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.email_sent')
        ],200);

    }
    public function successGet(Request $request, $reference)
    {
        return redirect(env('FRONT_END_URL').'paiement/success');
    }

    public function failurePost(Request $request, $reference){
        $order = Order::whereReference($reference)->first();
        // $order->delete();
        return 'Delete Successful';

    }
    public function failureGet(Request $request, $reference)
    {
        $order = Order::whereReference($reference)->first();
        // $order->delete();
        return redirect(env('FRONT_END_URL').'paiement/failed');
    }

}
