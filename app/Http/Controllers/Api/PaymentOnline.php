<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentOnline extends Controller
{

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function vnpay(Request $request)
    {

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = $request->url;
        $vnp_TmnCode = "R85DPSR9";//Mã website tại VNPAY
        $vnp_HashSecret = "90Y8TI5MF8APZOXUM4H07PDRY0E9ZEOZ"; //Chuỗi bí mật

        $vnp_TxnRef = 'FS_' . rand(1000, 9999);
        $vnp_OrderInfo = "VNPAY PAYMENT";
        $vnp_OrderType = "FSHOES";
        $vnp_Amount = $request->total * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00'
        ,
            'message' => 'success'
        ,
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {

            //Return trang trung gian
            header('Location: ' . $vnp_Url);
            die();
        } else {
            return json_encode($returnData);
        }
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

     public function momo(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';



        $orderInfo = "FSHOES";
        $amount = $request->total;
        $orderId = time() . "";
        $redirectUrl = $request->url;
        $ipnUrl = $request->url;
        $extraData = "";

        $orderId = 'FS_' . rand(1000, 9999); // Mã đơn hàng
        $orderInfo = 'MOMO PAYMENT';
        $amount = $request->total ;
        $requestId = time() . "";
        $requestType = "payWithATM";
        // $extraData = ($extraData ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'en',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        //Just a example, please check more in there

        return json_encode($jsonResult);
    }

    public function stripe(Request $request)
    {
        Stripe::setApiKey("sk_test_51Q3Bwk00OUlOewUZydndUP5rand7Mrcam8wUb7CvLfjKmpx2W5zXmbQ6srqoEqKKiIQbvmYEcyQHXWj34sAgfWOi00fP2Vdia6");
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
                   'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'VND',
                        'product_data' => [
                            "name" => "Fshoes Store",
                        ],
                        'unit_amount' => $request->total,
                    ],
                    'quantity' => 1,
                ],
            ],
            ],
            'customer_email' => 'longvulinhhoang@gmail.com',
            'mode' => 'payment',
            'success_url' => $request->url . '?session_id={CHECKOUT_SESSION_ID}&status=1',
            'cancel_url' => $request->url."?status=0",
        ]);
        return $checkoutSession->url;
    }


    /**
     * @throws \Throwable
     */
    public function paypal(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successPaypal'),
                "cancel_url" => route('errorPaypal'),
                "shipping_preference" => "SET_PROVIDED_ADDRESS"
            ],

            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => 50  //TOTAL
                    ],
                    "shipping" => [
                        "name" => [
                            "full_name" => "LONG" // Name,
                        ],
                        "address" => [
                            "address_line_1" => "123 Main St",
                            "address_line_2" => "Apt 1B",
                            "admin_area_2" => "San Francisco",  // City
                            "admin_area_1" => "CA",             // State
                            "postal_code" => "94107",           // Postal Code
                            "country_code" => "US"              // Country
                        ]
                    ],

                ]
            ],

        ]);
        foreach ($response["links"] as $type) {
            if ($type["rel"] == "approve") return $type["href"];
        }

    }

    public function successPaypal(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        return $response;
    }

    public function errorPaypal(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        return $response;
    }

}
