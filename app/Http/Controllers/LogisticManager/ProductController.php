<?php

namespace App\Http\Controllers\LogisticManager;

use App\Models\Product\ProductColor;
use App\Models\Product\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductPicture;
use App\Http\Requests\ProductRequest;
use League\Csv\Reader;
use League\Csv\Statement;

use Lang, File;
use Illuminate\Filesystem\Filesystem;
class ProductController extends Controller
{
    /**
     * [__construct]
     */
    function __construct()
    {
        // $this->middleware('auth:api');
        // $this->middleware('role:LOGISTICMANAGER');
    }
    
    /**
     * Display a listing of the product.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::noBundle()->with(['pictures','types','status','colors','sizes'])->paginate(20);
    }

    /**
     * [store product data]
     * @param  ProductRequest $request [description]
     * @return [type]                  [description]
     */
    public function store(ProductRequest $request)
    {
        $addProduct = Product::addNewProduct($request);

        $colors = collect($request->colors)->pluck('id');
        $sizes = collect($request->sizes)->pluck('id');

        $addProduct->colors()->sync($colors);

        $addProduct->sizes()->sync($sizes);

        $images = $request->pictures;
        if($images)
        {
            foreach ($images as $image)
            {
                $product_image = new ProductPicture(['url'=>$image['url'],'is_main'=>false]);
                $addProduct->pictures()->save($product_image);
            }
        }

        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.product_save')
        ],200);

    }

    
    /**
     * [display the specific product]
     * @param  Product $product [description]
     * @return [type]           [description]
     */
    public function show($productId)
    {
        return Product::with(['pictures','types','status','colors','sizes'])->findOrFail($productId);
    }

    /**
     * [update the product]
     * @param  Request $request [description]
     * @param  Product $product [description]
     * @return [type]           [description]
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->name = $request->name;
        $product->type_id = $request->type_id;
        $product->description = $request->description;
        $product->other_details = $request->other_details;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->order_number = $request->order_number;
        $product->purchase_date = $request->purchase_date;
        $product->status_id = $request->status_id;
        $product->save();

        $colors = collect($request->colors)->pluck('id');
        $sizes = collect($request->sizes)->pluck('id');

        $product->colors()->sync($colors);

        $product->sizes()->sync($sizes);
        $product->pictures()->delete();
        $images = $request->pictures;
        if($images)
        {
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
        }
        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.product_update')
        ],200);
    }

    /**
     * [delete product data]
     * @param  Product $product [description]
     * @return [type]           [description]
     */
    public function destroy(Product $product)
    {
        $product->colors()->detach();
        $product->sizes()->detach();
        $product->pictures()->delete();
        $product->carts()->delete();
        $product->delete();

        return response()->json([
            'success'=>true,
            'message'=>Lang::get('messages.product_delete')
        ],200);
    }
    
    /**
     * [imagesUpload of the product]
     * @param  Request $request   [description]
     * @param  [type]  $productId [description]
     * @return [type]             [description]
     */
    public function imagesUpload(Request $request, $productId)
    {
        $pictures = ProductPicture::whereProductId($productId)->get();
 
        foreach ($pictures as $picture) {
            unlink($picture->url);
        }
 
        if ($request->images) {
            foreach($request->images as $image)
            {
                $name = time().str_random(16).'.'.$image->getClientOriginalName();
                $image->move(storage_path().'/products/images/', $name);
                
                ProductPicture::updateOrCreate([
                    'product_id' => $productId,
                    'url' => $name
                ]);  
            }
        }
    }
    public function updateQuantity(Request $request, Product $product)
    {
        $quantity = $request->validate([
            'quantity' => 'required|numeric'
        ]);

        $product->updateQuantity($request->quantity);

        return response('quantity updated', 201);
    }
    /**
     * @param Request $request
     * @return string
     */
    public function fileUpload(Request $request)
    {
        if($request->file('file'))
        {
            $image = $request->file('file');
            $name = uniqid().".".$image->getClientOriginalExtension();
            $image->move(storage_path('app/public/images/products/'), $name);
            return array('url' => $name);
        }
        return array('url' =>'default.png');
    }

    public function searchProduct($keyword)
    {
        return Product::noBundle()
            ->where('ean','like','%'.$keyword.'%')
            ->orWhere('code','like','%'.$keyword.'%')
            ->orWhere('name','like','%'.$keyword.'%')
            ->paginate(20);
    }

    public function importCsv(Request $request)
    {
        if($request->file('file')) {
            $image = $request->file('file');
            $name = time() . "." . $image->getClientOriginalExtension();
            $image->move(storage_path('app/public/csv/'), $name);
        }
        else {
            return response()->json([
                'success'=>false,
                'message'=>Lang::get('messages.uploaded_failed')
            ],403);
        }

        $csv = Reader::createFromPath(storage_path('app/public/csv/'.$name), 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0); //set the CSV header offset

        //get 5 records starting from the 1st row
        $stmt = (new Statement())
            ->offset(0)
        ;
        $records = $stmt->process($csv);
        foreach ($records as $record) {
            $color = '';
            if($record['EAN'] =='' || $record['CODE']=='')
                break;
            $type = ProductType::firstOrCreate([
                'type'          =>  $record['CATEGORIES']
            ]);
            if($record['COLOR'])
            $color = ProductColor::firstOrCreate([
                'name'  => $record['COLOR'],
                'code'  =>''
            ]);

            $other_details = $record['KSP1'].". ".$record['KSP2'].". ".$record['KSP3'].". ".$record['KSP4'].". ".$record['KSP5'].". ".$record['KSP6'];//."\n".$record['DESC_LONG'];

            $product = Product::updateOrCreate(
                [
                    'ean'   => $record['EAN'],
                    'code'  => $record['CODE'],
                ],
                [
                    'name' => $record['TITLE'],
                    'slug' => str_slug($record['TITLE']),
                    'description' => $record['DESC_LONG'],
                    'other_details' => $other_details,
                    'short_description' =>$record['DESC_SHORT'],
                    'type_id' => $type->id,
                    'price' => str_replace(',','.',$record['PRICE']),
                    'quantity' => -1,
                    'status_id' =>  1
                ]);
            if($color)
                $product->colors()->sync($color->id);
        }
        return response()->json([
            'message'=> Lang::get('messages.csv_uploaded')
        ],200);
    }

    public function importImages(Request $request)
    {
        $ZIP_EXTRACT = public_path('extract');
        $ZIP_PRODUCTS = public_path('zip-products');
        $UP_IMG_PATH =  storage_path('app/public/images/products/');
        if (!is_dir($UP_IMG_PATH)) {
            mkdir($UP_IMG_PATH);
        }
        try{
            if ($request->file('file')) {
                $zip = $request->file('file');
                $name = time().".".$zip->getClientOriginalExtension();
                $zip->move($ZIP_PRODUCTS, $name);
            }
            else {
                return response()->json([
                    'success'=>false,
                    'message'=>Lang::get('messages.uploaded_failed')
                ],403);
            }
            $zip = new \ZipArchive();
            $res = $zip->open(public_path('zip-products/'.$name));
            if ($res === TRUE) {
                $zip->extractTo($ZIP_EXTRACT);
                $zip->close();
                $Directory = new \RecursiveDirectoryIterator($ZIP_EXTRACT);
                $Iterator = new \RecursiveIteratorIterator($Directory);

                $products = Product::get(['id','ean','code']);
                foreach ($products as $product) {
                    foreach ($Iterator as $file) {
                        $_fn = $file->getFilename();
                        if(substr( $_fn, 0,  1) === ".")
                            continue;
                        if (strpos($_fn, $product->ean)!== false || strpos($_fn, $product->code)!== false || strpos($_fn, $product->name)!== false) {
                            ProductPicture::firstOrCreate([
                                'product_id' => $product->id,
                                'url' => $file->getFileName()
                            ]);
                            copy($file->getPathname(),
                                $UP_IMG_PATH.$_fn
                            );
                            unlink($file->getPathname());
                        }
                    }
                }
                $file = new Filesystem;
                $file->cleanDirectory($ZIP_PRODUCTS);
                $file->cleanDirectory($ZIP_EXTRACT);
            }
            return response()->json([
                'success'=>true,
                'message'=>Lang::get('messages.img_uploaded')
            ],200);
        }
        catch (\Throwable $throwable)
        {
            return response()->json([
                'success'=>false,
                'message'=>$throwable->getMessage()
            ],403);
        }

    }

    public function removeImage($p_id,$pic)
    {
        $product = Product::whereId($p_id)->first();
        $product->pictures()->detach([$pic->id]);
        unlink(storage_path('app/public/images/products').$pic->url);
    }

}
