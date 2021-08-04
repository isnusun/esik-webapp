<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Indikator extends REST_Controller {
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
		$received_token = $this->input->request_headers();
		if(array_key_exists('Authorization',$received_token)){
			$this->objOfJwt = new ImplementJwt();
			$tokens = $received_token['Authorization'];
			$token = explode(" ",$tokens);
			$auth = false;
			if($token['0']=='Bearer'){
				if(trim($token[1])){
					try{
						$auth = $this->objOfJwt->DecodeToken(trim($token[1]));
					}catch (Exception $e){
						http_response_code('401');
						$hasil=[
							'status'=>'failed',
							'invalidData'=>'Data Authorisasi Gagal'];
						$this->set_response($hasil, REST_Controller::HTTP_OK);
					}
				}
		
			}

			if(!$auth['success']){
				$invalidLogin =[
					'status'=>failed,
					'invalid'=>'Anda tidak berhak mengakses data ini'
				];
				$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
			}
		}		
				
		$this->load->model('user_model');
		$this->load->model('api_model');
		$this->load->model('apibdt_model');
	}

	function rts_get(){
		$indikator = $this->apibdt_model->bdt_indikator('rts');
		// echo var_dump($indikator);
		if($indikator){
			$data = array(
				'status'=>'ok',
				'iat'=>time(),
				'iss'=>base_url(),
				'data'=>$indikator
			);
			$this->set_response($data, REST_Controller::HTTP_OK);
		}else{
			$invalidLogin =[
				'status'=>failed,
				'invalid'=>'Anda tidak berhak mengakses data ini'
			];
			$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
		}
	}
	
	function art_get(){
		$indikator = $this->apibdt_model->bdt_indikator('art');
		if($indikator){
			$data = array(
				'status'=>'ok',
				'iat'=>time(),
				'iss'=>base_url(),
				'data'=>$indikator
			);
			$this->set_response($data, REST_Controller::HTTP_OK);
		}else{
			$invalidLogin =[
				'status'=>failed,
				'invalid'=>'Anda tidak berhak mengakses data ini'
			];
			$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
		}
	}
	
}