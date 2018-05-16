<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//include Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
/**
 * create a api using post, get, put, delete
 * _get supported formats ('json','array','csv','html',jsonp','php','serialized','xml')
 * _post supported formats ('json')
 */
class Customer extends REST_Controller {
    public function __construct() { 
        parent::__construct();
        $this->load->model('customer_model');
    }

    public function get_customer_get() {
        //returns all rows if the id parameter doesn't exist,
        //otherwise single row will be returned 
        $cust_unique_id = $this->get('cust_unique_id');
        $customers = $this->customer_model->get_customer($cust_unique_id);
        
        //check if the user data exists
        if(!empty($customers)){
            /*
            $settings_mail = $this->customer_model->global_settings(3); // 3 is for approve credit line email
            $settings_sms = $this->customer_model->global_settings(9); // 9 is for approve credit line sms
            
            $mobile = "639954842680";
            $amount = "200.00";
            $firstname = $customers['first_name'];
            $message = "Sample Message";
            $email = "rambolista@gmail.com";
            $sms_message = str_replace("{amount}",$amount,$settings_sms['value']);
            $email_message = str_replace(array("{amount}","{firstname}"),array($amount,$firstname),$settings_mail['value']);
            send_sms($mobile,$sms_message);
            send_mail($email_message, $email, $settings_mail['subject']);
            */
            

            //set the response and exit
            $this->response($customers, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No customer were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function customer_basic_post() {
        $cData                   = array();
        $cData['first_name']     = $this->post('R1');
        $cData['middle_name']    = $this->post('R2');
        $cData['last_name']      = $this->post('R3');
        $cData['suffix']         = $this->post('R4');
        $cData['birth_date']     = $this->post('R5');
        $cData['address1']       = $this->post('R6');
        $cData['address2']       = $this->post('R7');
        $cData['country']        = $this->post('R8');
        $cData['state']          = $this->post('R9');
        $cData['city']           = $this->post('R10');
        $cData['zipcode']        = $this->post('R11');
        $cData['home_number']    = $this->post('R12');
        $cData['mobile_number']  = $this->post('R13');
        $cData['email']          = $this->post('R14');
        $cData['program_id']     = $this->post('R15');
        $cData['cust_unique_id'] = $this->post('R16');
        
        if(!empty($cData['first_name']) && !empty($cData['last_name']) && !empty($cData['email']) && !empty($cData['mobile_number'])){
            $insert = $this->customer_model->customer_basic($cData,$this->post('R16'));
            
            //check if the customer data inserted
            if($insert){
                //set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Customer information has been updated successfully.',
                    'response_code' => REST_Controller::HTTP_OK
                ], REST_Controller::HTTP_OK);
            }else{
                //set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'Some problems occurred, please try again.',
                    'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
            
            }
        }else{
            //set the response and exit
            $this->response([
                    'status' => FALSE,
                    'message' => 'Provide complete customer information to create.',
                    'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function customer_additional_post() {
        $cData                      = array();
        $cData['company_name']      = $this->post('R1');
        $cData['phone_number']      = $this->post('R2');
        $cData['net_pay_percheck']  = $this->post('R3');
        $cData['income_source']     = $this->post('R4');
        $cData['pay_frequency']     = $this->post('R5');
        $cData['next_paydate']      = $this->post('R6');
        $cData['following_paydate'] = $this->post('R7');
        $cust_unique_id             = $this->post('R8');
        
        if(!empty($cData['company_name']) && !empty($cData['net_pay_percheck']) && !empty($cData['income_source']) && !empty($cData['pay_frequency'])){
            $insert = $this->customer_model->customer_additional($cData,$cust_unique_id);
            
            //check if the customer data inserted
            if($insert){
                //set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Customer information has been updated successfully.',
                    'response_code' => REST_Controller::HTTP_OK
                ], REST_Controller::HTTP_OK);
            }else{
                //set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'Some problems occurred, please try again.',
                    'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
            
            }
        }else{
            //set the response and exit
            $this->response([
                    'status' => FALSE,
                    'message' => 'Provide complete customer information to create.',
                    'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function accept_terms_and_condition_post(){
        $cust_unique_id = $this->post('R1');
        $update = $this->customer_model->accept_terms_and_condition($cust_unique_id);
            
        //check if the customer data inserted
        if($update){
            //set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => 'Customer agreed in terms and condition.',
                'response_code' => REST_Controller::HTTP_OK
            ], REST_Controller::HTTP_OK);
        }
    }

    public function credit_line_application_post(){
        $cust_unique_id = $this->post('R1');
        $update = $this->customer_model->credit_line_application($cust_unique_id);
            
        //check if the customer data inserted
        if($update){
            //set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => 'Customer successfully applied for a credit line.',
                'response_code' => REST_Controller::HTTP_OK
            ], REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No existing customer',
                'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function loan_application_post(){
        $cust_unique_id = $this->post('R1');
        $amount = $this->post('R2');
        $update = $this->customer_model->loan_application($cust_unique_id,$amount);
            
        //check if the customer data inserted
        if($update['status']){
            //set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => $update['message'],
                'response_code' => REST_Controller::HTTP_OK
            ], REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => $update['message'],
                'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function compute_loan_post(){
        $cust_unique_id = $this->post('R1');
        $amount = $this->post('R2');
        $compute_loan = $this->customer_model->compute_loan_application($cust_unique_id,$amount);
        if(!empty($compute_loan)){
            //set the response and exit
            $this->response($compute_loan, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            #$this->response($compute_loan, REST_Controller::HTTP_OK);
            $this->response([
                'status' => FALSE,
                'message' => 'You dont have enough available credit.',
                'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function get_customer_loan_get(){
        $cust_unique_id = $this->get('R1');
        $loans = $this->customer_model->get_customer_loan($cust_unique_id);
        
        //check if the user data exists
        if(!empty($loans)){
            //set the response and exit
            $this->response($loans, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            //$this->response($loans, REST_Controller::HTTP_OK);
            
            $this->response([
                'status' => FALSE,
                'message' => 'No customer loan were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
            
        }
    }

    public function pay_loan_post(){
        $cust_unique_id = $this->post('R1');
        $amount = $this->post('R2');
        $insert = $this->customer_model->pay_loan($cust_unique_id,$amount);
            
        //check if the payment data inserted
        if($insert['status']){
            //set the response and exit
            $this->response([
                'status' => TRUE,
                'message' => $insert['message'],
                'response_code' => REST_Controller::HTTP_OK
            ], REST_Controller::HTTP_OK);
        }else{
            #$this->response($insert, REST_Controller::HTTP_OK);
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => $insert['message'],
                'response_code' => REST_Controller::HTTP_BAD_REQUEST
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function get_transaction_history_get(){
        $cust_unique_id = $this->get('R1');
        $type = $this->get('R2');
        $history = $this->customer_model->get_transaction_history($cust_unique_id,$type);
        //check if the user data exists
        if(!empty($history)){
            //set the response and exit
            $this->response($history, REST_Controller::HTTP_OK);
        }else{
            //set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'No transaction(s) were found.',
                'response_code' => REST_Controller::HTTP_NOT_FOUND
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //this is for another system
    /*
    public function send_mail_text_post(){
        $mobile = $this->post('R1');
        $email = $this->post('R2');
        $file_name = $this->post('R3');
        
        $url = "https://sunpass.mysuncash.com/x/".$file_name."/z1x2c3";
        $message = "Good day! Your Sunpass ticket is here: ".$url;
        
        send_sms($mobile,$message);
        send_mail($message, $email, "Sunpass Ticket");
            
        $this->response([
            'status' => TRUE,
            'message' => "success",
            'response_code' => REST_Controller::HTTP_OK
        ], REST_Controller::HTTP_OK);
    }*/
    
    
}

?>