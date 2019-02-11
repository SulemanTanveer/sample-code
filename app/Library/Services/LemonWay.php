<?php
namespace App\Library\Services;

class LemonWay
{
    private $directkit_json2;
    private $webKitUrl;
    private $ssl_verification = false;
    private $params = [];
    private $css_url = 'https://backend.btc-develop.frogeek.com/css/lemonway-custom.css';
    function __construct()
    {
        $this->directkit_json2 = config('payment.directkit_json2');
        $this->webKitUrl = config('payment.webKitUrl');
        $this->params['wlLogin'] = 'alain.pecourt@barefoot-studio.be';
        $this->params['wlPass'] = 'Rentreezen';
        $this->params['version'] = config('payment.version');
        $this->params['language'] = config('payment.language');
        $this->params['wallet'] = '152887585676o9kgg3';
    }
    public function callService($serviceName, $parameters) {

        // add missing required parameters
        $fullParams = array_merge($parameters, $this->params);

        // wrap to 'p'
        $request = json_encode(array('p' => $fullParams));

        $serviceUrl = $this->directkit_json2.'/'.$serviceName;

        $headers = array(
            "Content-type: application/json;charset=utf-8",
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serviceUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verification);

        $response = curl_exec($ch);

        $network_err = curl_errno($ch);

        if ($network_err) {
            error_log('curl_err: ' . $network_err);
            throw new \Exception($network_err);
        }
        else {
            $httpStatus = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($httpStatus == 200)  {
                // decode json
                $unwrapResponse = json_decode($response)->d;
                $businessErr = $unwrapResponse->E;
                if ($businessErr) {
                    error_log($businessErr->Code." - ".$businessErr->Msg." - Technical info: ".$businessErr->Error);
                    throw new \Exception($businessErr->Code." - ".$businessErr->Msg);
                }

                if (isset($unwrapResponse->MONEYINWEB) && isset($unwrapResponse->MONEYINWEB->TOKEN)){
                    $paymentUrl = $this->webKitUrl."?moneyintoken=".$unwrapResponse->MONEYINWEB->TOKEN."&lang=fr&p=".urlencode($this->css_url);

                    $fetchPaymentPage = curl_init();
                    curl_setopt($fetchPaymentPage, CURLOPT_URL, $paymentUrl);
                    curl_setopt($fetchPaymentPage, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($fetchPaymentPage, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($fetchPaymentPage, CURLOPT_TIMEOUT, 60);
                    curl_setopt($fetchPaymentPage, CURLOPT_SSL_VERIFYPEER, $this->ssl_verification);

                    $paymentPage = curl_exec($fetchPaymentPage);

                    $pageFetchError = curl_errno($fetchPaymentPage);

                    if ($pageFetchError) {
                        throw new \Exception($pageFetchError);
                    }

                    return $paymentPage;
                }

                else throw new \Exception("token not returned");
            }
            else {
                throw new \Exception("Service return HttpStatus $httpStatus $response");
            }
        }
    }
}
