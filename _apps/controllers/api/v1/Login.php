<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Login extends REST_Controller {
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
		$invalidLogin = [
			'status' =>'failed',
			'invalid' => 'Data tidak bisa diakses tanpa authorisasi'];
		$gagal = true;
		if(array_key_exists('Authorization',$received_token)){
			$auth = $this->GetTokenData($received_token);
			if($auth !== false){
				$auth = json_decode($auth);
				if(@$auth->success){
					$gagal = false;
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
			}		
		}
		if($gagal){
			$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
		}
	}

	function index_post()
	{
		$gagal = false;
		$received_token = $this->input->request_headers('Authorization');
		if(array_key_exists('Authorization',$received_token)){
			$auth = $this->GetTokenData($received_token);
			if($auth !== false){
				$auth = json_decode($auth);
				if(@$auth->success){
					$msg = "Detail Data";
					if($_POST){
						$msg = "Berhasil Memperbarui Detail Data";
						$str_foto = 'error';
						if(@$_FILES){
							$str_foto = _siteman_UploadFoto();
						}
				
						$update = array();
						$strSQL = "UPDATE tweb_users SET  ";

						if($this->input->post('nama')){
							$strSQL .= " `nama` = '".fixSQL($this->input->post('nama'))."', ";
							$update[] = "Memperbarui data Nama Lengkap";
						}

						if($this->input->post('pass1')){
							$strSQL .= " `passwt` = '".password_hash($this->input->post('pass1'),PASSWORD_DEFAULT)."', ";
							$update[] = "Memperbarui Kata Sandi/Password";
						}
						if($str_foto != "error")	{
							$strSQL .= "foto = '".fixSQL($str_foto)."',";
							$strSQL .= " `foto` = '".fixSQL($str_foto)."', ";
							$update[] = "Memperbarui Foto Profil";
						}

						if($this->input->post('email')){
							$strSQL .= " `email` = '".fixSQL($this->input->post('email'))."', ";
							$update[] = "Memperbarui data Surat Elektronik / E-Mail";
						}
						if($this->input->post('nohp')){
							$strSQL .= " `nohp` = '".fixSQL($this->input->post('nohp'))."', ";
							$update[] = "Memperbarui data Nomor HP";
						}
						$strSQL .= " `updated_at`=current_timestamp WHERE userid='".fixSQL($auth->uniqueId)."'";
						$query = $this->db->query($strSQL);
						if($query){
							$msg = array(
								'msg'=>$msg,
								'list'=>$update
							);
							$data = $this->user_model->get_user($auth->uniqueId);
							$token = explode(" ",$received_token['Authorization']);
							$hasil = array(
								'status'=>'ok',
								'Token'=>trim($token[1]),
								'iat'=>time(),
								'iss'=>base_url(),
								'data' => $data,
								'msg'=>$msg,
							);
		
							$this->set_response($hasil, REST_Controller::HTTP_OK);
						}else{
							$hasil = array(
								'status'=>'failed',
								'invalid' => 'Gagal menyimpan data ke dalam sistem');
			
							$this->set_response($hasil, REST_Controller::HTTP_FAILED_DEPENDENCY);
						}						
					}
				}
			}
		}else{
			$username = trim($this->post('userid'));
			$password = trim($this->post('passwd'));
			
			$invalidLogin = [
				'status' => 'failed',
				'invalid' => 'Field Tidak Lengkap : '. $username ." - ".$password];

			if(!$username || !$password) $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
			
			$login = $this->api_model->auth($username,$password);
			if($login['success']) {
				$date = new DateTime();
				$data = $this->user_model->get_user($username);
	
				$token['uniqueId'] = $username; 
				$token['success'] = true;
				$token['wilayah_kerja'] = $login['wilayah_kerja'];
				$token['iat'] = $date->getTimestamp();
				
				$output  = array(
					'status'=>'ok',
					'token'=>$this->objOfJwt->GenerateToken($token),
					'iat'=>time(),
					'iss'=>base_url(),
					'user' => $data,
	
				);
				$this->set_response($output, REST_Controller::HTTP_OK);
			}
			else 
			{
				$invalidLogin = array(
					'status'=>'failed',
					'invalid' => 'User tidak terdaftar : ' .$login['msg']);
				$this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
			}
	
		}
	}
}

