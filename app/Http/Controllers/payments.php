<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class payments extends Controller
{
    function pay(Request $req){
        date_default_timezone_set('Africa/Nairobi');
        $consumerKey = 'Aosv0tdoKuXmP1Qbr8nESEKoXl7i3FKW';
        $consumerSecret = 'VV3hAAmoYhVXGize'; 
        $BusinessShortCode = '174379';
        $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';  
        $PartyA = $req->phone;
        $AccountReference = '2255';
        $TransactionDesc = 'Test Payment';
        $Amount = $req->amount;
        $Timestamp = date('YmdHis');    
        $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
        $headers = ['Content-Type:application/json; charset=utf8'];
        $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $CallBackURL = 'https://enigmatic-ravine-83412.herokuapp.com/callback';  
        $curl = curl_init($access_token_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        $access_token = $result->access_token;  
        curl_close($curl);
        $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];
        # initiating the transaction
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $initiate_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
        $curl_post_data = array(
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $Password,
            'Timestamp' => $Timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $BusinessShortCode,
            'PhoneNumber' => $PartyA,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionDesc
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        print_r($curl_response);
        echo $curl_response;
    }
}