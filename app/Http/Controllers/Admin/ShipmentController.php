<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shipment\Shipment;
use Lang;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Shipment::all();
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->validate([
            'name'  => 'required',
            'description' => 'required',
            'cost' => 'required',
        ]);
        
        Shipment::create($request);

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.shipment_add')
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Shipment $shipment)
    {
        return $shipment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request = $request->validate([
            'name'  => 'required',
            'description' => 'required',
            'cost' => 'required',
        ]);
        
        $shipment->update($request);

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.shipment_update')
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.shipment_delete')
        ],200);
    //
    }
}
