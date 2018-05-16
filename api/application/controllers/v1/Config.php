<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//include Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
/**
 * create a api using post, get, put, delete
 * _get supported formats ('json','array','csv','html',jsonp','php','serialized','xml'
 * _post supported formats
 */
class Config extends REST_Controller {
    public function __construct() { 
        parent::__construct();
        $this->load->model('customer_model');
    }

    public function generate_customer_key_get() {
        $customer_id = $this->get('customer_id');
        $mobile = $this->get('mobile');
        
        if(!empty($customer_id) && !empty($mobile)){
            $check_if_exist = $this->customer_model->check_if_customer_key_exist($customer_id,$mobile);
            if(!empty($check_if_exist)){
                $this->response([
                    'status' => FALSE,
                    'message' => 'Customer has already an existing key and id.',
                    'response_code' => REST_Controller::HTTP_BAD_REQUEST
                ], REST_Controller::HTTP_BAD_REQUEST);
            }else{
                $user_key = $this->customer_model->generate_customer_key($customer_id,$mobile);
                $this->response($user_key, REST_Controller::HTTP_OK);
            }
            
        }else{
            //set the response and exit
            $this->response([
                    'status' => FALSE,
                    'message' => 'Provide complete fields to create.',
                    'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}

?>