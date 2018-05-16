<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->view('welcome_message');

		//API URL
		$url = 'http://mlocdev.epointserver.com/api/api/example/user/';

		//API key
		$apiKey = 'CODEX@123';

		//Auth credentials
		$username = "admin";
		$password = "1234";

		/*
		$userData = array(
			'id' => 73,
			'format' => 'html',
		);
		*/
		$userData = "?id=73";
		$userData .= "&format=json";
		
		//create a new cURL resource
		$ch = curl_init($url.$userData);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

		$result = curl_exec($ch);

		echo $result;

		//close cURL resource
		curl_close($ch);	
	}

	public function country_post()
	{
		//$this->load->view('welcome_message');

		//API URL
		$url = 'http://mlocdev.epointserver.com/api/api/example/countries/';

		//API key
		$apiKey = 'CODEX@123';

		//Auth credentials
		$username = "admin";
		$password = "1234";

		
		$userData = array(
			'format' => 'json',
		);
		
		#$userData = "?id=73";
		#$userData .= "&format=json";
		
		//create a new cURL resource
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey));
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);

		$result = curl_exec($ch);

		echo $result;

		//close cURL resource
		curl_close($ch);	
	}
}
