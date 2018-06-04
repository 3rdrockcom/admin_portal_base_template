<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function __construct()
    {
        
    }
    if ( ! function_exists('api_request')){
      function api_request($data,$method,$extra =null){
        $curl = curl_init();
        if (!isset($data['data']) && empty($data['data'])) {
            $data['data'] = array();
        }
        $options = array(
            CURLOPT_URL => BANKJET_API_URL . $data['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data['data']),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "accept: application/json",
                "cache-control: no-cache",
                $extra
            ),
        );
        if (ENV == 'DEV') {
            $options[CURLOPT_SSL_VERIFYHOST] = false;
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);


        return $response;        
      }
    }
    if ( ! function_exists('api_post')){
    	function api_post($data) {
            return api_request($data, 'POST');
        }
    }
    if ( ! function_exists('api_get')){
        function api_get($data) {
            return api_request($data, 'GET');
        } 
    }   
    if ( ! function_exists('api_request')){
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
    if ( ! function_exists('api_request')){    
        function send_sms($mobile_number, $response_msg) {
            //override, for testing
            if (substr($mobile_number, 0,4) == "1999" || substr($mobile_number, 0,4) == "1888") {
                $mobile_number = "639432788941";
            }elseif (substr($mobile_number, 0,4) == "1111" || substr($mobile_number, 0,4) == "0000") {
                $mobile_number = "639178435554";
            }elseif ((substr($mobile_number, 0, 4) == '1639') && strlen ($mobile_number) == 13) {
                $mobile_number = substr($mobile_number, 1, 12);
            }
            $msgs = str_split($response_msg, 160);
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
    


