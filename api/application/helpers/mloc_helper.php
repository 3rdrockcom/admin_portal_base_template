<?php

/**
 * Created by Ram Bolista 
 * 4/13/2018
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('send_mail')){
    function send_mail($message, $email_address, $subject, $attachment = false, $attachment_filename = false) {
        $CI = & get_instance();
        
        $config = array(
            'mailtype'  => 'html',
            'smtp_host' => settings::$email_recipients['smtp_host'],
            'smtp_user' => settings::$email_recipients['smtp_user'],
            'smtp_pass' => settings::$email_recipients['smtp_pass'],
            'protocol'  => settings::$email_recipients['protocol'],
            'smtp_port' => settings::$email_recipients['smtp_port'],
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'smtp_timeout' => '7',
            'smtp_crypto' => 'ssl',
        );
        $CI->load->library('email');
        $CI->email->initialize($config);
        $CI->email->from(settings::$email_recipients['from'], PROGRAM . ' Auto Mailer');
        $CI->email->to($email_address);
        $CI->email->subject($subject);
        $CI->email->message("<html><body><div>{$message}</div></body></html>");
        if ($attachment !== false) {
            if ($attachment_filename !== false) {
                $CI->email->attach($attachment, 'attachment', $attachment_filename);
            } else {
                $CI->email->attach($attachment);
            }
        }
        $CI->email->send();
    }
}

if(!function_exists('send_sms')){
     function send_sms($mobile_number, $response_msg) {
        //override, for testing
        if (substr($mobile_number, 0,3) == "995" || substr($mobile_number, 0,3) == "922") {
            $mobile_number = "63".$mobile_number;
        } elseif ((substr($mobile_number, 0, 4) == '1639') && strlen ($mobile_number) == 13) {
            $mobile_number = substr($mobile_number, 1, 12);
        }
        $msgs = str_split($response_msg, 160);
        if(strlen ($mobile_number) == 10) $mobile_number = "1".$mobile_number;
        foreach($msgs as $d) {
            $params = array(
                'user'      => settings::$sms_recipients['user'],
                'password'  => settings::$sms_recipients['password'],
                'sender'    => settings::$sms_recipients['sender'],
                'SMSText'   => $d,
                'GSM'       => $mobile_number,
            );
            $send_url = settings::$sms_recipients['send_url'] . http_build_query($params);
            $send_response = file_get_contents($send_url);
        }
        if (!($send_response != '')) {
            return array(
                'success' => FALSE,
                'message' => 'No Response',
            );
        } else {
            if (strstr($send_response, '<status>0</status>') === false) {
                return array(
                    'success' => FALSE,
                    'message' => 'Failed:' . $send_response,
                );
            } else {
                return array(
                    'success' => true,
                    'message' => 'Success:' . $send_response,
                );
            }
        }
        return $send_response;
    }
}

// log-in in EPOINT MERCHANT
if(!function_exists('epoint_merchant_login')){
    function epoint_merchant_login(){
        $url = EPOINT_API."merchant_login?P01=".EPOINT_MTID."&P02=".EPOINT_USER."&P03=".EPOINT_PASSWORD;
        $curl_result = get_curl_result($url);
        return $curl_result;
    }
}

// transafer specific amount to source and destination
if(!function_exists('epoint_merchant_fund_transfer')){
    function epoint_merchant_fund_transfer($p = array()){
        $params      = "P01=".$p['session_id'];
        $params     .= "&P02=".$p['amount'];
        $params     .= "&P03=".$p['reference_number'];
        $params     .= "&P04=".$p['source']; // source (P,S,F, or customer ID)
        $params     .= "&P05=".$p['destination']; // destination (P,S,F, or customer ID)
        $params     .= "&P06=".$p['description']; // description (25 chars max)
        $params     .= "&P07=".$p['mobile']; // customer_mobile_number (if P04 = customer_id, provide P07 otherwise leave empty)
        $url         = EPOINT_API."fund_transfer?".$params;
        $curl_result = get_curl_result($url);
        return $curl_result;
    }
}

// get customer balance inside EPOINT wallet
if(!function_exists('epoint_merchant_customer_balance')){
    function epoint_merchant_customer_balance($p = array()){
        $params      = "P01=".$p['session_id'];
        $params     .= "&P02=".$p['customer_id'];
        $params     .= "&P03=".$p['mobile'];
        $url         = EPOINT_API."get_customer_balance?".$params;
        $curl_result = get_curl_result($url);
        return $curl_result;
    }
}

// get merchant balance in EPOINT
if(!function_exists('epoint_merchant_balance')){
    function epoint_merchant_balance($sessionid){
        $url = EPOINT_API."get_merchant_balances?P01=".sessionid;
        $curl_result = get_curl_result($url);
        return $curl_result;
    }
}

// log-out in EPOINT MERCHANT
if(!function_exists('epoint_merchant_logout')){
    function epoint_merchant_logout($sessionid){
        $url = EPOINT_API."account_logout?P01=".sessionid;
        $curl_result = get_curl_result($url);
        return $curl_result;
    }
}

// get response via curl get
if(!function_exists('get_curl_result')){
    function get_curl_result($url)
    {
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_HEADER, 0);
        curl_setopt($req, CURLOPT_POST, 0);
        curl_setopt($req, CURLOPT_HTTPGET, 1);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        $resp =curl_exec($req);   
        curl_close($req);

        $resp = json_decode($resp, true);
        return $resp;

    }
}

// get response via curl post
if(!function_exists('post_curl_result')){
    function post_curl_result($url, $object_array)
    {
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($req, CURLOPT_POST, 1 );
        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($object_array));
        curl_setopt($req, CURLOPT_CONNECTTIMEOUT ,0);

        $resp =curl_exec($req);
        curl_close($req);

        $resp = json_decode($resp, true);
        return $resp;

    }
}


