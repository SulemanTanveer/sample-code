<?php

namespace App\Http\Controllers\LogisticManager;

use App\Models\Bundle\Bundle;
use App\Models\Bundle\BundlePicture;
use App\Models\Product\Product;
use App\Models\Product\ProductPicture;
use App\Models\Product\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
use App\Http\Requests\BundleRequest;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::bundle()->with('pictures')->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BundleRequest $request)
    {
        //add bundle as a product
        $type = ProductType::whereId(1)->first();
        if(!$type)
           return response()->json([
               'success'=>false,
               'message'=> Lang::get('messages.no_bundle_type')
           ],403);
        $request['type_id'] = $type->id;
        $addProduct = Product::addNewProduct($request);

        $images = $request->pictures;
        foreach ($images as $image)
        {
            $product_image = new ProductPicture(['url'=>$image['url'],'is_main'=>false]);
            $addProduct->pictures()->save($product_image);
        }

        $products = $request->bundle_product_list;
        foreach ($products as $product)
        {
            $addProduct->bundleProducts()->attach($product['id'], ['product_quantity' => $product['quantity']]);
        }

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.bundle_add')
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
        return  Product::whereId($id)->with('pictures')->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
public function update(BundleRequest $request,  $id)
{
    $product = Product::find($id);
    $product->name = $request->name;
    $product->ean = $request->ean;
    $product->code = $request->code;
    $product->description = $request->description;
    $product->short_description = $request->short_description;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->save();

    $images = $request->pictures;
    foreach ($images as $image)
    {
        ProductPicture::updateOrCreate([
            'product_id' => $product->id,
            'url' => $image['url']
        ],[
            'is_main' => false,
            'product_id' => $product->id,
            'url' => $image['url']
        ]);
    }
    $product->bundleProducts()->detach();
    $prods = $request->bundle_product_list;
    foreach ($prods as $prod)
    {
        $product->bundleProducts()->attach($prod['id'], ['product_quantity' => $prod['quantity']]);
    }
    return response()->json([
        'success'   =>  true,
        'message'   =>  Lang::get('messages.bundle_update')
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
        Product::whereId($id)->delete();
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.bundle_delete')
        ],202);
    }
    public function searchBundle($keyword)
    {
        return Product::bundle()->where('ean','like','%'.$keyword.'%')
            ->orWhere('code','like','%'.$keyword.'%')
            ->orWhere('name','like','%'.$keyword.'%')
            ->paginate(20);
    }
}
