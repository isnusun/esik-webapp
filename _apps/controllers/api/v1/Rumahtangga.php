<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');
require(APPPATH . '/libraries/ImplementJwt.php');

class Rumahtangga extends REST_Controller {

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
					http_response_code('401');
					$this->set_response($hasil, REST_Controller::HTTP_OK);
				}
			}
	
		}
		return $hasil;
    }	

	function index_get(){
		/**
		 * Daftar Rumah tangga dalam ruang lingkup petugas
		 * 
		 * $varID => id_rumahtangga/nopesertapbdt
		 * 
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
						$varPage = $this->input->get('page');
						$data = $this->apibdt_model->bdt_rts_by_wilayah($auth['wilayah_kerja'],$varPage);
						$status = array(
							'status' => 'ok',
							'iat'=>time(),
							'iss'=>base_url(),
						);
						$hasil = array_merge($status,$data);
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

	function index_post($varID=0){
		/**
		 * Post Multiple RTS
		 * 
		 * 
		{	
			"sta_keberadaan_rt":"0",
			"nama_krt":"WARNO",
			"jumlah_art":"5",
			"jumlah_keluarga":"1",
			"sta_bangunan":"0",
			"sta_lahan":"1",
			"luas_lantai":"0",
			"lantai":"1",
			"dinding":"1",
			"kondisi_dinding":"1",
			"atap":"1",
			"kondisi_atap":"1",
			"jumlah_kamar":"0",
			"sumber_airminum":"1",
			"nomor_meter_air":"",
			"cara_peroleh_airminum":"1",
			"sumber_penerangan":"1",
			"daya":"1",
			"nomor_pln":"",
			"bb_masak":"1",
			"nomor_gas":"",
			"fasbab":"1",
			"kloset":"1",
			"buang_tinja":"1",
			"ada_tabung_gas":"1",
			"ada_lemari_es":"3",
			"ada_ac":"1",
			"ada_pemanas":"3",
			"ada_telepon":"1",
			"ada_tv":"3",
			"ada_emas":"1",
			"ada_laptop":"3",
			"ada_sepeda":"1",
			"ada_motor":"3",
			"ada_mobil":"1",
			"ada_perahu":"3",
			"ada_motor_tempel":"1",
			"ada_perahu_motor":"3",
			"ada_kapal":"1",
			"aset_tak_bergerak":"1",
			"luas_atb":"0",
			"rumah_lain":"3",
			"jumlah_sapi":"0",
			"jumlah_kerbau":"0",
			"jumlah_kuda":"0",
			"jumlah_babi":"0",
			"jumlah_kambing":"0",
			"sta_art_usaha":"0",
			"sta_kks":"1",
			"sta_kip":"3",
			"sta_kis":"1",
			"sta_bpjs_mandiri":"3",
			"sta_jamsostek":"1",
			"sta_asuransi":"3",
			"sta_pkh":"1",
			"sta_rastra":"3",
			"sta_kur":"1",
			"idbdt":"3312010002000217",
			"kode_wilayah":"331201000200000000",
			"periode_id":"4"
		}

		{
			"foto-sta_bangunan":{
				"name":"Screenshot from 2019-08-15 14-37-03.png",
				"type":"image\/png",
				"tmp_name":"\/tmp\/phpb7yWsP",
				"error":0,"size":130459
			},
			"foto-lantai":{
				"name":"Screenshot from 2019-08-09 09-07-05.png",
				"type":"image\/png",
				"tmp_name":"\/tmp\/phpjNZ1R1",
				"error":0,
				"size":130000
			},
			"foto-dinding":{
				"name":"Screenshot from 2019-08-06 11-47-43.png",
				"type":"image\/png",
				"tmp_name":"\/tmp\/phpVYZ8ge",
				"error":0,"size":179429
			},
			"foto-atap":{
				"name":"Screenshot from 2019-08-06 12-01-04.png",
				"type":"image\/png",
				"tmp_name":"\/tmp\/phpJA4hGq",
				"error":0,
				"size":150507
			},
			"foto-sumber_airminum":{
				"name":"Screenshot from 2019-08-06 11-43-12.png",
				"type":"image\/png",
				"tmp_name":"\/tmp\/phpGaRs5C",
				"error":0,"size":135056
			},
			"foto-sumber_penerangan":{
				"name":"Screenshot from 2019-08-05 16-27-33.png",
				"type":"image\/png","tmp_name":"\/tmp\/phpVWcFuP",
				"error":0,"size":135365
			},
			"foto-kloset":{
				"name":"Screenshot from 2019-08-01 10-11-24.png","type":"image\/png",
				"tmp_name":"\/tmp\/php9YiTT1","error":0,"size":160574
			}
		}

		 * 
		 *  format :
		  [
				{
					"pbdtid":"123456789",
					"data":{
						"{indikator_id}":"{value}",
					}
				},
		  ]

		  contoh : 
		  "pbdtid":"",
		  "data":{
			  "tanggal_lahir":
		  }
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
						
						$indikator = $this->apibdt_model->bdt_indikator('rts');
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

						$posted = $this->input->post();
						$idbdt = $posted['idbdt'];
						$rts = $this->apibdt_model->get_rts_detail($idbdt);

						# code...
						$strSQL = "SELECT lead_id FROM bdt_rts WHERE idbdt='".fixSQL($idbdt)."' AND periode_id='".fixSQL($periode_aktif)."'";
						$bdt_check = $this->apibdt_model->do_query($strSQL);

						
						if($bdt_check == false){
							// $column = rtrim($str_column);
							$action = "insert";
							$strSQLData = "INSERT INTO bdt_rts(".$str_column." `idbdt`,`periode_id`,`kode_wilayah`,`created_by`) VALUES (";
							foreach($column_to_fill as $col){
								$strSQLData.="'".fixSQL($rs[$col])."',";
							}
							$strSQLData.= "'".fixSQL($idbdt)."','".fixSQL($periode_aktif)."','".fixSQL($rs['kode_wilayah'])."',".$user['id'].");";
						}else{
							$action = "update";
							$strSQLData = "UPDATE bdt_rts SET ";
							foreach($column_to_fill as $col){
								$strSQLData.="".$col."='".fixSQL($_POST[$col])."', ";
							}
							$strSQLData .=" created_by='".$user['id']."'
							WHERE idbdt='".fixSQL($idbdt)."' AND (periode_id='".fixSQL($periode_aktif)."');";
						}

						$entry_data = $this->apibdt_model->do_insert_update($strSQLData);

						if(isset($_FILES)){
							$strSQL = "INSERT INTO bdt_fotos(`idbdt`,`indikator`, `periode_id`, `foto`, `created_by`) VALUES ";
							foreach($_FILES as $key=>$file){
								$uploaded = _bdt_upload_foto($idbdt,$periode_aktif,$key);
								if($uploaded != 'error'){
									$strSQL .="('".fixSQL($idbdt)."','".fixSQL($key)."','".fixSQL($periode_aktif)."','".fixSQL($uploaded)."','".$user['id']."'),";
								}
							}
							// echo $strSQL;

							$SQLFoto = rtrim($strSQL,",");
							// echo $SQLFoto;

							$uplod_foto = $this->apibdt_model->do_insert_update($SQLFoto);
						}
						$strLog = array(
							'data'=>$strSQLData,
							'foto'=>$SQLFoto
						);
	
						
						$hasil = array(
							'status'=>'success',
							'data'=>array('idbdt'=>$idbdt,
							'action'=>$action .': '.$entry_data.' baris',
							// 'sqlFoto'=>$SQLFoto,
							'hasil'=>'success')
						);
						// $sqlLog = "INSERT INTO bdt_log(`user_id`,`idbdt`, `data`, `created_from`, `rts`, `hasil`) 
						// VALUES('".$user['id']."','".$idbdt."','".fixSQL($sqlLog)."','".fixSQL($_SERVER['REMOTE_ADDR'])."',1,'".json_encode($hasil)."')";
						// $this->apibdt_model->do_insert_update($sqlLog);

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

	function detail_get($varID=0){
		$gagal = true;
		$received_token = $this->input->request_headers();
		if(array_key_exists('Authorization',$received_token)){
			$received_token = $this->input->request_headers('Authorization');
			if(array_key_exists('Authorization',$received_token)){
				$auth = $this->GetTokenData($received_token);
				if($auth !== false){
					$auth = (array)json_decode($auth);
					if($auth['success']){
						$data = $this->apibdt_model->get_rts($varID);
						if($data){
							$gagal = false;
							$hasil = array(
								'status'=>'ok',
								'iat'=>time(),
								'iss'=>base_url(),
								'data'=>$data,
							);
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


	function detail_post($varID){
		$gagal = true;
		$received_token = $this->input->request_headers();
		if(array_key_exists('Authorization',$received_token)){
			$received_token = $this->input->request_headers('Authorization');
			if(array_key_exists('Authorization',$received_token)){
				$auth = $this->GetTokenData($received_token);
				if($auth !== false){
					$auth = (array)json_decode($auth);
					if($auth['success']){
						$periode_id = $this->apibdt_model->periodes();

						// $strSQL = "SELECT lead_id FROM bdt_rts WHERE periode_id='".fixSQL($periode_id['periode_aktif'])."' AND kode_wilayah LIKE '".fixSQL($auth['wilayah_kerja'])."%' AND idbdt='".fixSQL($varID)."'";
						// // echo $strSQL;
						// $query = $this->db->query($strSQL);
						// if($query->num_rows() > 0){
						// 	$strSQL = "UPDATE bdt_rts SET 
						// 	WHERE periode_id='".fixSQL($periode_id['periode_aktif'])."' AND kode_wilayah LIKE '".fixSQL($auth['wilayah_kerja'])."%' AND idbdt='".fixSQL($varID)."'";
						// }else{
						// 	$strSQL = "INSERT INTO bdt_rts(`kode_wilayah`, `periode_id`, `ruta6`, 
						// 	`nopesertapbdt`, `nopbdtkemsos`, `vector1`, `vector2`, `vector3`, `vector4`, `kdgabungan4`, 
						// 	`kdprop`, `kdkab`, `kdkec`, `kddesa`, `alamat`, `adapkh`, `adapbdt`, `adakks2016`, `adakks2017`, `adapbi`, `adadapodik`, `adabpnt`, `nopesertapkh`, `nopesertakks2016`, `nopesertapbi`, `pesertakip`, `nokartudebit`, `nama_sls`, 
						// 	`nama_krt`, `jumlah_art`, `jumlah_keluarga`, `sta_bangunan`, `sta_lahan`, `luas_lantai`, `lantai`, `dinding`, `kondisi_dinding`, `atap`, `kondisi_atap`, `jumlah_kamar`, `sumber_airminum`, `nomor_meter_air`, `cara_peroleh_airminum`, `sumber_penerangan`, `daya`, `nomor_pln`, `bb_masak`, `nomor_gas`, `fasbab`, `kloset`, `buang_tinja`, `ada_tabung_gas`, `ada_lemari_es`, `ada_ac`, `ada_pemanas`, `ada_telepon`, `ada_tv`, `ada_emas`, `ada_laptop`, `ada_sepeda`, `ada_motor`, `ada_mobil`, `ada_perahu`, `ada_motor_tempel`, `ada_perahu_motor`, `ada_kapal`, `aset_tak_bergerak`, `luas_atb`, `rumah_lain`, `jumlah_sapi`, `jumlah_kerbau`, `jumlah_kuda`, `jumlah_babi`, `jumlah_kambing`, `sta_art_usaha`, `sta_kks`, `sta_kip`, `sta_kis`, `sta_bpjs_mandiri`, `sta_jamsostek`, `sta_asuransi`, `sta_pkh`, `sta_rastra`, `sta_kur`, `sta_keberadaan_rt`) VALUES()";
						// }

						$strSQL = "SELECT lead_id FROM bdt_rts WHERE idbdt='".fixSQL($_POST['idbdt'])."' AND periode_id='".fixSQL($_POST['periode_id'])."'";
						$bdt_check = $this->apibdt_model->do_query($strSQL);
						$indikator = $this->apibdt_model->bdt_indikator('rts');
						$column_to_fill = [];
						$str_column = "";
						foreach($indikator as $key=>$rs){
							if($rs["jenis"] !=''){
								$str_column .= "`".$rs['nama']."`,";
								$column_to_fill[] = $rs['nama'];
							}
						}
						// echo var_dump($bdt_check);
						if($bdt_check == false){
							// $column = rtrim($str_column);
							$strSQL = "INSERT INTO bdt_rts(".$str_column." `idbdt`,`periode_id`,`kode_wilayah`,`created_by`) VALUES (";
							foreach($column_to_fill as $col){
								$strSQL.="'".fixSQL($_POST[$col])."',";
							}
							$strSQL.= "'".fixSQL($_POST['idbdt'])."','".fixSQL($_POST['periode_id'])."','".fixSQL($_POST['kode_wilayah'])."',".$user['id'].");";
						}else{
							$strSQL = "UPDATE bdt_rts SET ";
							foreach($column_to_fill as $col){
								$strSQL.="".$col."='".fixSQL($_POST[$col])."', ";
							}
							$strSQL .=" created_by='".$user['id']."'
							WHERE idbdt='".fixSQL($_POST['idbdt'])."';";
						}
						$entry_data = $this->apibdt_model->do_insert_update($strSQL);
						if($entry_data){
							// echo $strSQL;
		
							if(@$_FILES){
								$strSQL = "INSERT INTO bdt_fotos(`idbdt`,`indikator`, `periode_id`, `foto`, `created_by`) VALUES";
								
								foreach($_FILES as $key=>$file){
									$uploaded = _bdt_upload_foto($_POST['idbdt'],$_POST['periode_id'],$key);
									$strSQL .="('".fixSQL($_POST['idbdt'])."','".fixSQL($key)."','".fixSQL($_POST['periode_id'])."','".fixSQL($uploaded)."','".$user['id']."'),";
									// echo "<hr />". $key."<hr />".var_dump($file);
								}
								$SQLFoto = rtrim($strSQL,",");
								$uplod_foto = $this->apibdt_model->do_insert_update($SQLFoto);
							}
							// redirect('cms/verval/form_rts/'.$_POST['idbdt']);
						}						
					}
				}
			}
		}
		// echo $varID;
	}

}