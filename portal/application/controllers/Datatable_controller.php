<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Datatable_controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->model('datatables/datatable_model','dt');
    }

    function index(){
      switch($this->input->post('function_ctrl')){
            case "dbuserlist" : $this->dbuserlist();  break;
            case "role_list"  : $this->role_list();   break;
            case "clist"      : $this->clist();       break;
            case "menu_list"  : $this->menu_list();   break;
            case "fee_list" : $this->fee_list();  break;
            case "interest_list" : $this->interest_list();  break;
            case "credit_line_list" : $this->credit_line_list();  break;
            case "interval_list" : $this->interval_list();  break;
            case "line_credit_list" : $this->line_credit_list();  break;
            case "active_loan_list" : $this->active_loan_list();  break;
            case "payment_history_list" : $this->payment_history_list();  break;
            case "loan_history_list" : $this->loan_history_list();  break;
            case "customer_list" : $this->customer_list();  break;
            case "settings_list" : $this->settings_list();  break;
            
            
            
        }
    }

    /**
    * retrieve data of user via datatable
    * @return array
    */
    private function dbuserlist(){
        $results = $this->dt->user_list();
        echo $results;
    }

    /**
    * retrieve data of user via datatable
    * @return array
    */
    private function role_list(){
        $results = $this->dt->role_list();
        echo $results;
    }

    /**
     * retrieve data of category via datatable
    * @return array
    */
    private function clist(){
        $results = $this->dt->category_ist();
        echo $results;
    }

    /**
     * retrieve data of menu via datatable
    * @return array
    */
    private function menu_list(){
        $results = $this->dt->menu_list();
        echo $results;
    }

    /**
    * retrieve data of fee via datatable
    * @return array
    */
    private function fee_list(){
      $results = $this->dt->fee_list();
      echo $results;
    }

    /**
    * retrieve data of interest via datatable
    * @return array
    */
    private function interest_list(){
        $results = $this->dt->interest_list();
        echo $results;
    }

    /**
    * retrieve data of credit line per customer via datatable
    * @return array
    */
    private function line_credit_list(){
        $results = $this->dt->line_credit_list();
        echo $results;
    }

    /**
    * retrieve data of active loans per customer via datatable
    * @return array
    */
    private function active_loan_list(){
        $results = $this->dt->active_loan_list();
        echo $results;
    }

    /**
    * retrieve data of settlement per customer via datatable
    * @return array
    */
    private function payment_history_list(){
        $results = $this->dt->payment_history_list();
        echo $results;
    }

    /**
    * retrieve data of loan history per customer via datatable
    * @return array
    */
    private function loan_history_list(){
        $results = $this->dt->loan_history_list();
        echo $results;
    }

    /**
    * retrieve data of customer via datatable
    * @return array
    */
    private function customer_list(){
        $results = $this->dt->customer_list();
        echo $results;
    }

    /**
    * retrieve data of credit line via datatable
    * @return array
    */
    private function credit_line_list(){
        $results = $this->dt->credit_line_list();
        echo $results;
    }

    /**
    * retrieve data of interval via datatable
    * @return array
    */
    private function interval_list(){
        $results = $this->dt->interval_list();
        echo $results;
    }

    /**
    * retrieve data of settings via datatable
    * @return array
    */
    private function settings_list(){
        $canedit   = $this->input->post('canedit');
        $results = $this->dt->settings_list($canedit);
        echo $results;
    }




}
