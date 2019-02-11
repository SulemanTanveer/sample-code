<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Promotion\PromotionCode;
use Illuminate\Http\Request;
use App\Http\Requests\PromotionCodeRequest;
use Lang;
use App\Models\Order\Order;

class PromotionCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PromotionCode::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PromotionCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PromotionCodeRequest $request)
    {
        PromotionCode::create($request->except('user'));
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.promotion_code_add')
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param PromotionCode $promotion_code
     * @return \Illuminate\Http\Response
     */
    public function show($promotion_code)
    {
        return PromotionCode::whereId($promotion_code)->with(['product','user'])->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PromotionCode $promotion_code
     * @param PromotionCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PromotionCode $promotion_code, PromotionCodeRequest $request)
    {
        $promotion_code->update($request->except('user'));

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.promotion_code_update')
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PromotionCode $promotion_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PromotionCode $promotion_code)
    {
        $promotion_code->delete();

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.promotion_code_delete')
        ],201);
    
    }
    /**
     * [validate description]
     * @param  [type]  $code    [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function validatePromotionCode($code)
    {
        $code = PromotionCode::whereCode($code)->validateCode()->firstOrFail();
        
        if ($code->limit_per_user == false) {
            return response([
                'discount' => $code->discount,
            ], 200);
        } else {
            $findPromotionCodeUsed = Order::where(['user_id' => auth()->user()->id, 'promotion_code_id' => $code->id])->first();
            
            if ($findPromotionCodeUsed) {
                return response('promotion code already used', 403);
            } else {
                return response([
                    'discount' => $code->discount,
                ], 200);   
            }
        }
    }
}
