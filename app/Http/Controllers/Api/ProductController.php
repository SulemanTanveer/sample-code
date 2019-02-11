<?php

namespace App\Http\Controllers\Api;

use App\Models\Product\ProductColor;
use App\Models\Product\ProductSize;
use App\Models\Product\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Filters\ProductsFilters;
use App\Models\Category\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProductsFilters $filters)
    {
        $category = $request->category;
        if($request->per_page == -1 )
        {
            if(request('category'))
                return  Product::whereTypeId($category)
                    ->with('pictures')->get();
            else
                return Product::with('pictures')->get();

        }

        $per_page = $request->per_page ? $request->per_page : 12;
        $search = $request['search'] ?? '';

        if ($category) {
            return Product::whereTypeId($category)
                ->with('pictures')
                ->paginate($per_page);

        }if ($category > 0 && $search) {
            return Product::whereTypeId($category)
                ->where('name', 'like', '%'.$search.'%')
                // ->where('description', 'like', '%'.$search.'%')
                ->with('pictures')
                ->paginate($per_page);

        }

        if ($search) {    
            return Product::where('name', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->with('pictures')
                ->paginate($per_page);

        }
        return Product::where('name', 'like', '%'.$search.'%')
                // ->where('description', 'like', '%'.$search.'%')
                ->with('pictures')
                ->paginate($per_page);

        // return $products;




        // $per_page = $request->per_page ? $request->per_page : 10;
        
        // $products = Product::latest('id')->filter($filters);
        
        // if ($category->exists) {
        
        //     $products = $products->byCategories($category);
        
        // }

        // return  $products->with('pictures')->paginate($per_page);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::with(['types','status','colors','sizes','pictures'])->findOrFail($id);
    }

    /**
     * @param $product_id
     * @param $quantity
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function checkQuantity($product_id, $quantity)
    {
        return Product::checkQuantity($product_id, $quantity);
    }

    /**
     * [product types return ]
     * @return [type] [description]
     */
    public function types()
    {
        return ProductType::all();
    }
    
    /**
     * [product sizes return]
     * @return [type] [description]
     */
    public function sizes()
    {
        return ProductSize::all();
    }
    
    /**
     * [product colors return]
     * @return [type] [description]
     */
    public function colors()
    {
        return ProductColor::all();
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function productQuantity(Request $request)
    {
        if (is_array($request->product_ids)) {
            return Product::whereIn('id', $request->product_ids)->get(['id', 'quantity']);
        } else {
            return response('param not valid', 403);
        }
    }
    
    public function search($query_param)
    {
        $product = Product::where('name','LIKE','%'.$query_param.'%')->limit(20)->get();
        return $product;
    }

}