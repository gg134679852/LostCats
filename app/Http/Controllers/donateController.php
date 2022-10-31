<?php

namespace App\Http\Controllers;

use App\Models\DonateLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class donateController extends Controller
{

    public function Donate(Request $request)
    {
        $PayGateWay = "https://ccore.spgateway.com/MPG/mpg_gateway";
        $url = env('URL');
        $user = Auth::user();
        $orderId = $this->makeOrderId();
        $trade_info_arr = array(
            'MerchantID' => env('Merchant_ID'),
            'RespondType' => 'JSON',
            'TimeStamp' => Carbon::now()->timestamp,
            'Version' => 1.6,
            'MerchantOrderNo' => $orderId,
            'LoginType' => 0,
            'OrderComment' => "捐款至{$request->data['shelter_name']}",
            'Amt' => $request->data['price'],
            'ItemDesc' => $request->data['shelter_name'],
            'Email' => $request->data['email'],
            'ReturnURL' => $url . '/spgateway/callback?from=ReturnURL',
            'NotifyURL' => $url . '/spgateway/callback?from=NotifyURL',
        );

        $mer_key = env('HASH_KEY');
        $mer_iv = env('HASH_IV');

        $return_str = http_build_query($trade_info_arr);

        $TradeInfo = bin2hex(openssl_encrypt($return_str, 'aes-256-cbc', $mer_key, OPENSSL_RAW_DATA, $mer_iv));

        $shaData = 'HashKey=' . $mer_key . '&' . $TradeInfo . '&HashIV=' . $mer_iv;

        $Tradehsa = strtoupper(hash('sha256', $shaData));

        DonateLog::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'shelter_name' => $request->data['shelter_name'],
            'donate_price' => $request->data['price'],
        ]);

        return response(json_encode(['MerchantID' => env('Merchant_ID'), 'TradeInfo' => $TradeInfo, 'TradeSha' => $Tradehsa, 'Version' => '1.6', 'PayGateWay' => $PayGateWay]));
    }

    public function callback(Request $request)
    {
        $requestData = $request->all();
        $decryptData = $requestData['TradeInfo'];
        $mer_key = env('HASH_KEY');
        $mer_iv = env('HASH_IV');
        $TradeInfo = $this->strippadding(openssl_decrypt(hex2bin($decryptData), 'AES-256-CBC', $mer_key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $mer_iv));

        $returnData = json_decode($TradeInfo, true);
        $result = $returnData['Result'];

        if (isset($returnData) && $returnData['Status'] !== 'SUCCESS') {
            DonateLog::where('order_id', $result['MerchantOrderNo'])->delete();
        }
        if (isset($returnData) && $returnData['Status'] === 'SUCCESS') {
            DonateLog::where('order_id', $result['MerchantOrderNo'])->update(['isComplete' => true]);
        }

        return view('donateCallBackPage', $returnData);
    }

    public function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);

        if (preg_match('/' . $slastc . '{' . $slast . '}/', $string)) {
            $string = substr($string, 0, strlen($string) - $slast);

            return $string;
        }

        return false;
    }

    public function makeOrderId()
    {
        $Strings = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $shuffleString = substr(str_shuffle($Strings), 1, 2);
        $randomNum = uniqid();
        return "{$shuffleString}{$randomNum}";
    }
}
