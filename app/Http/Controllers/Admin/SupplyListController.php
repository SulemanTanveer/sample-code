<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Product\Product;
use App\Models\SupplyList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang;
use League\Csv\Reader;
use League\Csv\Statement;
class SupplyListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SupplyList::with(['school','level'])->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supply_list = SupplyList::where('school_id',$request->school_id)
                    ->where('school_level_id',$request->school_level_id)
                    ->where('city_id',$request->city_id)
                    ->first();

        if($supply_list)
            return response()->json([
                'success'   => false,
                'message'   => Lang::get('messages.supply_exists')
            ],400);

        $supply_list = SupplyList::create([
            'school_id'         =>  $request->school_id,
            'school_level_id'   =>  $request->school_level_id,
            'city_id'           =>  $request->city_id,
            'no_of_products_req'=> $request->no_of_products_req
        ]);
        $product_lists_left = $request->products_left;
        $product_lists_right = $request->products_right;
        if($product_lists_left) {
            foreach ($product_lists_left as $product) {
                $supply_list->products()->attach($product['id'], ['product_quantity' => $product['quantity'], 'handy' => 1]);
            }
        }
        if($product_lists_right){
            foreach ($product_lists_right as $product)
            {
                $supply_list->products()->attach($product['id'],['product_quantity' => $product['quantity'],'handy'=>2]);
            }
        }

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.supply_add'),
            'id'        => $supply_list->id
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  SupplyList  $supplylist
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return SupplyList::whereId($id)->first();
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
        $supply_list = SupplyList::whereId($id)->first();

        if($request->hasFile('supplyListFile'))
        {
            $UP_PDF_PATH =  storage_path('app/public/pdf');
            if (!is_dir($UP_PDF_PATH)) {
                mkdir($UP_PDF_PATH,0777, true);
            }
            $supply_pdf = $request->file('supplyListFile');
            $name = "supply_$id._".time().".".$supply_pdf->getClientOriginalExtension();
            $supply_pdf->move($UP_PDF_PATH, $name);
            $supply_list->pdf_url = $name;
            $supply_list->save();
            return response()->json([
                'success'=>true
            ],201);
        }

        if($request->hasFile('supplyListFileProducts'))
        {
            $UP_CSV_PATH =  storage_path('app/public/csv');
            if (!is_dir($UP_CSV_PATH)) {
                mkdir($UP_CSV_PATH,0777, true);
            }
            $supply_prod_csv = $request->file('supplyListFileProducts');
            $name = "supply_$id._".time().".".$supply_prod_csv->getClientOriginalExtension();
            $supply_prod_csv->move($UP_CSV_PATH, $name);
            $csv = Reader::createFromPath($UP_CSV_PATH."/$name", 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0); //set the CSV header offset
            $stmt = (new Statement());
            $records = $stmt->process($csv);
            $supply_list->products()->detach();
            foreach ($records as $record) {
                $product = Product::whereEan($record['ean'])->first();
                if(isset($record['qty']))
                    $quantity = $record['qty'];
                else
                    $quantity = 1;
                if(!$product)
                    continue;
                if( $record['main'] === 'droite')
                    $handy = 2;
                elseif ( $record['main'] === 'gauche')
                    $handy = 1;
                else
                    return response()->json([
                        'success'   =>  false,
                        'message'   =>  Lang::get('messages.incorrect_hand_csv')
                    ],403);
//                if($supply_list->products->contains($product['id']))
//                        $supply_list->products()->detach([$product['id']]);

                $supply_list->products()->attach($product['id'],['product_quantity' => $quantity,'handy'=>$handy]);
            }
            return response()->json([
                'success'=>true,
                'message'=>Lang::get('messages.supply_update')
            ],201);
        }

        $supply_list->city_id = $request->city_id;
        $supply_list->school_id = $request->school_id;
        $supply_list->school_level_id = $request->school_level_id;
        $supply_list->no_of_products_req = $request->no_of_products_req;

        $supply_list->save();

        $product_lists_left = $request->products_left;
        $product_lists_right = $request->products_right;
        $supply_list->products()->detach();
        if($product_lists_left)
        {
            foreach ($product_lists_left as $product)
            {
                $supply_list->products()->attach($product['id'],['product_quantity' => $product['quantity'],'handy'=>1]);
            }
        }
        if($product_lists_right)
        {
            foreach ($product_lists_right as $product)
            {
                $supply_list->products()->attach($product['id'],['product_quantity' => $product['quantity'],'handy'=>2]);
            }
        }

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.supply_update')
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SupplyList::whereId($id)->delete();
        
        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.supply_delete')
        ],200);
    }
    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        return SupplyList::with(['products','school','level'])->findOrFail($id);
    }

    public function replaceProductInSupplyList(Request $request)
    {

    }

    public function searchSupplyList($keyword)
    {
       return SupplyList::whereHas('city',function($query)use ($keyword){
                $query->where('name','like','%'.$keyword.'%');
            })->orWhereHas('school',function($query)use ($keyword){
                $query->where('name','like','%'.$keyword.'%');
            })->with(['school', 'level'])
            ->paginate(20);
    }
}
