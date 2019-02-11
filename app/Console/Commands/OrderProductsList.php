<?php

namespace App\Console\Commands;

use App\Models\Order\Order;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Excel;
use Mail;
use App\Models\Promotion\PromotionCode;
use App\Models\Promotion\UsedPromotionalCode;

class OrderProductsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:order-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sending orders list to admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function discountOnProduct($item, $promotion_codes)
    {
        
        $price = $item->price*$item->quantity;
        $discount = 0;
        foreach ($promotion_codes as $pc)
        {
            if($pc->promotion_type_id == 2 && $item->product_id == $pc->product_id)
            {
                $discount = $price*$pc->discount/100;
                break;
            }
            if($pc->promotion_type_id == 1){
                $discount = $price*$pc->discount/100;
            }
        }
        return number_format($discount, 2, '.', ',');
            
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    $orders = Order::pending()->with(['user','products','delivery_address'])->get();
    $name = Carbon::now()->toDateString();
    if($orders->count()>0):
        $data = Excel::create("$name._order", function($excel) use($orders) {
            $excel->sheet('ExportFile', function($sheet) use($orders) {
                $data [] = [
                    "référence_client",     "nom",
                    "prénom",           "adresse_1",
                    "adresse_2",   "code_postal",
                    "ville", "pays",  "téléphone", "mobile","référence_commande","livraison", "ean",	"code",	"quantité",	"prix_unitaire",	"prix_reduit",	"prix_total","nom_produit",	"couleur_produit"

                ];

                foreach ($orders as $order) {
                    $codes = UsedPromotionalCode::whereOrderId($order->id)->pluck('promotion_code_id');
                    $promotion_codes =  PromotionCode::whereIn('id',$codes)->get();

                    $cost = str_replace('.',',',money_format('%+n', $order->shipment->cost)).'€';
                    foreach($order->products as $product)
                    {
                        $prod_disc = $this->discountOnProduct($product, $promotion_codes);
                        $prod_price_after_disc = $product->price - $prod_disc;
                        $total_discount = $prod_disc*$product->quantity;
                        $total_price = ($product->price)*($product->quantity);
                        $f_final_total = $total_price - $total_discount;
                        $data[] = [
                            $order->user->id,
                            $order->delivery_address->surname,//$order->user->profile->surname,
                            $order->delivery_address->firstname,//$order->user->profile->firstname,
                            $order->delivery_address->street_1,
                            ' ',
                            $order->delivery_address->zip,
                            $order->delivery_address->city,
                            'france',
                            $order->delivery_address->telephone,
                            ' ',
                            $order->reference,
                            "Payé($cost)",
//                        $order->shipment->cost,
                            $product->product->ean,
                            $product->product->code,
                            $product->quantity,
                            str_replace('.',',',money_format('%+n', $product->price)).'€',
                            str_replace('.',',',money_format('%+n', $prod_price_after_disc)).'€',
                            str_replace('.',',',money_format('%+n', $f_final_total)).'€',

                            $product->name,
                        $product->product->colors()->first()?$product->product->colors[0]->name:''
                        ];
                    }
                    $order->mail_sent = 1;
                    $order->save();
                }

                $sheet->fromArray($data, null, 'A1', true,false);
            });
        });
//            $csvfile = $data->store('csv', storage_path('excel/exports'));
            $xlsfile = $data->store('xls', storage_path('excel/exports'));

    $path[0] = storage_path()."/excel/exports/$name._order.xls";
//    $path[1] = storage_path()."/excel/exports/$name._order.csv";
    $invoice_refs = $orders->pluck('reference');

    $file = array('path'=>$path,'email'=>'kanwal.tanveer@barefootandco.com');

    Mail::send('emails.daily-email',[$file,'invoice_refs'=>$invoice_refs], function ($message) use ($file) {
        $message->from(env('MAIL_SENDER_ADDRESS'),env('MAIL_SENDER_USERNAME'));
        $message->to([
            'alain.pecourt@barefootandco.com',
            'nathalie.nguy@barefootandco.com',
            'kanwal.tanveer@barefootandco.com',
            'Maria.Bevilacqua@bicworld.com',
            'Roland.Mangeret@bicworld.com',
            'carla.dedieu@barefootandco.com',
            'shafique.qadri@barefootandco.com'
        ]);
        $message->subject('RENTREE ZEN : Nouvelle commande');
        $message->attach($file['path'][0]);
//        $message->attach($file['path'][1]);
    });
    endif;

    }

}
