<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Wilayah extends REST_Controller {
    public function __construct() {
		parent::__construct();

		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header('Content-Type: application/json');		
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == "OPTIONS") {
				die();
		}
				
		$this->load->model('user_model');
		$this->load->model('api_model');
		$this->load->model('apibdt_model');
		$this->objOfJwt = new ImplementJwt();
	}


    public function GetTokenData($received_Token='')
    {
		$hasil = false;
		$auth = explode(" ",$received_Token['Authorization']);
		if($auth['0']=='Bearer'){

			if(trim($auth[1])){
				try{
					$jwtData = $this->objOfJwt->DecodeToken(trim($auth[1]));
					$hasil = json_encode($jwtData);
				}catch (Exception $e){
					http_response_code('401');
					$this->set_response($hasil, REST_Controller::HTTP_OK);
				}
			}
	
		}
		return $hasil;
	}
		
	function index_get(){

		$received_token = $this->input->request_headers();
		$invalidLogin = [
			'status' =>'failed',
			'invalid' => 'Data tidak bisa diakses tanpa authorisasi'];
		$failed = false;
		if(array_key_exists('Authorization',$received_token)){
			$auth = $this->GetTokenData($received_token);
			if($auth !== false){
				$auth = json_decode($auth);
				if(@$auth->success){
					$data = $this->apibdt_model->bdt_wilayah_kerja_user($auth->wilayah_kerja);
					$hasil = array(
						'status'=>'ok',
						'iat'=>time(),
						'iss'=>base_url(),
						'data' => $data
					);
					$this->set_response($hasil, REST_Controller::HTTP_OK);
				}
			}
		}
		if($failed){
			$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
		}
	}
	
	function index_put(){

	}

	function index_post(){

	}
	function index_delete(){

	}

	
}