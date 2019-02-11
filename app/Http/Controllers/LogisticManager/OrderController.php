<?php

namespace App\Http\Controllers\LogisticManager;

use App\Models\Order\BillingAddress;
use App\Models\Order\DeliveryAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Notifications\OrderConfirmed;
use App\Notifications\OrderCompleted;
use App\Notifications\OrderCancelled;
use App\Models\User\UserAddress;
use Lang;

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
        return Order::with(['user','products','address','orderStatus','shipment.deliveryStatus','billing_address','delivery_address'])->where('reference', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

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
     * [pending order]
     * @return [type] [order]
     */
    public function pending()
    {
        return Order::pending()->with(['user','products','address','orderStatus','shipment.deliveryStatus','billing_address','delivery_address'])->get();
    }
    /**
     * [confirmed order]
     * @return [type] [order]
     */
    public function confirmed()
    {
        return Order::confirmed()->with(['user','products','address','orderStatus','shipment.deliveryStatus','billing_address','delivery_address'])->get();
    }
    /**
     * [completed order]
     * @return [type] [order]
     */
    public function completed()
    {
        return Order::completed()->with(['user','products','address','orderStatus','shipment.deliveryStatus','billing_address','delivery_address'])->get();
    }
    /**
     * [cancelled order]
     * @return [type] [order]
     */
    public function cancelled()
    {
        return Order::cancelled()->with(['user','products','address','orderStatus','shipment.deliveryStatus','billing_address','delivery_address'])->get();
    }
    /**
     * [orderStatusChange]
     * @param  [type] $orderId  [description]
     * @param  [type] $statusId [description]
     * @return [type]           [description]
     */
    public function orderStatusChange($orderId, $statusId)
    {
        $status = OrderStatus::findOrFail($statusId);
        $order = Order::findOrFail($orderId);

        $order->update([
            'order_status_id' => $status->id
        ]);

        switch ($statusId) {
            case 2:
                $order->user->notify(new OrderConfirmed($order));
                return response()->json([
                    'success'   =>  true,
                    'message'   =>  Lang::get('messages.order_confirmed')
                ],200);
                break;
            case 3:
                // $order->user->notify(new OrderCompleted($order));

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  Lang::get('messages.order_completed')
                ],200);
                break;
            case 4:
                $order->user->notify(new OrderCancelled($order));
                return response()->json([
                    'success'   =>  true,
                    'message'   =>  Lang::get('messages.order_cancelled')
                ],200);
                break;
            default:
               return response()->json([
                    'success'   =>  false,
                    'message'   =>  'error'
                ],200);
                break;
        }
    }
    /**
     * [invoice orderAddress update]
     * @param  [type]  $address [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function orderAddress($address, Request $request)
    {
        $addressValidate = $request->validate([
            'name' => 'required',
            'street_1' => 'required',
            'street_2' => 'required',
            'zip' => 'required',
            'city' => 'required',
        ]);

        $address = UserAddress::findOrFail($address);

        $address->update($addressValidate);

        return response()->json([
            'success'   =>  false,
            'message'   =>  Lang::get('messages.address')
        ],200);
    }
    /**
     * [orderWidgets return count]
     * @return [type] [order status count]
     */
    public function orderWidgets()
    {
        $data = array(
            'pending' => Order::totalPendingOrders(),
            'confirmed' => Order::totalConfirmedOrders(),
            'completed' => Order::totalCompletedOrders(),
            'cancelled' => Order::totalCancelledOrders(),
        );
        return response([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function updateBillingAddress($id)
    {
        $billing_address = BillingAddress::whereId($id)->first();
        $billing_address->update(request()->all());
        return response()->json([
            'success'   => false,
            'message'   =>  Lang::get('messages.billing_address_update')
        ],200);

    }

    public function updateDeliveryAddress($id)
    {
        $delivery_address = DeliveryAddress::whereId($id)->first();
        $delivery_address->update(request()->all());
        return response()->json([
            'success'   => false,
            'message'   =>  Lang::get('messages.delivery_address_update')
        ],200);
    }
}
