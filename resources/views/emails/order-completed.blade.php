
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rentrée Zen</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <style type="text/css">
        /* Fonts and Content */
        body, td { font-family: "Open Sans"; font-size:14px; }
        .green a{color:#2EB481;}
        .white a{color:#ffffff;}

    </style>

</head>
<body style="margin:0px; padding:0px; -webkit-text-size-adjust:none;">

<table width="600" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto; padding-top:40px; width:600px;" align="center" >
    <tbody>
    <tr>
        <td align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td align="center"><a href="https://rentree-zen.fr/" target="_blank"><img src="{{$message->embed('https://rentree-zen.fr/assets/images/mainLogo.png')}}" width="231" height="229"/></a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top:54px;" >
          <p style="	color: #0647c9;	font-size: 18px;	font-weight: 600;	line-height: 32px;">Bonjour {{$order->billing_address->firstname}}</p>

          <p><b>{{__('email.order_number')}} <a href="{{env('FRONT_END_URL')}}order/{{$order->reference}}">{{$order->reference}}</a> </b></p>
          <p> {{__('email.order_completed')}}</p>

        </td>
    </tr>
    <tr>
        <td>
          <p>A très bientôt!</p>
          <p style="	color: #0647c9;font-weight: 600;	line-height: 32px;">L’équipe Rentrée Zen</p>
          <br />
          <p style="text-align: center;	color: #0647c9;	font-size: 18px;	font-weight: 600;	line-height: 32px;">La liste scolaire de votre enfant chez vous en 1 clic!</p>

        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" style="margin-top:30px;">
                <tr align="center">
                    <td width="200" align="center">
                        <table align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td height="75" align="center"><img src="{{$message->embed('https://rentree-zen.fr/assets/images/iconSimple.png')}}" width="128" height="129"></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="color: #0647c9;	font-size: 14px;	font-weight: bold;">SIMPLE</p>
                                    <p style="color: #474747;font-size: 12px;	font-weight: 300;	line-height: 17px;	text-align: center;">
                                      Renseignez l’école et la classe de votre enfant.
  Un panier de fournitures adapté à sa liste scolaire est prêt à être commandé !</p>
                                </td>
                            </tr>
                        </table>


                    </td>
                    <td width="200" align="center">
                        <table align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td height="75" align="center"><img src="{{$message->embed('https://rentree-zen.fr/assets/images/iconFlexible.png')}}" width="125" height="125"></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="color: #0647c9;	font-size: 14px;	font-weight: bold;">FLEXIBLE</p>
                                    <p style="color: #474747;font-size: 12px;	font-weight: 300;	line-height: 17px;	text-align: center;">
                                      Ajustez votre panier de produits à tout moment.
  Ajoutez les listes de plusieurs enfants, c’est aussi possible !</p>
                                </td>
                            </tr>
                        </table>


                    </td>
                    <td width="199" align="center">
                        <table align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td height="75" align="center"><img src="{{$message->embed('https://rentree-zen.fr/assets/images/iconLotus.png')}}" width="128" height="129"></td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="color: #0647c9;	font-size: 14px;	font-weight: bold;">ZEN</p>
                                    <p style="color: #474747;font-size: 12px;	font-weight: 300;	line-height: 17px;	text-align: center;">
                                      Evitez le stress des magasins à l’heure de la rentrée !
  Commandez vos fournitures en quelques clics et recevez les produits chez vous.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="color: #474747;	font-size: 12px;	font-weight: 300;	line-height: 17px;	text-align: center;" align="center" height="60">
          Rentrée Zen, 2018. Tous droits réservés
          <p><b>Barefoot&Co France</b> société par actions simplifiée au capital de 2328,38 € dont le siège social se trouve au 7 rue du colonel Moll 75017 Paris, immatriculée au RCS de Paris sous le numéro 821 838 562.</p>

        </td>
    </tr>
</table>
</body>
</html>
