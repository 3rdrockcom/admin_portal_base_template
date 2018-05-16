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
class Lookup extends REST_Controller {
    public function __construct() { 
        parent::__construct();
        $this->load->model('lookup_model');
    }

    public function get_country_get() {
        //returns all rows if the id parameter doesn't exist,
        //otherwise single row will be returned
        $id = $this->get('country_id');
        $country = $this->lookup_model->get_country($id);
        
        //check if the country data exists
        if(!empty($country)){
            //set the response and exit
            $this->response($country, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No country were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function get_state_get() {
        //returns all rows if the id parameter doesn't exist,
        //otherwise single row will be returned
        $country_id = $this->get('country_id');
        $state_id = $this->get('state_id');
        $state = $this->lookup_model->get_state($country_id,$state_id);
        
        //check if the state data exists
        if(!empty($state)){
            //set the response and exit
            $this->response($state, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No state were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function get_city_get() {
        //returns all rows if the id parameter doesn't exist,
        //otherwise single row will be returned
        $state_code = $this->get('state_code');
        $city_id = $this->get('city_id');
        $city = $this->lookup_model->get_city($state_code,$city_id);
        
        //check if the city data exists
        if(!empty($city)){
            //set the response and exit
            $this->response($city, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No city were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function get_income_source_get() {
        //returns all rows if the id parameter doesn't exist,
        //otherwise single row will be returned
        $id = $this->get('id');
        $isource = $this->lookup_model->get_income_source($id);
        
        //check if the income sourse data exists
        if(!empty($isource)){
            //set the response and exit
            $this->response($isource, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No income source were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function get_pay_frequency_get() {
        //returns all rows if the id parameter doesn't exist,
        //otherwise single row will be returned
        $id = $this->get('id');
        $pf = $this->lookup_model->get_pay_frequency($id);
        
        //check if the income sourse data exists
        if(!empty($pf)){
            //set the response and exit
            $this->response($pf, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No pay frequency were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

}

?>