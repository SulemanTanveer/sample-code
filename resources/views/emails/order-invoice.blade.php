<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Order Invoice</title>
    <style type="text/css">
    .clearfix:after {
    content: "";
    display: table;
    clear: both;
  }
    .space{
      margin:60px
    }
.no-border{
  border: none;
}
.text-center{
  text-align:center
}
a {
  color: #5D6975;
  text-decoration: underline;
}

body {
  position: relative;
  margin : 50px;
  height: 29.7cm;
  color: #001028;
  background: #FFFFFF;
  font-family: Arial, sans-serif;
  font-size: 12px;
  font-family: Arial;
}

header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: left;
  margin-bottom: 10px;
  margin-left: 20px;
}

#logo img {
  width: 90px;
}
/*.no-background{*/
  /*background-color:#ffffff;*/
/*}*/
    table.address tr:nth-child(2n-1) td {
      background: #ffffff;
    }
    table.top-address tr:nth-child(2n-1) td {
      background: #ffffff;
    }
    table.top-address td{
      text-align : left;
    }
    table.top-address td {
      padding:10px 20px;
    }
    table.address th{
      text-tarnsform:underline;
      text-align: left;
    }
    table.address td {
    text-align: left;
      padding:5px 20px;
    }
    h1 {
  color: #5D6975;
  font-size: 2.4em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
  background: url(dimension.png);
}

#project {
  float: left;
}

#project span {
  color: #5D6975;
  text-align: right;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}

#company {
  float: right;
  text-align: right;
}

#project div,
#company div {
  white-space: nowrap;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;

}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}

    table.border tr td {
      background: #ffffff;
      border: .5px solid #000;
    }
    table.border th{
      border: .5px solid #000;
      background-color: #d9d9d9;
    }
table th,
table td {
  text-align: center;
}

table th {
  padding: 5px 20px;
  /*color: #5D6975;*/
  font-weight:bold;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;
  text-align: center;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: 5px;
  text-align: center;
}

table td.service,
table td.desc {
  vertical-align: top;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table td.grand {
  border-top: 1px solid #5D6975;;
}
.text-bold {
  font-weight: bold;
}
.calculation {
  font-weight: bold;
  text-transform: uppercase;
  text-align: left;
}
.grand-total {
  font-weight: 900 !important;
  font-size: 20px;
}
#notices .notice {
  /*color: #5D6975;*/
  line-height: 2.2em;
  font-size: 1.2em;
}
.border{
  border: 1px solid #000000;
}

footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <?php setlocale(LC_MONETARY, 'fr_FR.UTF-8'); ?>
        <img src="{{env('LOGO_ADDRESS')}}">
      </div>
      <h1>{{__('email.invoice')}}</h1>
      {{-- <div id="company" class="clearfix">
        <div><b>Adresse de livraison:</b> {{$order->delivery_address->street_1}}</div>
        <div><b>Adresse de facturation:</b> {{$order->billing_address->street_1}}</div>
      </div> --}}
      <div id="project">
        <table class="top-address">
          <tbody>
          <tr>
            <td class="no-border no-background">{{__('email.reference')}} </td>
            <td>{{$order->id}}</td>
          </tr>
          <tr>
            <td class="no-border no-background">{{__('email.order_number')}} </td>
            {{--<td><a href="mailto:{{$order->user->email}}">{{$order->user->email}}</a></td>--}}
            <td>{{$order->reference}}</td>
          </tr>
          <tr>
            <td class="no-border no-background">{{__('email.order_date')}}</td>                             
            <td>{{$order->created_at}}</td>
          </tr> 
          <tr>
            <td class="no-border no-background">{{__('email.invoice_date')}}</td>                             
            <td>{{$order->created_at}}</td>
          </tr>
          <tr>
            <td class="no-border no-background">{{__('email.settlement_date')}}</td> <td>{{$order->created_at}} {{__('email.by_card')}}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </header>
    <table class="address no-border">
    <thead >
      <tr>
        <th style="width: 33%;" class="text-bold no-border"><u>De:</u></th>
        <th style="width: 33%;" class="text-bold no-border"><u>Adresse de facturation:</u></th>
        <th style="width: 33%;" class="text-bold no-border"><u>Adresse de livraison:</u></th>
      </tr>
    </thead>
      <tbody>
      <tr >
        <td class="no-background">Barefoot&Co France SAS</td>
        <td class="no-background">{{$order->billing_address->firstname}} {{$order->billing_address->surname}}</td>
        <td class="no-background">{{$order->delivery_address->firstname}} {{$order->delivery_address->surname}}</td>
      </tr>
      <tr>
          <td class="no-background">7 rue du Colonel Moll </td>
          <td class="no-background">{{$order->billing_address->street_1}}</td>
          <td class="no-background">{{$order->delivery_address->street_1}}	</td>
      </tr>
      <tr>
          <td class="no-background">75017 Paris</td>
          <td class="no-background">{{$order->billing_address->zip}} {{$order->billing_address->city}}	</td>
        <td class="no-background">{{$order->delivery_address->zip}} {{$order->delivery_address->city}}	</td>
      </tr>
      <tr>
        <td>TVA : FR 59 821 838 562</td>
      </tr>
  </tbody>
</table>
    <main>
      <table class="border">
        <thead>
          <tr>
            <th class="service">{{__('email.products')}}</th>
            <th>{{__('email.quantity')}}</th>
            <th>{{__('email.price')}}</th>
            <th>{{__('email.tva')}}</th>
            <th>{{__('email.total')}}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->products as $product)
          <tr>
            <td class="service">{{$product->name}}</td>
            <td class="qty">{{$product->quantity}}</td>
            <td class="unit">{{money_format('%!(#1n', ($product->price-round($product->price*(1/6),2)))}} &euro;</td>
            <td class="qty">{{money_format('%!(#1n', round($product->price*(1/6),2))}}&euro;</td>
            <td class="total">{{money_format('%!(#1n', $product->price*$product->quantity)}} &euro;</td>
          </tr>
          @endforeach
          <tr>
            <td class="calculation">{{__('email.sub_total')}}</td>
            <td></td>
            <td></td>
            <td></td>

            <td class="total text-bold">{{money_format('%!(#1n', $order->total+$order->discount-$order->shipment->cost)}} &euro;</td>

          </tr>

          <tr>
            <td class="calculation">{{__('email.total_tva')}}</td>
            <td></td>
            <td></td>
            <td></td>

            <!-- <td class="total text-bold">{{money_format('%!(#1n', ($order->total-$order->shipment->cost)*(1/6))}} &euro;</td> -->
            <td class="total text-bold">{{money_format('%!(#1n', ($order->total)*(1/6))}} &euro;</td>

          </tr>
          <tr>
            <td class="calculation">{{__('email.discount')}}</td>
            <td></td>
            <td></td>
            <td></td>

            <td class="total text-bold">{{money_format('%!(#1n', $order->discount)}} &euro;</td>
          </tr>
           <tr>
            <td class="calculation">{{__('email.delivery_charges')}}</td>
               <td></td>
               <td></td>
             <td></td>

             <td class="total text-bold">{{money_format('%!(#1n', $order->shipment->cost)}} &euro;</td>
          </tr>
          <tr>
            <td class="calculation" colspan="" class="grand total">{{__('email.grand_total')}}</td>
          <td></td>
          <td></td>
            <td></td>


            <td class="grand-total">{{money_format('%!(#1n', $order->total)}} &euro;</td>

          </tr>
        </tbody>
      </table>
      <div class="space"></div>
       <div id="notices">
         <div class="notice">Nous vous remercions pour votre confiance et espérons avoir répondu à vos attentes.
         </div>
         <div class="notice">En cas de problème, contactez notre service clients au : 01 79 72 52 77  ou envoyez vos commentaires à <a href="mailto:support@rentree-zen.fr">support@rentree-zen.fr</a>
         </div>
      </div>
      <div class="space"></div>
      <div class="text-center">
  <p class="text-center">Rentree-zen.fr est un service opéré par la Société Barefoot&Co France SAS
  </p>
        <p class="text-center">SAS au capital de 2328,38 euros

        </p>
        <p class="text-center">Immatriculée au RCS de Paris sous le numéro 821 838 562

        </p>
      </div>
    </main>
    <footer>
      {{-- Invoice was created on a computer and is valid without the signature and seal. --}}
    </footer>
  </body>
</html>
