<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Order::with(['user','products','address','orderStatus','shipment.deliveryStatus'])->get();
        
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
        return Order::with(['user','products','address','orderStatus','shipment.deliveryStatus'])->where('reference', $id)->first();
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

    public function pending()
    {
        return Order::pending()->with(['user','products','address','orderStatus','shipment.deliveryStatus'])->get();
    }

    public function completed()
    {
        return Order::completed()->with(['user','products','address','orderStatus','shipment.deliveryStatus'])->get();
    }

    public function cancelled()
    {
        return Order::cancel()->with(['user','products','address','orderStatus','shipment.deliveryStatus'])->get();
    }

    public function confirmed()
    {
        return Order::confirmed()->with(['user','products','address','orderStatus','shipment.deliveryStatus'])->get();
    }
}
