<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

use App\Models\Order\Order;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Events\NewOrder;


Route::get('/home', 'HomeController@index')->name('home');

Route::get('invoice/{id}', function($id) {
    $order = Order::where('reference', $id)->with(['delivery_address', 'billing_address', 'user', 'products', 'orderStatus', 'shipment.deliveryStatus','delivery_address','billing_address'])->first();
    
    //return $order;
    // return view('emails.order-invoice', ['order'=> $order]);
    NewOrder::dispatch($order);
    return "Email Sent.";
});

Route::get('seo-data', function(){
    $csv = Reader::createFromPath(public_path('seo.csv'), 'r');
    $csv->setHeaderOffset(0); //set the CSV header offset
    $stmt = (new Statement());

    $records = $stmt->process($csv);
    foreach ($records as $record) {
        $record['url'] = "https://rentree-zen.fr/annuaire/".str_slug($record['ecole']." ".$record['ville']." ".$record['cp']);
        $record['slug']= str_slug($record['ecole']." ".$record['ville']." ".$record['cp']);
        \App\Seo::create($record);
    }
});
//use Carbon\Carbon;
//use Mail;
Route::get('csv', function(){
//    $orders = Order::pending()->with(['user','products','delivery_address'])->get();
////return $orders;
//    $name = Carbon::now()->toDateString();
//        Excel::create("$name._order", function($excel) use($orders) {
//            $excel->sheet('ExportFile', function($sheet) use($orders) {
//                $data [] = [
//                    "reference_client",     "nom",
//                    "prenom",           "adresse_1",
//                    "adresse_2",   "code_postal",
//                    "ville",            "departement",
//                    "pays", "telephone", "mobile","reference_commande",	"ean",	"code",	"quantite",	"prix_unitaire",	"prix_reduis",	"prix_total","nom_produit",	"couleur_produit"
//
//                ];
//
//                foreach ($orders as $order) {
//                    foreach($order->products as $product)
//                    {
//                        $data[] = [
//                            $order->user->id,
//                            $order->delivery_address->surname,//$order->user->profile->surname,
//                            $order->delivery_address->firstname,//$order->user->profile->firstname,
//                            $order->delivery_address->street_1,
//                            ' ',
//                            $order->delivery_address->zip,
//                            $order->delivery_address->city,
//                            ' ',
//                            ' ',
//                            $order->delivery_address->telephone,
//                            ' ',
//                            $order->reference,
//                            $product->product->ean,
//                            $product->product->code,
//                            $product->quantity,
//                            $product->price,
//                            '',//'reduce_price',
//                            $product->quantity*$product->price,
//                            $product->name,
//                        $product->product->colors()->first()?$product->product->colors[0]->name:''
//                        ];
//                    }
//                }
//                $sheet->fromArray($data, null, 'A1', false, false);
//            });
//        })->store('xls', storage_path('excel/exports'));
//
//    $path = storage_path()."/excel/exports/$name._order.xls";
////
//    $file = array('path'=>$path,'email'=>'kanwal.tanveer@barefootandco.com');
//
//    Mail::send('emails.weekly-email',$file, function ($message) use ($file) {
//        $message->from('info@freeedrive.com', 'FreeeDrive');
//        $message->to(['alain.pecourt@barefootandco.com','nathalie.nguy@barefootandco.com','kanwal.tanveer@barefootandco.com']);
//        $message->subject('Weekly Product Orders report');
//        $message->attach($file['path']);
//    });
});
//public function iosMailSent(Request $request)
//{
//
//    $score_data = $request->json()->all();
//    $email = $score_data['email'];
//    $mail = explode('@', $email);
//    $data = array();
//    $name = $mail[0];
//    Excel::create($name, function ($excel) use ($score_data) {
//        $excel->sheet('Safety Score', function ($sheet) use ($score_data) {
//            $data [] = [
//                "arrival_location",     "arrival_time",
//                "company_id",           "count_bad_behaviour",
//                "departure_location",   "departure_time",
//                "driver_id",            "high_ride_time",
//                "remote_id", "score", "time_elapsed"
//            ];
//            foreach ($score_data['data'] as $dt) {
//                $data[] = [
//                    $dt['arrival_location'],
//                    round($dt['arrival_time']),
//                    $dt['company_id'],
//                    $dt['count_bad_behaviour'],
//                    $dt['departure_location'],
//                    round($dt['departure_time']),
//                    $dt['driver_id'],
//                    $dt['high_ride_time'],
//                    $dt['remote_id'],
//                    $dt['score'],
//                    $dt['time_elapsed']
//                ];
//            }
//            $sheet->fromArray($data, null, 'A1', false, false);
//        });
//
//    })->store('xls', storage_path('exports'));
//    $path = storage_path().'/exports/'.$name.'.xls';
//
//    $file = array('path'=>$path,'email'=>$email);
//    Mail::send('emails.weeklyEmail', $file, function ($message) use ($file) {
//        $message->from('info@freeedrive.com', 'FreeeDrive');
//        $message->to($file['email']);
//        $message->subject('FreeeDrive Score Card');
//        $message->attach($file['path']);
//    });
//    return $this->respond(200);
//}



