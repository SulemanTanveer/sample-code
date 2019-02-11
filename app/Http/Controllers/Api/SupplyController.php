<?php

namespace App\Http\Controllers\Api;

use App\Mail\RequestSupplyList;
use App\Models\Children\Child;
//use App\Models\City;
use App\Models\City;
use App\Models\Level\Level;
use App\Models\Product\Product;
use App\Models\Product\ProductType;
use App\Models\School\School;
use App\Models\SupplyList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Mail, Validator;

class SupplyController extends Controller
{

    protected $product_details = [
        'sizes',
        'colors'
    ];
    public function index()
    {
        $supply_list = SupplyList::where('school_id',request('school_id'))
                        ->where('school_level_id',request('school_level_id'))
                        ->first();

        if(!$supply_list)
            return response()->json([
                'success'   => false,
                'message'   =>  Lang::get('messages.no_supply')
            ],403);
        if(request('handy')){
            return [
                'id'=>$supply_list->id,
                'no_of_products_req'=> $supply_list->no_of_products_req,
                'pdf_url'=>$supply_list->pdf_url,
                'products' => (request('handy') == 1) ? $supply_list->products_left : $supply_list->products_right
                // 'products'=>$supply_list->products()->where('handy',request('handy'))->with($this->product_details)->get()
            ];
        }
        else{
            return [
                'id'=>$supply_list->id,
                'no_of_products_req'=> $supply_list->no_of_products_req,
                'pdf_url'=>$supply_list->pdf_url,
                'products'=>$supply_list->products()->with($this->product_details)->get()
            ];
        }
    }
    public function schoolsByCity()
    {
//        $zip = mb_substr(request('zip'), 0, 3)??'';
        $zip = request('zip');
        if(!request('city_id'))
            return School::whereHas('city',function ($query) use($zip){
                $query->where('postal_code',$zip);
            })->get();

        $city = request('city_id');

        return School::whereHas('city',function ($query) use($city,$zip){
                $query->whereId($city)
                        ->where('postal_code',$zip);
            })
            ->get();
    }

    public function cities()
    {
        return City::where('name','like',request('query').'%')
            ->orWhere('postal_code','like',request('query').'%')
            ->take(20)
            ->get();
    }

    public function schoolLevels(School $school)
    {
//        return $school->levels()->get();
        $levels = SupplyList::whereSchoolId($school->id)->distinct()->pluck('school_level_id');
        return Level::whereIn('id',$levels)->get();
    }

}
