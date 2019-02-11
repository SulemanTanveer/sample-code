<?php

namespace App\Http\Controllers\LogisticManager;

use App\Models\Product\Product;
use App\Models\Product\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
class ProductTypesController extends Controller
{

    public function index()
    {
        return ProductType::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ProductType::create($request->all());
        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.type_added')
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ProductType::whereId($id)->first();
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
        ProductType::whereId($id)->update($request->all());
        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.type_updated')
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::whereTypeId($id)->delete();
        ProductType::whereId($id)->delete();
        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.type_delete')
        ],200);
    }
}
