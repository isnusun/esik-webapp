<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Data extends REST_Controller {
    public function __construct() {
		parent::__construct();

		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == "OPTIONS") {
				die();
		}
				
		$this->load->model('user_model');
		$this->load->model('api_model');
		$this->load->model('bdt_model');
		$this->objOfJwt = new ImplementJwt();
	}

	function wilayah_get(){

		header('Content-Type: application/json');		
		$received_token = $this->input->request_headers();

		$data['status'] = 'terlarang';
		$data['msg'] = "Maaf, anda tidak berhak mengakses data ini";
		$data['authorized'] = false;
		$data['created_at'] = time();
		$hasil = $data;

		if(array_key_exists('Authorization',$received_token)){

			$tokens = $received_token['Authorization'];
			$token = explode(" ",$tokens);
			$auth = false;
			if($token['0']=='Bearer'){
				if(trim($token[1])){
					try{
						$auth = $this->objOfJwt->DecodeToken(trim($token[1]));
					}catch (Exception $e){
						http_response_code('401');
						$this->set_response($hasil, REST_Controller::HTTP_OK);
					}
				}
		
			}

			if($auth['success']){
				$data = $this->bdt_model->bdt_wilayah_kerja_user($auth['wilayah_kerja']);
				$hasil = array(
					'status' => 'ok',
					'data' => $data,
					'created_at'=>time(),
				);
			}
		}
		echo json_encode($hasil);		
	}
	
	function wilayah_post(){

	}

	function wilayah_delete(){

	}

	
}