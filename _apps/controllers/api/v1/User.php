<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class User extends REST_Controller {
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
	

	function index_post()
	{
		$received_token = $this->input->request_headers('Authorization');
		if(array_key_exists('Authorization',$received_token)){
			$auth = $this->GetTokenData($received_token);
			if($auth !== false)
			{
				$auth = json_decode($auth);
				if(@$auth->success){
					$msg = "Detail Data";
					if($this->post()){
						$msg = "Berhasil Memperbarui Detail Data";
						$str_foto = 'error';
						if(@$_FILES){
							$str_foto = _siteman_UploadFoto();
						}
				
						$update = array();
						$strSQL = "UPDATE tweb_users SET  ";

						if($this->post('nama')){
							$strSQL .= " `nama` = '".fixSQL($this->post('nama'))."', ";
							$update[] = "Memperbarui data Nama Lengkap";
						}

						if($this->post('pass1')){
							$strSQL .= " `passwt` = '".password_hash($this->post('pass1'),PASSWORD_DEFAULT)."', ";
							$update[] = "Memperbarui Kata Sandi/Password";
						}
						if($str_foto != "error")	{
							$strSQL .= "foto = '".fixSQL($str_foto)."',";
							$strSQL .= " `foto` = '".fixSQL($str_foto)."', ";
							$update[] = "Memperbarui Foto Profil";
						}

						if($this->post('email')){
							$strSQL .= " `email` = '".fixSQL($this->post('email'))."', ";
							$update[] = "Memperbarui data Surat Elektronik / E-Mail";
						}
						if($this->post('nohp')){
							$strSQL .= " `nohp` = '".fixSQL($this->post('nohp'))."', ";
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
								'user' => $data,
								'msg'=>$msg,
							);
		
							$this->set_response($hasil, REST_Controller::HTTP_OK);
						}else{
							$hasil=[
								'status'=>'failed',
								'database'=>'Gagal menyimpan data'];
							$this->set_response($hasil, REST_Controller::HTTP_FAILED_DEPENDENCY);
						}						
					}
				}else{
					$hasil=[
						'status'=>'failed',
						'database'=>'Gagal menyimpan data'];
					$this->set_response($hasil, REST_Controller::HTTP_FAILED_DEPENDENCY);
				}
			}else{
				$invalidLogin = [
					'status'=>'failed',
					'invalid' => 'Tidak terverifikasi '];
				$this->set_response($invalidLogin, REST_Controller::HTTP_FAILED_DEPENDENCY);
			}
		}else{
			$invalidLogin = [
				'status'=>'failed',
				'invalid' => 'Tidak terverifikasi '];
			$this->set_response($invalidLogin, REST_Controller::HTTP_FAILED_DEPENDENCY);

		}

	}
}

