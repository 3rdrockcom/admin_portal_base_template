<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NOTE: function send_sms() and send_email() and curl_result() is in helper/mloc_helper.php file
 * Author : Robert Ram Bolista
 */

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        //load database library
        $this->load->database();
    }

    /*
     * Fetch customer data
     */
    public function get_customer($cust_unique_id = ""){
        $query = $this->db->get_where('view_customer_info', array('cust_unique_id' => $cust_unique_id));
        return $query->row_array();
    }

    /*
     * Update customer basic info
     */
    public function customer_basic($data, $cust_unique_id) {
        if(!empty($data) && !empty($cust_unique_id)){
            $update = $this->db->update('tblCustomerBasicInfo', $data, array('cust_unique_id'=>$cust_unique_id));
            return $update?true:false;
        }else{
            return false;
        }
    }

    /*
     * Update customer additional info
     */
    public function customer_additional($data, $cust_unique_id) {
        if(!empty($data) && !empty($cust_unique_id)){
            $fk_customer_id=$this->get_customer($cust_unique_id);
            $update = $this->db->update('tblCustomerOtherInfo', $data, array('fk_customer_id'=>$fk_customer_id['customer_id']));
            return $update?true:false;
        }else{
            return false;
        }
    }

    /*
     * Accept Terms and Condition
     */
    public function accept_terms_and_condition($cust_unique_id) {
        if(!empty($cust_unique_id)){
            $data['term_and_condition'] = "1";
            $fk_customer_id=$this->get_customer($cust_unique_id);
            $update = $this->db->update('tblCustomerAgreement', $data, array('fk_customer_id'=>$fk_customer_id['customer_id']));
            return $update?true:false;
        }else{
            return false;
        }
    }

    /*
     * Add Credit line application to a specific customer
     * NOTE: after tblCustomerCreditLineApplication insert or update, tblCustomerCreditLine and tblCustomerCreditLineHistory will automatically generate a data using the mysql trigger trg_after_tblCustomerCreditLineApplication_insert or trg_after_tblCustomerCreditLineApplication_update
     */
    public function credit_line_application($cust_unique_id) {
        
        $customer_info=$this->get_customer($cust_unique_id);
        if(!empty($customer_info['customer_id'])){
            $limit = $this->get_loan_credit_limit();
            $ref_code = $this->generate_random_key(5);
            $credit_approval_settings = $this->global_settings(1); // 1 check if credit is auto approve or manual
            
            $data['fk_customer_id'] = $customer_info['customer_id'];
            $data['credit_line_id'] = $limit['id'];
            $data['credit_line_amount'] =  $limit['amount'];
            $data['reference_code'] = "CA-".$ref_code; // Credit Application
            $data['processed_by'] = "SYSTEM";
            $data['processed_date'] = date("Y-m-d H:i:s");

            if($credit_approval_settings['value']=="1"){ // means auto approved                
                $data['status'] = "APPROVED";
                $setting_message = $this->global_settings(3); // 3 is for approve credit line email/text
                
            }else{
                $data['status'] = "PENDING";
                $setting_message = $this->global_settings(4); // 4 is for pending credit line email/text
            }

            $insert = $this->db->insert('tblCustomerCreditLineApplication', $data);
            if($insert){
                $sms_message = str_replace("{amount}",$limit['amount'],$setting_message['sms_message']);
                $email_message = str_replace(array("{amount}","{firstname}"),array($limit['amount'],$customer_info['first_name']),$setting_message['email_message']);    
                send_sms($customer_info['mobile_number'],$sms_message);
                send_mail($email_message, $customer_info['email'], $setting_message['subject']);

                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    /*
     * Add loan application to a specific customer
     * NOTE: after tblCustomerLoanApplication insert or update, tblCustomerLoan and tblCustomerLoanHistory will automatically generate a data using the mysql trigger trg_after_tblCustomerLoanApplication_insert or trg_after_tblCustomerLoanApplication_update
     */
    public function loan_application($cust_unique_id,$amount) {
        
        $customer_info=$this->get_customer($cust_unique_id);
        $return = array();
        if(!empty($customer_info['customer_id'])){
            if($amount<=$customer_info['available_credit']){
                $ref_code = $this->generate_random_key(5);
                $loan_approval_settings = $this->global_settings(2); // 2 check if loan is auto approve or manual
                $computed_loan = $this->compute_loan_application($cust_unique_id,$amount);

                $data['fk_customer_id']  = $customer_info['customer_id'];
                $data['loan_amount']     = $amount;
                $data['interest_amount'] = $computed_loan['interest'];
                $data['fee_amount']      = $computed_loan['fee'];
                $data['total_amount']    = $computed_loan['total_amount'];
                $data['reference_code']  = "LA-".$ref_code; // Loan Application
                $data['due_date']        = $computed_loan['due_date'];
                $data['loan_date']       = date("Y-m-d H:i:s");
                $data['created_by']      = "SYSTEM";
                $data['created_date']    = date("Y-m-d H:i:s");

                if($loan_approval_settings['value']=="1"){ // means auto approved                
                    $data['status']         = "APPROVED";
                    $data['processed_by']   = "SYSTEM";
                    $data['processed_date'] = date("Y-m-d H:i:s");
                    $setting_message          = $this->global_settings(6); // 6 is for approve loan email/text
                    

                    /**
                     * forward the amount loan in the EPOINT wallet via curl starts here
                     * function is inside the helper/mloc_helper.php
                     */
                    $login = epoint_merchant_login();
                    if($login['ResponseCode']=="0000"){
                        // process amount in epoint wallet
                        $params['session_id'] = $login['ResponseMessage']['session_id'];
                        $params['amount'] = $amount;
                        $params['reference_number'] = $data['reference_code'];
                        $params['source'] = "P"; // P stands for Prefund
                        $params['destination'] = $customer_info['program_customer_id']; // customer_id in epoint wallet
                        $params['description'] = "Loan_approved_via_MLOC";
                        $params['mobile'] = $customer_info['mobile_number'];
                        $transfer_loan = epoint_merchant_fund_transfer($params);
                        #echo $transfer_loan;
                        #print_r($transfer_loan);
                        if($transfer_loan['ResponseCode']=="0000"){
                            $data['epoint_transaction_id'] = $transfer_loan['ResponseMessage']['epoint_transaction_id'];     
                        }else{
                            $return = array(
                                'status' => FALSE,
                                'message' => "Cannot transfer amount in EPOINT. Please contact epoint admin.",    
                            );
                            return $return;
                        }
                    }else{
                        $return = array(
                            'status' => FALSE,
                            'message' => 'Invalid EPOINT username or password.',    
                        );
                        return $return;
                    }
                    #$login = logout_epoint_merchant();
                    // end of curl
                    
                }else{
                    $data['status'] = "PENDING";
                    $setting_message = $this->global_settings(7); // 7 is for pending loan email/text
                }

                $insert = $this->db->insert('tblCustomerLoanApplication', $data);
                if($insert){
                    $sms_message = str_replace("{amount}",$amount,$setting_message['sms_message']);
                    $email_message = str_replace(array("{amount}","{firstname}"),array($amount,$customer_info['first_name']),$setting_message['email_message']);    
                    send_sms($customer_info['mobile_number'],$sms_message);
                    send_mail($email_message, $customer_info['email'], $setting_message['subject']);

                    $return = array(
                        'status' => TRUE,
                        'message' => 'Customer successfully applied for a loan',    
                    );
                    return $return;
                }else{
                    $return = array(
                        'status' => FALSE,
                        'message' => 'Error while processing loan application.',    
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'status' => FALSE,
                    'message' => 'You dont have enough available credit.',    
                );
                return $return;
            }
        }else{
            $return = array(
                'status' => FALSE,
                'message' => 'No existing customer',    
            );
            return $return;
        }
    }

    /*
     * compute loan application of a customer
     */
    public function compute_loan_application($cust_unique_id,$amount) {
        $customer=$this->get_customer($cust_unique_id);
        $computed_fee = array();
        $total_amount = $amount;
        if(!empty($customer['customer_id']) && $amount<=$customer['available_credit']){
            //compute fee
            $fee = $this->get_fee();
            if($fee['percentage']>0){
                $fee_amount = ($fee['percentage']/100) * $amount;
                $fee_amount = number_format($fee_amount,2);
            }else{
                $fee_amount = $fee['fixed'];
            }

            //compute interest
            $interest = $this->get_interest();
            if($interest['percentage']>0){
                $interest_amount = ($interest['percentage']/100) * $amount;
                $interest_amount = number_format($interest_amount,2);
            }else{
                $interest_amount = $interest['fixed'];
            }
            $tier_no_days = $this->get_loan_credit_limit($customer['credit_line_id']);
            
            $due_date =  date('Y-m-d H:i:s', strtotime("+".$tier_no_days['no_of_days']." days"));
            $due_date_formatted =  date('m-d-Y h:i A', strtotime("+".$tier_no_days['no_of_days']." days"));
            #$loan_interval = $this->get_loan_interval();
            #$due_date =  date('m-d-Y', strtotime("+".$loan_interval['no_of_days']." days"));
            #$due_date =  date('m-d-Y', strtotime("+35 days"));
            $total_amount += $fee_amount;
            $total_amount += $interest_amount;
            $computed_fee = array(
                "available_credit" => number_format($customer['available_credit'],2),
                "amount" => number_format($amount,2),
                "fee" => $fee_amount,
                "interest" => $interest_amount,
                "date_applied" => date('Y-m-d H:i:s'),
                "due_date" => $due_date,
                "due_date_formatted" => $due_date_formatted,
                "total_amount" => number_format($total_amount,2)
            );
            return $computed_fee;
        }else{
            return false;
        }
    }

    /*
     * pay loan to mloc
     * NOTE: after tblCustomerPayment insert, tblCustomerPaymentHistory will automatically generate a data using the mysql trigger trg_after_tblCustomerPayment_insert
     */
    public function pay_loan($cust_unique_id,$amount) {
        $loan_details = $this->get_customer_loan_list($cust_unique_id); 
        $customer_info=$this->get_customer($cust_unique_id);
        $return = array();
        // check if customer exist
        if(!empty($customer_info['customer_id'])){

            
            /**
             * forward the amount loan in the EPOINT wallet via curl starts here
             * function is inside the helper/mloc_helper.php
             */
            $login = epoint_merchant_login();
            if($login['ResponseCode']=="0000"){
                // process amount in epoint wallet
                $params['session_id'] = $login['ResponseMessage']['session_id'];
                $params['customer_id'] = $customer_info['program_customer_id']; // customer_id in epoint wallet
                $params['mobile'] = $customer_info['mobile_number'];
                $customer_wallet_balance = epoint_merchant_customer_balance($params);
                if($customer_wallet_balance['ResponseCode']=="0000"){
                    $balance = $customer_wallet_balance['ResponseMessage']['available_balance'];     
                }else{
                    $return = array(
                        'status' => FALSE,
                        'message' => "Cannot retrieve customer balance.",    
                    );
                    return $return;
                }
            }else{
                $return = array(
                    'status' => FALSE,
                    'message' => 'Invalid EPOINT username or password.',    
                );
                return $return;
            }
            // check if customer balance in wallet is enough for payment
            if($amount<=$balance){
                $ref_code                   = $this->generate_random_key(5);
                $data['fk_customer_id']     = $customer_info['customer_id'];
                $data['reference_code']     = "PL-".$ref_code; // Payment reference
                $data['payment_amount']     = $amount;
                $data['date_paid']          = date("Y-m-d H:i:s");
                $data['paid_by']            = $customer_info['customer_id'];
                
                // process amount in epoint wallet
                $params['amount']           = $amount;
                $params['reference_number'] = $data['reference_code'];
                $params['source']           = $customer_info['program_customer_id']; // customer_id in epoint wallet
                $params['destination']      = "S"; // S stands for Settlement
                $params['description']      = "Loan_payment_via_MLOC";
                $params['mobile']           = $customer_info['mobile_number'];
                $transfer_loan              = epoint_merchant_fund_transfer($params);
                if($transfer_loan['ResponseCode']=="0000"){
                    $data['epoint_transaction_id'] = $transfer_loan['ResponseMessage']['epoint_transaction_id'];     
                }else{
                    $return = array(
                        'status' => FALSE,
                        'message' => "Cannot transfer amount in EPOINT. Please contact epoint admin.",
                    );
                    return $return;
                }

                /**
                 * NOTE: after tblCustomerPayment insert,the data in tblCustomerPaymentHistory and tblCustomerLoanTransaction will automatically update using the mysql trigger trg_after_tblCustomerPayment_insert
                */
                $insert = $this->db->insert('tblCustomerPayment', $data);
                if($insert){
                    $customer_payment_id    = $this->db->insert_id();
                    $setting_message          = $this->global_settings(9); // 9 is for loan payment email
                    $sms_message = str_replace("{amount}",$amount,$setting_message['sms_message']);
                    $email_message = str_replace(array("{amount}","{firstname}"),array($amount,$customer_info['first_name']),$setting_message['email_message']);    
                    send_sms($customer_info['mobile_number'],$sms_message);
                    send_mail($email_message, $customer_info['email'], $setting_message['subject']);

                    //start of payment distribution
                    $payment_amount = $amount;
                    foreach ($loan_details as $row) {
                        if($payment_amount>0){
                            $fee=0.00;
                            $base_amount=0.00;
                            $ispaid = 0;
                            $total_fee = 0;
                            $total_base = 0;

                            //check if fee is already paid in tblCustomerLoan
                            if($row['fee_amount']!=$row['total_paid_fee']){
                                $fee = $row['fee_amount']-$row['total_paid_fee'];
                                if($payment_amount>=$fee) $payment_amount -= $fee;
                                else {
                                    $fee = $payment_amount;
                                    $payment_amount = 0;
                                }
                            }

                            //check if principal amount is already paid in tblCustomerLoan
                            if($row['loan_amount']!=$row['total_paid_principal']){
                                $base_amount = $row['loan_amount']-$row['total_paid_principal'];
                                if($payment_amount>=$base_amount) $payment_amount -= $base_amount;
                                else {
                                    $base_amount = $payment_amount;
                                    $payment_amount = 0;
                                }
                            }

                            //check if all loan is paid per id in tblCustomerLoan
                            $total_fee  = $fee+$row['total_paid_fee'];
                            $total_base = $base_amount+$row['total_paid_principal'];
                            if($total_base==$row['loan_amount'] && $total_fee==$row['fee_amount']) $ispaid = 1;
                            
                            /**
                             * NOTE: after tblCustomerLoan update,the data in tblCustomerCreditLine and tblCustomerLoanTotal will automatically update using the mysql trigger trg_after_tblCustomerLoan_update
                             */
                            //update tblCustomerLoan
                            $loan_update['total_paid_principal'] = $row['total_paid_principal'] + $base_amount;
                            $loan_update['total_paid_fee']       = $row['total_paid_fee'] + $fee;
                            $loan_update['is_paid']              = $ispaid;
                            $loan_update['total_paid_amount']    = $row['total_paid_amount'] + ($fee+$base_amount);
                            $update_loan_per_id = $this->db->update('tblCustomerLoan', $loan_update, array('id'=>$row['id']));
                            #echo $this->db->last_query();
                            

                            //update settlement
                            $settlement['fk_customer_id']      = $customer_info['customer_id'];
                            $settlement['customer_loan_id']    = $row['id'];
                            $settlement['customer_payment_id'] = $customer_payment_id;
                            $settlement['settlement_amount']   = $fee+$base_amount;
                            $settlement['principal_amount']         = $base_amount;
                            $settlement['fee_amount']          = $fee;
                            $settlement['created_date']        = date("Y-m-d H:i:s");
                            $insert_settlement = $this->db->insert('tblCustomerSettlement', $settlement);
                        }
                    }

                    //end of payment distribution

                    #$logout = epoint_merchant_logout($params['session_id']);
                    $return = array(
                        'status' => TRUE,
                        'message' => 'Customer successfully paid a loan',    
                    );
                    return $return;
                }else{
                    $return = array(
                        'status' => FALSE,
                        'message' => 'Error while processing loan payment.',    
                    );
                    return $return;
                }
            }else{
                #$logout = epoint_merchant_logout($params['session_id']);
                $return = array(
                    'status' => FALSE,
                    'message' => 'You dont have enough available wallet balance.',    
                );
                return $return;
            }
        }else{
            $return = array(
                'status' => FALSE,
                'message' => 'No existing customer',    
            );
            return $return;
        }
    }

    /*
     * Fetch customer transaction history
     */
    public function get_transaction_history($cust_unique_id = "",$type=""){
        $fk_customer_id=$this->get_customer($cust_unique_id);
        $query = $this->db->select("*");
        $query = $this->db->from('view_transaction_history');
        $query = $this->db->where(array('fk_customer_id' => $fk_customer_id['customer_id']));
        $query = $this->db->order_by("t_date desc");
        
        if(!empty($type)){
            $query = $this->db->where(array('t_type' => $type));
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_loan_credit_limit($credit_line_id=""){
        $query = $this->db->select("*");
        $query = $this->db->from('tblLoanCreditLimit');
        if(!empty($credit_line_id)){
            $query = $this->db->where(array('id' => $credit_line_id));
        }else{
            $query = $this->db->where(array('active' => "YES"));
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_fee(){
        $query = $this->db->get_where('tblFee', array('active' => "YES"));
        return $query->row_array();
    }

    public function get_interest(){
        $query = $this->db->get_where('tblInterest', array('active' => "YES"));
        return $query->row_array();
    }

    public function get_loan_interval(){
        $query = $this->db->get_where('tblLoanInterval', array('active' => "YES"));
        return $query->row_array();
    }

    public function get_customer_loan($cust_unique_id=""){
        $fk_customer_id=$this->get_customer($cust_unique_id);
        $query = $this->db->get_where('tblCustomerLoanTotal', array('fk_customer_id' => $fk_customer_id['customer_id']));
        return $query->row_array();

    }

    public function get_customer_loan_list($cust_unique_id=""){
        $fk_customer_id=$this->get_customer($cust_unique_id);
        $query = $this->db->select("*");
        $query = $this->db->from('tblCustomerLoan');
        $query = $this->db->where(array('fk_customer_id' => $fk_customer_id['customer_id']));
        $query = $this->db->where(array('is_paid' => "0"));
        $query = $this->db->order_by("id");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /*
     * check if customer key exist
     */
    public function check_if_customer_key_exist($customer_id,$mobile) {
        $return = array();
        $cust_unique_id = $this->convert_customer_id($customer_id,$mobile);
        $query = $this->db->get_where('view_customer_info', array('cust_unique_id' => $cust_unique_id));
        $row = $query->row_array();
        if (isset($row))
        {
            $return = array(
                "cust_unique_id" => $row['cust_unique_id'],
                "api_key" => $row['key'],
                "mloc_access" => $row['mloc_access'],
                "registration" => $row['registration'],
                "term_and_condition" => $row['term_and_condition']
            );
        }
        return $return;
    }

    /*
     * generate customer key
     */
    public function generate_customer_key($customer_id,$mobile) {
        $cust_unique_id = $this->convert_customer_id($customer_id,$mobile);
        $data = array(); 
        $data['cust_unique_id'] = $cust_unique_id; 
        $data['program_customer_id'] = $customer_id; 
        $data['program_customer_mobile'] = $mobile; 
        /**
         * NOTE: after tblCustomerBasicInfo insert, tblCustomerOtherInfo and tblCustomerAgreement and tblCustomerCreditLine will automatically generate a fk_customer_id using the mysql trigger trg_after_tblCustomerBasicInfo_insert
         */
        $insert = $this->db->insert('tblCustomerBasicInfo', $data);
        if($insert){
            $apikey = array();
            $apikey['fk_customer_id'] =  $this->db->insert_id();
            $apikey['key'] =  $this->generate_random_key(20);
            $insert_customer = $this->db->insert('tblApiKey', $apikey);

            $return = array(
                "cust_unique_id" => $cust_unique_id,
                "api_key" => $apikey['key'],
                "mloc_access" => "0",
                "registration" => "0",
                "term_and_condition" => "0"
            );

            return $return;
        }else{
            return false;
        }
    }

    public function global_settings($id=""){
        $query = $this->db->get_where('tblSystemSettings', array('id' => $id));
        return $query->row_array();
    }

    /*
     * convert customer_id and mobile in md5
     */
    private function convert_customer_id($customer_id,$mobile){
        $md = $customer_id."_".$mobile;
        $cust_unique_id = md5($md);
        return $cust_unique_id;
    }

    /*
     * generate random key for api key / Credit Line and Loan
     */
    private function generate_random_key($random_string_length = 20){
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $random_string_length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }

    /*
     * get temporary api key of the customer
     */
    public function get_temporary_apikey(){
        $query = $this->db->get_where('tblApiKey', array('id' => "2"));
        $row = $query->row_array();
        if (isset($row))
        {
            $return = array(
                "cust_unique_id" => "",
                "api_key" => $row['key'],
                "mloc_access" => "0",
                "registration" => "0",
                "term_and_condition" => "0"
            );
        }
        return $return;
    }

    

}
?>