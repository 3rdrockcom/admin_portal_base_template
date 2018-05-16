<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}

	/**
	 * retrieve data of user via datatable
	 * @return json
	 */
	function user_list(){
		$results="";
		$this->datatables
		->select('a.userno as userno,fn_user_fullname_get(userid) as name,b.status as status,a.userid')
		->from('tblUserInformation a')
		->join('tblUserInfo b','b.fk_userid=a.userid','left');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of user via datatable
	 * @return json
	 */
	function role_list(){
		$results="";
		$this->datatables
		->select('code,description,roleid')
		->from('tblSystemRole');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of category via datatable
	 * @return json
	 */
	function category_ist(){
		$results="";
		$this->datatables
		->select('description,arranged,syscat_id')
		->from('tblSystemCategory');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of menu via datatable
	 * @return json
	 */
	function menu_list(){
		$results="";
		$this->datatables
		->select('title,`status`,a.arranged,description,menuid,comments,a.icon')
		->from('tblSystemMenu a')
		->join('tblSystemMenuCategory b','b.fk_menuid=a.menuid','left')
		->join('tblSystemCategory c','c.syscat_id=b.fk_syscatid','left');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of fee via datatable
	 * @return json
	 */
	function fee_list(){
		$results="";
		$this->datatables
		->select('code,description,percentage,fixed,id,active')
		->from('tblFee');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of interest via datatable
	 * @return json
	 */
	function interest_list(){
		$results="";
		$this->datatables
		->select('code,description,percentage,fixed,id,active')
		->from('tblInterest');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of credit line via datatable
	 * @return json
	 */
	function line_credit_list(){
		$results="";
		$this->datatables
		->select('first_name,last_name,email,credit_limit,available_credit,is_suspended,credit_approved_by')
		->from('view_customer_info')
		->where('first_name!=',NULL);
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of active loans via datatable
	 * @return json
	 */
	function active_loan_list(){
		$results="";
		$this->datatables
		->select('first_name,last_name,loan_amount,fee_amount,total_amount,total_paid_amount,fn_date_format(due_date) as due_date, fn_date_format(loan_date) as loan_date')
		->from('tblCustomerLoan a')
		->join('tblCustomerBasicInfo b','b.id=a.fk_customer_id','inner')
		->where('is_paid',"0");;
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of settlement payment via datatable
	 * @return json
	 */
	function payment_history_list(){
		$results="";
		$this->datatables
		->select('first_name,last_name,settlement_amount,principal_amount,fee_amount,payment_amount,fn_date_format(date_paid) as date_paid,reference_code')
		->from('tblCustomerSettlement a')
		->join('tblCustomerBasicInfo b','b.id=a.fk_customer_id','inner')
		->join('tblCustomerPayment c','c.id=a.customer_payment_id','inner');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of loan history via datatable
	 * @return json
	 */
	function loan_history_list(){
		$results="";
		$this->datatables
		->select('first_name,last_name,loan_amount,fee_amount,total_amount,reference_code,fn_date_format(due_date) as due_date,status')
		->from('tblCustomerLoanHistory a')
		->join('tblCustomerBasicInfo b','b.id=a.fk_customer_id','inner');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of Curriculum year via datatable
	 * @return json
	 */
	public function customer_list(){
		$results="";
		/*
		 ->edit_column('customer_id','
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'1\',\'$2\',\'$3\')"; title="Basic Information"><i class="fa fa-user"></i></button>
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'2\',\'$2\',\'$3\')"; title="Additional Information"><i class="fa fa-info-circle"></i></button>
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'3\',\'$2\',\'$3\')"; title="Credit Line"><i class="fa fa-credit-card"></i></button>
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'4\',\'$2\',\'$3\')"; title="Loans"><i class="fa fa-money"></i></button>
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'5\',\'$2\',\'$3\')"; title="Payments"><i class="fa fa-check-circle"></i></button>
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'6\',\'$2\',\'$3\')"; title="Analytics"><i class="fa fa-bar-chart-o"></i></button>',
			'customer_id,email,fullname')
		 */
		$this->datatables
		->edit_column('customer_id','
			<button class="btn btn-xs btn-default" onclick="edit_customer(\'$1\',\'1\',\'$2\',\'$3\')"; title="Basic Information"><i class="fa fa-user"></i> View Info</button>',
			'customer_id,email,fullname')
		->select('customer_id,fn_fullname_get(customer_id) as fullname,email,credit_limit,available_credit,first_name,last_name')
		->from('view_customer_info a')
		->where('first_name!=',NULL);
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of credit lines via datatable
	 * @return json
	 */
	function credit_line_list(){
		$results="";
		$this->datatables
		->select('id,tier,code,description,amount,no_of_days, active')
		->from('tblLoanCreditLimit a');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of interval via datatable
	 * @return json
	 */
	function interval_list(){
		$results="";
		$this->datatables
		->select('id,description,no_of_days, active')
		->from('tblLoanInterval a');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	/**
	 * retrieve data of settings via datatable
	 * @return json
	 */
	public function settings_list($canedit=''){
		$results="";
		$this->datatables
		->select('updated_by,
				  id,
				  code,
				  name,
				  description,
				  value,
				  setting_type,
				  is_active,
				  sms_message,
				  email_message,
				  subject,
				  from,
				  to,
				  cc,
				  bcc')
		->edit_column('updated_by', '<div class="details-control"></div>', 'updated_by')
		->edit_column('id',($canedit?
		   '<button class="btn btn-success " onclick="edit_settings(\'$1\')" value="$1">
		   		Edit Settings
		   	</button>':'&nbsp;'),'id')
		->from('tblSystemSettings a');
		$results = $this->datatables->generate('json','UTF-8');
		return $results;
	}

	

}

/* End of file datatable_model.php */
/* Location: ./application/models/datatables/datatable_model.php */