<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionCodeRequest extends FormRequest
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
        if(request()->isMethod('put') or request()->isMethod('patch'))
        {
            return [
                'code' => 'required|alpha_num|size:8|unique:promotion_codes,code,'.$this->route()->parameter('promotion_code.id'),
                'discount' => 'required|min:0|max:100',
                'limit_per_user' => 'boolean',
                'expiry_date' => 'required|date',
                'promotion_type_id' => 'required'
            ];
        }

        return [
            'code' => 'required|alpha_num|size:8|unique:promotion_codes,code',
            'discount' => 'required|min:0|max:100',
            'limit_per_user' => 'boolean',
            'expiry_date' => 'required|date',
            'promotion_type_id' => 'required'
        ];
    }
}
