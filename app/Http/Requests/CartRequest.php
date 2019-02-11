<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data'                =>    'nullable|array',
//            'data.*.firstname'    =>    'sometimes|required',
//            'data.*.surname'      =>    'sometimes|required',
            'data.city'           =>    'sometimes|required',
            'data.*.school_id'    =>    'sometimes|required|exists:schools,id',
            'data.*.school_level_id'   =>  'sometimes|required|exists:levels,id',
            'data.*.supply_list'       =>  'nullable|array',
            'data.*.supply_list.*.product_id'  =>  'sometimes|required|exists:products,id',
            'data.*.supply_list.*.quantity'    =>  'sometimes|required',
        ];
    }
}
