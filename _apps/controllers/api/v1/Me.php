<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Me extends REST_Controller {
    public function __construct() {
		parent::__construct();

		header('Vary: Accept-Encoding');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: *");
		// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Cache-Control: no-cache, private");
		header('Content-Type: application/json');
		
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == "OPTIONS") {
				die();
		}

		$this->load->model('user_model');
		$this->load->model('api_model');
		$this->objOfJwt = new ImplementJwt();
	}

    public function GetTokenData($received_Token='')
    {
		$hasil = false;
		// echo var_dump($received_Token['Authorization']);
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
		$invalidLogin = array(
			'status'=> 'failed',
			'invalid' => 'Data tidak bisa diakses tanpa authorisasi');
		if(array_key_exists('Authorization',$received_token)){
			$auth = $this->GetTokenData($received_token);
			if($auth !== false){
				$auth = json_decode($auth);
				if(@$auth->success){
					$msg = "Detail Data";
					$data = $this->user_model->get_user($auth->uniqueId);
					$token = explode(" ",$received_token['Authorization']);
					$hasil = array(
						'status'=>'ok',
						'msg'=>$msg,
						'Token'=>trim($token[1]),
						'iat'=>time(),
						'iss'=>base_url(),
						'data' => $data,
					);
					$this->set_response($hasil, REST_Controller::HTTP_OK);
					
				}
			}else{
				$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
			}		
		}else{
			$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
		}
	}
}

