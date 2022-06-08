<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class mpesaController extends Controller
{
    public function passcodegen(){
        $timestamp=Carbon::rawParse('now')->format('YmsHms');
        $passKey='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
        $businessShortCode=174379;
        $mpesaPassword=base64_encode($businessShortCode.$passKey.$timestamp);
        return $mpesaPassword;
    }
    public function newAccessToken(){
        $consumer_key="Aosv0tdoKuXmP1Qbr8nESEKoXl7i3FKW";
        $consumer_secret="VV3hAAmoYhVXGize";
        $credentials=base64_encode($consumer_key.":". $consumer_secret);
        $url="https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials.'Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
        $curl_response=curl_exec($curl);
        $access_token=json_decode($curl_response);
        curl_close($curl);
        return $access_token->access_token;

    }
    public function stkpush(){
        $url="https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
        $curl_post_data=[
            "BusinessShortCode"=> 174379,
            "Password"=> $this->passcodegen(),
            "Timestamp"=> Carbon::rawParse('now')->format('YmdHms'),
            "TransactionType"=> "CustomerPayBillOnline",
            "Amount"=> '2',
            "PartyA"=> '254701583807',
            "PartyB"=> 174379,
            "PhoneNumber"=> 254708374249,
            "CallBackURL"=> "https://enigmatic-ravine-83412.herokuapp.com/callbackUrl",
            "AccountReference"=> "Trial EP push",
            "TransactionDesc"=> "Lipa na Mpesa trial" 
        ];
        $data_string=json_encode($curl_post_data);

        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'.'Authorization:Bearer '.$this->newAccessToken()));
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response=curl_exec($curl);
        return $curl_response;
    }
}
