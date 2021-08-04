<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Penduduk extends REST_Controller {

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
		// echo var_dump($received_Token['Authorization']);
		$auth = explode(" ",$received_Token['Authorization']);
		if($auth['0']=='Bearer'){

			if(trim($auth[1])){
				try{
					$jwtData = $this->objOfJwt->DecodeToken(trim($auth[1]));
					$hasil = json_encode($jwtData);
				}catch (Exception $e){
					$this->set_response($hasil, REST_Controller::HTTP_OK);
				}
			}
	
		}
		return $hasil;
    }	

	function index_get(){
		/**
		 * Daftar Penduduk dalam ruang lingkup petugas
		 * 
		 * $varID => id_rumahtangga/nopesertapbdt
		 * 
		 */
		$gagal =true;
		$received_token = $this->input->request_headers();
		if(array_key_exists('Authorization',$received_token)){
			$received_token = $this->input->request_headers('Authorization');
			if(array_key_exists('Authorization',$received_token)){
				$auth = $this->GetTokenData($received_token);
				if($auth !== false){
					$auth = (array) json_decode($auth);
					if($auth['success']){
						$varPage = $this->input->get('page');
						$data = $this->apibdt_model->bdt_penduduk_by_wilayah($auth['wilayah_kerja'],$varPage);
						if($data){
							$gagal = false;
							$status = array(
								'status' => 'ok',
								'iat'=> time(),
								'iss'=> base_url(),
								'created_at'=>time(),
							);
							$hasil = array_merge($status,$data);
							$this->set_response($hasil, REST_Controller::HTTP_OK);
						}
					}
				}
			}
		}
		if($gagal){
			$invalidLogin = [
				'status'=>'failed',
				'invalid' => 'Tidak terverifikasi '];
			$this->set_response($invalidLogin, REST_Controller::HTTP_FAILED_DEPENDENCY);
		}
	}

	function index_post($varID=0){
		/**
		 * Post Multiple ART
		 */
		$gagal = true;
		$received_token = $this->input->request_headers();
		if(array_key_exists('Authorization',$received_token)){
			$received_token = $this->input->request_headers('Authorization');
			if(array_key_exists('Authorization',$received_token)){
				$auth = $this->GetTokenData($received_token);
				if($auth !== false){
					$auth = (array)json_decode($auth);
					if($auth['success']){
						$gagal = false;
						$user = $this->user_model->get_user($auth['uniqueId']);

						$periodes = $this->apibdt_model->periodes();
						$periode_aktif = $periodes['periode_aktif'];
						$posted = $this->input->post();

						$indikator = $this->apibdt_model->bdt_indikator('art');
						$column_to_fill = [];
						$str_column = "";
						foreach($indikator as $key=>$rs){
							if($rs["jenis"] !=''){
								if(isset($_POST[$rs['nama']])){
									$str_column .= "`".$rs['nama']."`,";
									$column_to_fill[] = $rs['nama'];
								}
							}
						}


						$idbdt = $posted['idbdt'];
						$idartbdt = $posted['idartbdt'];

						$strSQL = "SELECT kode_wilayah FROM tweb_rumahtangga WHERE idbdt='".fixSQL($idbdt)."' ";
						$rts_check = $this->apibdt_model->do_query($strSQL);
						$kode_wilayah = $rts_check[0]['kode_wilayah'];
						$kdprop = substr($kode_wilayah,0,2);
						$kdkab = substr($kode_wilayah,2,2);
						$kdkec = substr($kode_wilayah,4,3);
						$kddesa = substr($kode_wilayah,7,3);

						$strSQL = "SELECT lead_id FROM bdt_idv WHERE idbdt='".fixSQL($idbdt)."' AND idartbdt='".fixSQL($idartbdt)."' AND periode_id='".fixSQL($periode_aktif)."'";
						$bdt_check = $this->apibdt_model->do_query($strSQL);

						
						if($bdt_check == false){
							// $column = rtrim($str_column);
							$action = "insert";
							$strSQL = "INSERT INTO bdt_idv(".$str_column." `idbdt`,`idartbdt`,`periode_id`,`kode_wilayah`,`kdprop`, `kdkab`, `kdkec`, `kddesa`,`created_by`) VALUES (";
							foreach($column_to_fill as $col){
								if(isset($_POST[$col])){
									$strSQL.="'".fixSQL($_POST[$col])."',";
								}
							}
							$strSQL.= "'".fixSQL($idbdt)."','".fixSQL($idartbdt)."','".fixSQL($periode_aktif)."','".fixSQL($kode_wilayah)."',
							'".$kdprop."','".$kdkab."','".$kdkec."','".$kddesa."',
							".$user['id'].");";
						}else{
							$action = "update";
							$strSQL = "UPDATE bdt_idv SET ";
							foreach($column_to_fill as $col){
								if(isset($_POST[$col])){
									$strSQL.="".$col."='".fixSQL($_POST[$col])."', ";
								}
							}
							$strSQL .=" 
							`kdprop`='".$kdprop."',
							`kdkab`='".$kdkab."',
							`kdkec`='".$kdkec."',
							`kddesa`='".$kddesa."',
							`created_by`='".$user['id']."'
							WHERE ((`idbdt`='".fixSQL($idbdt)."') 
							AND (`idartbdt`='".fixSQL($idartbdt)."')
							AND (`periode_id`='".fixSQL($periode_aktif)."'));";
						}
						$strSQLData = $strSQL;
						$entry_data = $this->apibdt_model->do_insert_update($strSQL);

						if($_FILES){
							$strSQL = "INSERT INTO bdt_fotos(`idbdt`,`idartbdt`,`indikator`, `periode_id`, `foto`, `created_by`) VALUES";
							foreach($_FILES as $key=>$file){
								$uploaded = _bdt_upload_foto($idbdt,$periode_aktif,$key);
								if($uploaded != 'error'){
									$strSQL .="('".fixSQL($idbdt)."','".fixSQL($idartbdt)."','".fixSQL($key)."','".fixSQL($periode_aktif)."','".fixSQL($uploaded)."','".$user['id']."'),";
								}
							}
							$SQLFoto = rtrim($strSQL,",");
							$uplod_foto = $this->apibdt_model->do_insert_update($SQLFoto);
						}
						$hasil = array(
							'status'=>'success',
							'data'=>array(
								'idbdt'=>$idbdt,
								'idbartdt'=>$idartbdt,
								'action'=>$action,
								// 'strSQL'=>$strSQL,
								// 'strSQLData'=>$strSQLData,
								// 'data_existing'=>$data_exist,
								'hasil'=>'success')
						);
						$this->set_response($hasil, REST_Controller::HTTP_OK);
					}
				}
			}
		}

		if($gagal){
			$invalidLogin = [
				'status'=>'failed',
				'invalid' => 'Tidak terverifikasi '];
			
			$this->set_response($invalidLogin, REST_Controller::HTTP_FORBIDDEN);
		}		

	}

	function detail_get($varID=''){
		if($varID){
			$received_token = $this->input->request_headers();
			$gagal = true;
			if(array_key_exists('Authorization',$received_token)){
				$received_token = $this->input->request_headers('Authorization');
				if(array_key_exists('Authorization',$received_token)){
					$auth = $this->GetTokenData($received_token);
					if($auth !== false){
						$auth = (array) json_decode($auth);
						if($auth['success']){
							$data = $this->apibdt_model->get_art($varID);
							if($data){
								$gagal = false;
								$hasil = array(
									'status'=>'ok',
									'iat'=> time(),
									'iss'=> base_url(),
									'data'=>$data,
								);
								$this->set_response($hasil, REST_Controller::HTTP_OK);
							}else{
								$gagal = false;
								$invalidLogin = array(
									'status'=>'failed',
									'invalid' => 'Tidak tidak ditemukan');
								$this->set_response($invalidLogin, REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
							}
						}
					}
				}
			}
			if($gagal){
				$invalidLogin = array(
					'status'=>'failed',
					'invalid' => 'Tidak tidak ditemukan');
				$this->set_response($invalidLogin, REST_Controller::HTTP_UNAUTHORIZED);
			}
		}else{
			$invalidLogin = array(
				'status'=>'failed',
				'invalid' => 'Tidak tidak ditemukan');
			$this->set_response($invalidLogin, REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
		}
	}


	function detail_post($varID){
		echo $varID;
	}

}