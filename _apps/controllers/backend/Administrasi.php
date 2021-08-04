<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrasi extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->siteman_login->cek_login();

		$this->load->model('siteman_model');
		$this->load->model('wilayah_model');
		$this->load->model('penduduk_model');
		$this->load->model('administrasi_model');
	}

	function index(){
		$data['pageTitle'] = "Wilayah Administrasi";
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']: $data['user']['wilayah'];
		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$data['sub_wilayah'] = $this->wilayah_model->statistik_sub_wilayah($varKode);
		$this->load->view('administrasi/wilayah',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function wilayah_add($varKode){
		$data['pageTitle'] = "Tambah Data Wilayah Administrasi";
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['varKode'] = $varKode;
		$subNama = "";
		$subTingkat = "";
		switch (strlen($varKode)) {
			case 2:
				# code...
				$subTingkat = 2;
				$subNama = "Kabupaten";
				break;
			case 4:
				# code...
				$subTingkat = 3;
				$subNama = "Kecamatan";
				break;
			case 7:
				# code...
				$subTingkat = 4;
				$subNama = "Desa";
				break;
			case 10:
				# code...
				$subTingkat = 5;
				$subNama = "Dusun/Kampung/Lingkungan";
				break;
			case 12:
				# code...
				$subTingkat = 6;
				$subNama = "Rukun Warga (RW)";
				break;
			case 15:
				# code...
				$subTingkat = 7;
				$subNama = "Rukun Tangga (RT)";
				break;
			
			default:
				# code...
				$subNama = "Dusun/Kampung/Lingkungan";
				break;
		}
		$data['subNama'] = $subNama;
		$data['subTingkat'] = $subTingkat;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$this->load->view('administrasi/wilayah_add',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function wilayah_delete(){
		$data['user'] = $this->session->userdata();
		if($_POST['id']){
			$ids = explode("_",$_POST['id']);
			$tingkatan = _tingkat_wilayah();
			$strSQL = "DELETE FROM tweb_wilayah WHERE tingkat='".$ids[1]."' AND id=".$ids[0];
			// echo $strSQL;
			$query = $this->db->query($strSQL);
			if($query){
				$_SESSION['msg'] = "Berhasil Menghapus Data Wilayah Administrasi Baru: <strong>".$tingkatan[$ids[1]]." ".$_POST['nama']."</strong>";
				redirect('backend/administrasi');
			}
		}
	}

	function wilayah_add_save(){
		// echo var_dump($_POST);
		$strSQL = "SELECT MAX(kode) as kode,kode_capil FROM tweb_wilayah WHERE tingkat='".$_POST['tingkat']."' AND kode LIKE '".$_POST['kode']."%'";
		$query = $this->db->query($strSQL);
		if($query){
			$tingkatan = _tingkat_wilayah();
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				// echo var_dump($rs);
				$kode_baru = $rs['kode']+1;
				$kode_capil_baru = ($rs['kode_capil'] > 0)  ? $rs['kode_capil']+1 : 0;
			}else{
				switch ($_POST['tingkat']) {
					case 5:
						# code...
						$kode_baru = $_POST['kode']."01";
						break;
					case 6:
						# code...
						$kode_baru = $_POST['kode']."001";
						break;
					case 7:
						# code...
						$kode_baru = $_POST['kode']."001";
						break;
					
					default:
						# code...
						break;
				}
			}
			$strSQL = "INSERT INTO tweb_wilayah(`kode`,`nama`,`tingkat`,`kode_capil`) 
				VALUES('".$kode_baru."','".fixSQL($_POST['nama'])."','".$_POST['tingkat']."','".$kode_capil_baru."')";
			if($this->db->query($strSQL)){
				$_SESSION['msg'] = "Berhasil Menambahkan Data Wilayah Administrasi Baru: <strong>".$tingkatan[$_POST['tingkat']]." ".$_POST['nama']."</strong>";
				redirect('backend/administrasi');
			}
		}

	}

	function data_rts($varKode = 0){
		$data['pageTitle'] = "Data Rumah Tangga Sasaran";
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$varKode = ($varKode == 0) ? $data['user']['wilayah']:$varKode;
		if($data['user']['tingkat'] >= 1){
			if(strlen($varKode) > strlen($data['user']['wilayah'])){
				if((strpos($varKode,$data['user']['wilayah'])) !== false){
					// echo "MASIH WILAYAH GUA?" .$data['user']['wilayah'] ." x ".$varKode;
				}else{
					// echo "MASA BUKAN MASIH WILAYAH GUA?" .$data['user']['wilayah'] ." x ".$varKode;
					$varKode =	$data['user']['wilayah'];
				}	
			}elseif($data['user']['wilayah'] == $varKode){
				$varKode =	$data['user']['wilayah'];
			}else{
				$varKode =	$data['user']['wilayah'];
			}
		}
		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$data["data"] = $this->administrasi_model->data_rts($varKode);
		$this->load->view('administrasi/data_rts',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function data_kk($varKode = 0){
		$data['pageTitle'] = "Data Kartu Keluarga";
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$varKode = ($varKode == 0) ? $data['user']['wilayah']:$varKode;
		if($data['user']['tingkat'] >= 1){
			if(strlen($varKode) > strlen($data['user']['wilayah'])){
				if((strpos($varKode,$data['user']['wilayah'])) !== false){
					// echo "MASIH WILAYAH GUA?" .$data['user']['wilayah'] ." x ".$varKode;
				}else{
					// echo "MASA BUKAN MASIH WILAYAH GUA?" .$data['user']['wilayah'] ." x ".$varKode;
					$varKode =	$data['user']['wilayah'];
				}	
			}elseif($data['user']['wilayah'] == $varKode){
				$varKode =	$data['user']['wilayah'];
			}else{
				$varKode =	$data['user']['wilayah'];
			}
		}
		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$data["data"] = $this->administrasi_model->data_keluarga($varKode);
		$this->load->view('administrasi/data_art',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function data_art($varKode = 0){
		$data['pageTitle'] = "Data Penduduk";
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$varKode = ($varKode == 0) ? $data['user']['wilayah']:$varKode;
		if($data['user']['tingkat'] >= 1){
			if(strlen($varKode) > strlen($data['user']['wilayah'])){
				if((strpos($varKode,$data['user']['wilayah'])) !== false){
					// echo "MASIH WILAYAH GUA?" .$data['user']['wilayah'] ." x ".$varKode;
				}else{
					// echo "MASA BUKAN MASIH WILAYAH GUA?" .$data['user']['wilayah'] ." x ".$varKode;
					$varKode =	$data['user']['wilayah'];
				}	
			}elseif($data['user']['wilayah'] == $varKode){
				$varKode =	$data['user']['wilayah'];
			}else{
				$varKode =	$data['user']['wilayah'];
			}
		}
		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$data["data"] = $this->administrasi_model->data_art($varKode);
		$this->load->view('administrasi/data_art',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function pindah_alamat_form($varKode){
		$var_str = @$_REQUEST['id'];
		if($var_str){
			$var = explode("_",$var_str);
			$data['user'] = $this->session->userdata();
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
			$data['varKode'] = $varKode;
			$data['rs'] = $this->penduduk_model->rtm_load($var[1]);
			$data['tujuan'] = $this->wilayah_model->list_alamat_detail($data['user']['wilayah']);
			$this->load->view('administrasi/form_pindah_alamat',$data);
		}

	}

	function pindah_alamat_do(){
		// echo var_dump($_REQUEST);
		$tujuan = $_POST['tujuan'];
		$rts_idbdt = $_POST['idbdt'];
		$varKode = $_POST['varKode'];
		// Pindahkan data RTS
		$strMsg = "";
		$err = 0;
		$strSQL = "UPDATE tweb_rumahtangga SET kode_wilayah='".fixSQL($tujuan)."' WHERE idbdt='".fixSQL($rts_idbdt)."'";
		$query = $this->db->query($strSQL);
		if($query){
			$numrows = $this->db->affected_rows();
			$strMsg .= "<br />Rumah tangga <strong>".$rts_idbdt." (".$numrows.")</strong> berhasil dipindahkan";
		}else{
			$err++;
		}
		// echo $strSQL;
		// Pindahkan data KK
		$strSQL = "UPDATE tweb_keluarga SET kode_wilayah='".fixSQL($tujuan)."' WHERE idbdt='".fixSQL($rts_idbdt)."'";
		$query = $this->db->query($strSQL);
		if($query){
			$numrows = $this->db->affected_rows();
			$strMsg .= "<br />Kartu Keluarga dgn <strong>".$rts_idbdt." (".$numrows.")</strong> berhasil dipindahkan";
		}else{
			$err++;
		}
		// echo $strSQL;
		// Pindahkan data ART
		$strSQL = "UPDATE tweb_penduduk SET kode_wilayah='".fixSQL($tujuan)."' WHERE idbdt='".fixSQL($rts_idbdt)."'";
		$query = $this->db->query($strSQL);
		if($query){
			$numrows = $this->db->affected_rows();
			$strMsg .= "<br />Kartu Keluarga dgn <strong>".$rts_idbdt." (".$numrows.")</strong> berhasil dipindahkan";
		}else{
			$err++;
		}
		// $this->db->query($strSQL);
		// echo $strSQL;
		if($err == 0){
			$_SESSION['msg'] = $strMsg;
			redirect('backend/administrasi/data_rts/'.$varKode);
		}else{
			$_SESSION['msg'] = "Ada Kesalahan saat memindahkan data alamat Rumah Tangga ".$rts_idbdt;
			redirect('backend/administrasi/data_rts/'.$varKode);
		}

	}
}
