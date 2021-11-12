<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class donateController extends Controller
{
    public function Donate (Request $request) {
        $PayGateWay = "https://ccore.spgateway.com/MPG/mpg_gateway";
        $url='https://0757-118-150-140-18.ngrok.io';
        $trade_info_arr = array(
        'MerchantID' => env('Merchant_ID'),
        'RespondType' => 'JSON',
        'TimeStamp' => Carbon::now()->timestamp,
        'Version' => 1.6,
        'MerchantOrderNo' => Carbon::now()->timestamp,
        'LoginType'=> 0,
        'OrderComment'=> "捐款至{$request->data['shelter_name']}",
        'Amt' => $request->data['price'],
        'ItemDesc' => '捐款',
        'Email'=> $request->data['email'],
        'ReturnURL'=> $url . '/api/spgateway/callback?from=ReturnURL', 
        'NotifyURL'=> $url . '/api/spgateway/callback?from=NotifyURL',
        'ClientBackURL'=>$url
    );

        $mer_key = env('HASH_KEY');
        $mer_iv = env('HASH_IV');

        $return_str = http_build_query($trade_info_arr);

        $TradeInfo = trim(bin2hex(openssl_encrypt($this->addpadding($return_str), 'aes-256-cbc', $mer_key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $mer_iv)));

        $shaData = 'HashKey=' . $mer_key . '&' . $TradeInfo . '&HashIV=' . $mer_iv;

        $Tradehsa = strtoupper(hash('sha256', $shaData));

       return response(json_encode(['MerchantID'=>env('Merchant_ID'),'TradeInfo'=>$TradeInfo,'TradeSha'=>$Tradehsa,'Version'=>'1.6','PayGateWay'=>$PayGateWay]));
    }
    public function addpadding($string, $blocksize = 32)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }
    public function callback (Request $request){
        $returnData = $request->all();
        return view('welcome');
    }
}
