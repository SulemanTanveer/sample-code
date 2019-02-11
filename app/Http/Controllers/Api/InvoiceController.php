<?php

namespace App\Http\Controllers\Api;

use App\Mail\Invoice;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use PDF;
use App\User;
use Auth, Mail;
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $order = Order::where('reference', $reference)->firstOrFail();
        $order = Order::where('id', 14)->firstOrFail();
        $products_list = collect($order->products);
        $ordered_products = $products_list->map(function($item) {
            return [
                'product_id'=>$item['product_id'],
                'quantity'=>$item['quantity']
            ];
        });
        return $ordered_products;
        return $products_list;
        $sumArray = [];//collect();
        foreach ($products_list as $key=>$value)
        {
            if(in_array($value['product_id'],$sumArray))
            {
                $sumArray[$key]['quantity'] += $value['quantity'];
            }
            else
                $sumArray[$key]['product_id']= $value['product_id'];
        }
        $listing = $products_list->every(function($value, $key) use($sumArray) {

            foreach ($value as $val)
            {

                $p['quantity']=$val;
            }
//                if(key_exists($ke));
            $sumArray[$key]= $value;

            dd($value,$key);
            return $value->reduce(function($val){

            });

        });

        return $sumArray;
        return $products_list;
        $p_list = [] ;
        foreach ($products_list as $key=>$value):
            if(!in_array($value['product_id'],$p_list))
                array_push($p_list,$value);
        endforeach;

        $p = $products_list->map(function($product) use ($p_list){
//            if(!in_array($product, $p_list))


        });

        return $products_list;
        $user = $order->user->email;
        Mail::to($user)->send(new Invoice($order));

        return response()->json([
            'success'   =>  true,
            'message' => Lang::get('messages.password_reset')
        ], 200);
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
    public function showInvoice($reference)
    {
        $order = Order::whereReference($reference)->with(['billing_address','delivery_address'])->firstOrFail();
        return PDF::loadView('emails/order-invoice', compact('order'))->download();
    }
}
