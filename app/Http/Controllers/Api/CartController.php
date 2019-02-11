<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart\ShoppingCart;
use App\Models\Cart\CartItem;
use App\Models\Product\Product;
use App\Classes\Cart;
use App\Http\Requests\CartRequest;
use App\Models\Children\Child;
use Lang;
class CartController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * [store product in cart]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|numeric',
            'child_id'   =>  'sometimes|required|exists:children,id',
        ]);

        $childId = $request->child_id ? $request->child_id : NULL;

        $cart = ShoppingCart::firstOrCreate([
            'user_id' => auth()->user()->id,
            'child_id' => $childId
        ]);

        $cart->cartItems()->updateOrCreate([
            'product_id' => $request->product_id,
        ],[
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return response([
            'message' => Lang::get('messages.add_to_cart'),
            'child_id' => $childId
        ], 200);
    }
    /**
     * [show cart of specific user]
     * @return [type] [description]
     */
    public function show()
    {
        return response([
            'data' => auth()->user()->shoppingCart
        ] ,200);
    }

    /**
     * [remove product from cart list]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request)
    {
        $cart = ShoppingCart::byChild($request->child_id)->first();

        if (!$cart) {
            return response('cart not found', 404);
        }

        $cart->cartItems()->where('product_id', $request->product_id)->delete();

        if (count($cart->cartItems()->get()) ==0) {
            $cart->delete();
        }

        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.remove_from_cart')
        ],201);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function supplyListAddToCart(CartRequest $request)
    {
        if (!empty($request->data)) {
            foreach ($request->data as $data) {

                if(isset($data['city_id'])){
                    $child = Child::addChild($data);
                }

                $childId = isset($child) ? $child->id : NULL;

                ShoppingCart::addShoppinCart($data, $childId);
            }
        } else {
            auth()->user()->shoppingCart()->delete();
        }

        return response()->json([
            'child_id'  => isset($childId) ? $childId : NULL
        ],200);
    }

    /**
     * [validateCart items quantity]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function validateCart(Request $request)
    {
        $request->validate([
            'supply_list' => 'required|array'
        ]);

        $cart = new Cart;
        foreach ($request->supply_list as $product) {
            $invalid = $cart->ValidateProductQuantity($product['product_id'], $product['quantity']);
        }
        if ($invalid) {
            return response([
                'inVaildProduct' => $invalid
            ], 403);
        } else {
            return response('', 203);
        }
    }

}