<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lookup_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        //load database library
        $this->load->database();
    }

    /*
     * Fetch country
     */
    function get_country($id = ""){
        if(!empty($id)){
            $query = $this->db->get_where('tblCountry', array('country_id' => $id));
            return $query->row_array();
        }else{
            $query = $this->db->get('tblCountry');
            return $query->result_array();
        }
    }
    
    /*
     * Fetch State
     */
    function get_state($country_id = "",$state_id = ""){
        $query = $this->db->select("*");
        $query = $this->db->from('tblState');
        $query = $this->db->where(array('country_id' => $country_id));
        
        if(!empty($state_id)){
            $query = $this->db->where(array('state_id' => $state_id));
            $query = $this->db->get();
            return $query->row_array();
        }else{
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    /*
     * Fetch City
     */
    function get_city($state_code = "",$city_id = ""){
        $query = $this->db->select("*");
        $query = $this->db->from('tblCity');
        $query = $this->db->where(array('state_code' => $state_code));
        
        if(!empty($city_id)){
            $query = $this->db->where(array('city_id' => $city_id));
            $query = $this->db->get();
            return $query->row_array();
        }else{
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    /*
     * Fetch Income Source
     */
    function get_income_source($id = ""){
        if(!empty($id)){
            $query = $this->db->get_where('tblIncomeSource', array('id' => $id));
            return $query->row_array();
        }else{
            $query = $this->db->get('tblIncomeSource');
            return $query->result_array();
        }
    }

    /*
     * Fetch Pay Frequency
     */
    function get_pay_frequency($id = ""){
        if(!empty($id)){
            $query = $this->db->get_where('tblPayFrequency', array('id' => $id));
            return $query->row_array();
        }else{
            $query = $this->db->get('tblPayFrequency');
            return $query->result_array();
        }
    }

    

    

}
?>