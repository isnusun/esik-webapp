<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbdt extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->siteman_login->cek_login();

		$this->load->model('siteman_model');
		$this->load->model('bdt_model');
		$this->load->model('bpnt_model');
		$this->load->model('wilayah_model');
	}

	function index(){
		// echo var_dump($)

		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['pageTitle'] = $data['app']['app_title'];
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/backend/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}
		$varKode = (@$_REQUEST['kode']) ? fixSQL(@$_REQUEST['kode']):0;

		$kode_wilayah = $data['app']['kode_wilayah'];
		if($data['user']['tingkat'] >= 3){
			$kode_wilayah = ($data['user']['wilayah']==NULL) ? $data["app"]['kode_wilayah']:$data['user']['wilayah'];
		}
		if(strlen($varKode < $kode_wilayah)){
			$varKode = $kode_wilayah;
		}

		$data['tingkat_wilayah'] = _tingkat_wilayah();
		$data['varKode'] = $varKode;
		$data['sub_wilayah'] = $this->wilayah_model->subwilayah($varKode);

		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);

		$data['desil'] = $this->bdt_model->desil();
		$data['data_rts'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'rts');
		$data['data_art'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'art');

		$this->load->view('pbdt/index',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function rts_desil($varDesil){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$desil = $this->bdt_model->desil();

		$data['periodes'] = $this->bdt_model->periodes(0);

		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}
		$periode = $data['periodes']['periode'][$data['periode']]['nama'];
		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);

		if($varDesil != '0'){
			$data['desil'] = $desil;
			$skor = $desil[$varDesil]['batas_bawah']." - ".$desil[$varDesil]['batas_atas'];
			if($desil[$varDesil]['batas_bawah'] > $desil[$varDesil]['batas_atas']){
				$skor = "&gt;" .$desil[$varDesil]['batas_bawah'];
			}
	
			$data['pageTitle'] = "Data Rumah Tangga Sasaran Periode ".$periode." ".$desil[$varDesil]['nama'] ." (SKOR ".$skor.") di ".$data['wilayah']['nama'];
		}else{
			$data['pageTitle'] = "Data Rumah Tangga Sasaran Periode ".$periode ." ";
		}
		$data['data'] = $this->bdt_model->data_rts_by_desil_by_wilayah($data['periode'],$varDesil,$varKode);

		$this->load->view('pbdt/desil_rts',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function art_desil($varDesil){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$desil = $this->bdt_model->desil();
		$data['desil'] = $desil;
		$skor = $desil[$varDesil]['batas_bawah']." - ".$desil[$varDesil]['batas_atas'];
		if($desil[$varDesil]['batas_bawah'] > $desil[$varDesil]['batas_atas']){
			$skor = "&gt;" .$desil[$varDesil]['batas_bawah'];
		}

		$data['periodes'] = $this->bdt_model->periodes(0);

		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}
		$periode = $data['periodes']['periode'][$data['periode']]['nama'];
		$data['pageTitle'] = "Data Rumah Tangga Sasaran Periode ".$periode." ".$desil[$varDesil]['nama'] ." (SKOR ".$skor.") di ".$data['wilayah']['nama'];
		$data['varKode'] = $varKode;
		$data['data'] = $this->bdt_model->data_art_by_desil_by_wilayah($data['periode'],$varDesil,$varKode);

		$this->load->view('pbdt/desil_art',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function indikator_rts(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		$data['pageTitle'] = "Data PBDT Berbasis Indikator Rumah Tangga";
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;

		// Terkait Data
		$data['bdt_indikator'] = $this->bdt_model->bdt_indikator('rts');
		$i=0;
		$data['num_responden'] = false;
		foreach ($data['periodes']['periode'] as $key => $value) {
			# code...
			if($i < 3){
				foreach ($data['bdt_indikator'] as $indi => $rs) {
					if($rs['jenis']=='pilihan'){
						$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_rts_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
					}
				}
			}
			$i++;
		}

		$this->load->view('pbdt/indikator_rts',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function indikator_art(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['HTTP_HOST']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		$data['pageTitle'] = "Data PBDT Berbasis Indikator Individu Anggota Rumah Tangga";
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;
		$data['varKode'] = $varKode;

		// Terkait Data
		$data['bdt_indikator'] = $this->bdt_model->bdt_indikator('art');
		// echo var_dump($data['bdt_indikator']);
		$i=0;
		$data['num_responden'] = false;
		foreach ($data['periodes']['periode'] as $key => $value) {
			# code...
			if($i < 3){
				foreach ($data['bdt_indikator'] as $indi => $rs) {
					if($rs['jenis']=='pilihan'){
						$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_art_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
					}
				}
			}
			$i++;
		}

		$this->load->view('pbdt/indikator_art',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function indikator_detail($varRts='rts',$varIndikator=''){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;
		$data['varKode'] = $varKode;
		$data['responden'] = $varRts;

		$data['pageTitle'] = "Data PBDT Berbasis Indikator Individu Anggota Rumah Tangga";
		// Terkait Data
		$data['bdt_indikator'] = $this->bdt_model->bdt_indikator($varRts);
		// echo var_dump($data['bdt_indikator']);
		$i=0;
		$data['indikator'] = $data['bdt_indikator'][$varIndikator];
		$data['num_responden'] = false;
		foreach ($data['periodes']['periode'] as $key => $value) {
			# code...
			if($i < 3){
				foreach ($data['bdt_indikator'] as $indi => $rs) {
					if($rs['jenis']=='pilihan'){
						if($varRts=='rts'){
							$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_rts_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
						}else{
							$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_art_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
						}
					}
				}
			}
			$i++;
		}

		$this->load->view('pbdt/indikator_detail',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}
	
	function rts_detail($varBdtId=''){
		if($varBdtId){
			$data['user'] = $this->session->userdata();
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
			$data['periodes'] = $this->bdt_model->periodes(0);
			if(@$_REQUEST['periode']){
				$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
				$cookie = array(
					'name'   => 'pbdt_periode',
					'value'  => $_REQUEST['periode'],
					'expire' => time()+86500,
					'domain' => $_SERVER['SERVER_NAME'],
					'path'   => '/',
					'prefix' => 'siteman_',
					);
				set_cookie($cookie);
				$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
			}else{
				$periode_by_cookie = get_cookie('siteman_pbdt_periode');
				$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
			}
			$varKode = (@$_REQUEST['kode']) ? fixSQL(@$_REQUEST['kode']):0;
	
			$kode_wilayah = $data['app']['kode_wilayah'];
			if($data['user']['tingkat'] >= 3){
				$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];
			}
			if(strlen($varKode < $kode_wilayah)){
				$varKode = $kode_wilayah;
			}
	
			$data['tingkat_wilayah'] = _tingkat_wilayah();
			$data['varKode'] = $varKode;
			$data['sub_wilayah'] = $this->wilayah_model->subwilayah($varKode);
			$data['desil'] = $this->bdt_model->desil();
			// $data['data_rts'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'rts');
			// $data['data_art'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'art');
			$data['data_rts'] = $this->bdt_model->get_rts($varBdtId,$varKode);
			$data['data_bpnt'] = $this->bpnt_model->data_by_rts($varBdtId);
			$data['indikator'] = $this->bdt_model->bdt_indikator('rts');
			$data['pageTitle'] = "Data RTS ".$data['data_rts']['nama_kepala_rumah_tangga'];
			$data['alamat_bc'] = $this->wilayah_model->alamat_bc($data['data_rts']['kode_wilayah']);

			$this->load->view('pbdt/detail_rts',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
		}else{
			redirect('pbdt');
		}
	}

	function art_detail($varBdtId=''){
		if($varBdtId){
			$data['user'] = $this->session->userdata();
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
			$data['periodes'] = $this->bdt_model->periodes(0);
			if(@$_REQUEST['periode']){
				$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
				$cookie = array(
					'name'   => 'pbdt_periode',
					'value'  => $_REQUEST['periode'],
					'expire' => time()+86500,
					'domain' => $_SERVER['SERVER_NAME'],
					'path'   => '/',
					'prefix' => 'siteman_',
					);
				set_cookie($cookie);
				$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
			}else{
				$periode_by_cookie = get_cookie('siteman_pbdt_periode');
				$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
			}
			$varKode = (@$_REQUEST['kode']) ? fixSQL(@$_REQUEST['kode']):0;
	
			$kode_wilayah = $data['app']['kode_wilayah'];
			if($data['user']['tingkat'] >= 3){
				$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];
			}
			if(strlen($varKode < $kode_wilayah)){
				$varKode = $kode_wilayah;
			}
	
			$data['tingkat_wilayah'] = _tingkat_wilayah();
			$data['varKode'] = $varKode;
			$data['sub_wilayah'] = $this->wilayah_model->subwilayah($varKode);
			$data['desil'] = $this->bdt_model->desil();
			// $data['data_rts'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'rts');
			// $data['data_art'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'art');
			$data['data_art'] = $this->bdt_model->get_art($varBdtId,$varKode);
			$data['indikator'] = $this->bdt_model->bdt_indikator('art');
			$data['pageTitle'] = $data['data_art']['nama'];
			$data['alamat_bc'] = $this->wilayah_model->alamat_bc($data['data_art']['kode_wilayah']);

			$this->load->view('pbdt/detail_art',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
		}else{
			redirect('pbdt');
		}
	}

	function rts_mana(){
		// echo var_dump($_REQUEST);array(4) { ["periode_id"]=> string(1) "1" ["indikator"]=> string(12) "sta_bangunan" ["opsi"]=> string(1) "2" ["kode"]=> string(4) "3312" }
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		if(@$_REQUEST['indikator']){
			$indikator = $_REQUEST['indikator'];
			$opsi = $_REQUEST['opsi'];

			$data['bdt_indikator'] = $this->bdt_model->bdt_indikator('rts');

			$data['pageTitle'] = "Data RTS Berbasis Indikator: <strong>".$data['bdt_indikator'][$_REQUEST['indikator']]['label']." = ".$data['bdt_indikator'][$_REQUEST['indikator']]['opsi'][$opsi]['label']."</strong>";
	
			// Terkait Data
			$data['data'] = $this->bdt_model->data_rts_by_value_indikator_by_area_by_periode($indikator,$_REQUEST['opsi'],$varKode,$data['periode']);
			$this->load->view('pbdt/indikator_mana_rts',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
	
		}else{
			redirect('pbdt/indikator/rts/?kode='.$varKode);
		}

	}

	function art_mana(){
		// echo var_dump($_REQUEST);array(4) { ["periode_id"]=> string(1) "1" ["indikator"]=> string(12) "sta_bangunan" ["opsi"]=> string(1) "2" ["kode"]=> string(4) "3312" }
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		if(@$_REQUEST['indikator']){

			$indikator = $_REQUEST['indikator'];
			$opsi = $_REQUEST['opsi'];

			$data['bdt_indikator'] = $this->bdt_model->bdt_indikator('art');

			$data['pageTitle'] = "Data Anggota Rumah Tangga Berbasis Indikator: <strong>".$data['bdt_indikator'][$_REQUEST['indikator']]['label']." = ".$data['bdt_indikator'][$_REQUEST['indikator']]['opsi'][$opsi]['label']."</strong>";

			$data['data'] = $this->bdt_model->data_art_by_value_indikator_by_area_by_periode($indikator,$opsi,$varKode,$data['periode']);

			$this->load->view('pbdt/indikator_mana_art',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
	
		}else{
			redirect('pbdt/indikator/rts/?kode='.$varKode);
		}

	}	

	function query_rts(){
		// echo var_dump($_REQUEST);
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/backend/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['indikator'] = $this->bdt_model->bdt_indikator('rts');

		if($_REQUEST){

			$indikator_aktif = array();
			$query_string = array();
			$query_value = array();

			if($_POST){
				$params="";
				// $query_string = $_POST;
				// sanity querystring tobe next param
				foreach($_POST as $key=>$rs){
					if(is_array($rs)){
						foreach($rs as $item){
							$params .= "&".$key."=".$item;
							$query_string[$key][]=$item;
						}
					}else{
						if(strlen(trim($rs)) == 0){
						}else{
							$params .= "&".$key."=".$rs."";
							if($key != 'periode_id'){
								$query_string[$key]=$rs;
							}
						}
					}
				}
			}else{
				if($_GET['param']){
					$params = siteman_crypt($_GET['param'],'d');
					$pre_qs = explode('&',$params);
					foreach($pre_qs as $str){
						$d = explode('=',$str);
						if(!empty($d[1])){
							$query_string[$d[0]][]=$d[1];
							if($d[0] =='periode_id'){
								$periode_id = $d[1];
							}
						}
					}
				}
			}

			$data['query_string']=siteman_crypt($params,'e');
			$page = (@$_REQUEST['p'])? $_REQUEST['p']:1;
			$periode_id = (@$_REQUEST['periode_id']) ? $_REQUEST['periode_id']:$periode_id;
			$offset = ($page - 1) * $data["app"]['limit_tampil'];
			$data['collapse'] = "collapsed-box";

			$param_pilihan = array();
			foreach($data['indikator'] as $key=>$item){
				$param_pilihan[$item['nama']] = $item;
			}
			$data['param_pilihan'] = $param_pilihan;


			foreach($query_string as $key=>$rs){
				// echo $key."\n";
				switch ($key) {
					case 'p':
					case 'periode_id':
						# code...
						break;
					default:
						# code...
						$column = str_replace('_min','',$key);
						$column = str_replace('_max','',$column);
						$indikator_aktif[] = $column;
						break;
				}
			}
			// $data['new_param'] = $new_param;
			$indikator_aktif = array_unique($indikator_aktif);
			$data['indikator_aktif'] = $indikator_aktif;
			// echo var_dump($data['indikator_aktif']);
			$periode_id = (@$_REQUEST['periode_id']) ? $_REQUEST['periode_id']:$periode_id;
			$strSQLNum = "SELECT d.lead_id FROM bdt_rts d WHERE ((d.kode_wilayah LIKE '".fixSQL($varKode)."%') AND (d.periode_id='".fixSQL($periode_id)."')";
			$strSQL = "SELECT d.idbdt,d.kode_wilayah,d.alamat,d.nama_krt,d.percentile,
				prop.nama as propinsi, kab.nama as kabupaten, kec.nama as kecamatan, desa.nama as desa 
			FROM bdt_rts d
				LEFT JOIN tweb_wilayah prop ON SUBSTRING(d.kode_wilayah,1,2)=prop.kode
				LEFT JOIN tweb_wilayah kab ON SUBSTRING(d.kode_wilayah,1,4)=kab.kode
				LEFT JOIN tweb_wilayah kec ON SUBSTRING(d.kode_wilayah,1,7)=kec.kode
				LEFT JOIN tweb_wilayah desa ON SUBSTRING(d.kode_wilayah,1,10)=desa.kode
			WHERE (
				(d.kode_wilayah LIKE '".fixSQL($varKode)."%') AND 
				(d.periode_id='".$periode_id."') ";

			$clausul = "";
			foreach($indikator_aktif as $pa=>$param){
				if($param_pilihan[$param]['jenis'] == 'pilihan'){
					$param_name = $param;
					// echo var_dump($query_string);
					$x = count($query_string[$param_name]);
					$i = 1;
					if($x > 0){
						$clausul .= " AND (";
						foreach($query_string[$param_name] as $k=>$nilai){
							$strOr = ($i < $x) ? "OR ":"";
							$col = "d.".$param;
							$clausul .= " (".$col." = '".fixSQL($nilai)."') ".$strOr."\n";
							$i++;
						}
						$clausul .= ")\n";

					}
				}elseif($param_pilihan[$param]['jenis'] == 'angka'){
					$param_name_min = $param."_min";
					$param_name_max = $param."_max";
					$col = "d.".$param;
					$val_param_min = (is_array($query_string[$param_name_min]))? $query_string[$param_name_min][0]:$query_string[$param_name_min];
					$val_param_max = (is_array($query_string[$param_name_max]))? $query_string[$param_name_max][0]:$query_string[$param_name_max];

					$clausul .= " AND (";
					if($val_param_max < $val_param_min){
						$clausul .=" $col > '".fixSQL($val_param_min)."'";
					}else{
						$clausul .= " $col BETWEEN ".$val_param_min." AND ".$val_param_max."  \n";
					}
					$clausul .= ")\n";
				}
			}
			$strSQL .= $clausul.") LIMIT ".$offset.",".$data["app"]['limit_tampil'];
			$strSQLNum .= $clausul.")";
			$query = $this->db->query($strSQLNum);
			$numrows = 0;
			$hasil = false;
			if($query){
				$numrows = $query->num_rows();
				if($numrows > 0){
					$dataset = array();
					$query = $this->db->query($strSQL);
					if($query){
						if($query->num_rows() > 0){
							$dataset = $query->result_array();
						}
					}
					$page_total = ceil($numrows/$data['app']['limit_tampil']);
					$page_next = ($page < $page_total ) ? $page+1:false;
					$page_prev = ($page > 1) ? $page-1:false;
	
					$hasil = array(
						'numrows'=>$numrows,
						'paging'=>array(
							'page'=>$page,
							'offset'=>$offset,
							'page_total'=>$page_total,
							'page_next'=>$page_next,
							'page_prev'=>$page_prev
						),
						'data'=>$dataset,
						);
				}
			}
			// echo var_dump($data['x']);
			$data['pageTitle'] = "Hasil Query Data ART";
			$data['dataset'] = $hasil;

		}else{
			$data['pageTitle'] = "Formulir Query Data RTS";
			$data['collapse'] = "";
			$data['data_rts'] = false;
		}

		$this->load->view('pbdt/query_rts',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}				
	}

	function query_art(){

		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['indikator'] = $this->bdt_model->bdt_indikator('art');

		if($_REQUEST){
			$indikator_aktif = array();
			$query_string = array();
			$query_value = array();

			if($_POST){
				$params="";
				// $query_string = $_POST;
				// sanity querystring tobe next param
				foreach($_POST as $key=>$rs){
					if(is_array($rs)){
						foreach($rs as $item){
							$params .= "&".$key."=".$item;
							$query_string[$key][]=$item;
						}
					}else{
						if(strlen(trim($rs)) == 0){
						}else{
							$params .= "&".$key."=".$rs."";
							if($key != 'periode_id'){
								$query_string[$key]=$rs;
							}
						}
					}
				}
			}else{
				if($_GET['param']){
					$params = siteman_crypt($_GET['param'],'d');
					$pre_qs = explode('&',$params);
					foreach($pre_qs as $str){
						$d = explode('=',$str);
						if(!empty($d[1])){
							$query_string[$d[0]][]=$d[1];
							if($d[0] =='periode_id'){
								$periode_id = $d[1];
							}
						}
					}
				}
			}

			$data['query_string']=siteman_crypt($params,'e');
			$page = (@$_REQUEST['p'])? $_REQUEST['p']:1;
			$periode_id = (@$_REQUEST['periode_id']) ? $_REQUEST['periode_id']:$periode_id;
			$offset = ($page - 1) * $data["app"]['limit_tampil'];
			$data['collapse'] = "collapsed-box";

			$param_pilihan = array();
			foreach($data['indikator'] as $key=>$item){
				$param_pilihan[$item['nama']] = $item;
			}
			$data['param_pilihan'] = $param_pilihan;
			// echo var_dump($param_pilihan);


			foreach($query_string as $key=>$rs){
				// echo $key."\n";
				switch ($key) {
					case 'p':
					case 'periode_id':
						# code...
						break;
					default:
						# code...
						$column = str_replace('_min','',$key);
						$column = str_replace('_max','',$column);
						$indikator_aktif[] = $column;
						break;
				}
			}
			// $data['new_param'] = $new_param;
			$indikator_aktif = array_unique($indikator_aktif);
			$data['indikator_aktif'] = $indikator_aktif;
			// echo "<h2>QUER STRING</h2>";
			// echo var_dump($query_string);

			// echo "<h2>QUER STRING</h2>";
			// echo var_dump($indikator_aktif);
			// $data['indikator_aktif'] = $indikator_aktif;
			$strSQLNum = "SELECT d.lead_id FROM bdt_idv d WHERE ((d.kode_wilayah LIKE '".fixSQL($varKode)."%') AND (d.periode_id='".fixSQL($periode_id)."')";
			$strSQL = "SELECT d.idbdt,d.idartbdt,d.kode_wilayah,d.nama,d.nik,d.umur,
				prop.nama as propinsi, kab.nama as kabupaten, kec.nama as kecamatan, desa.nama as desa,
				r.percentile as percentile,r.alamat as alamat
			FROM bdt_idv d
				LEFT JOIN bdt_rts r ON d.idbdt=r.idbdt 
				LEFT JOIN tweb_wilayah prop ON SUBSTRING(d.kode_wilayah,1,2)=prop.kode
				LEFT JOIN tweb_wilayah kab ON SUBSTRING(d.kode_wilayah,1,4)=kab.kode
				LEFT JOIN tweb_wilayah kec ON SUBSTRING(d.kode_wilayah,1,7)=kec.kode
				LEFT JOIN tweb_wilayah desa ON SUBSTRING(d.kode_wilayah,1,10)=desa.kode
			WHERE ((r.periode_id='".fixSQL($periode_id)."') AND 
				(d.kode_wilayah LIKE '".fixSQL($varKode)."%') AND 
				(d.periode_id='".fixSQL($periode_id)."')";

			$clausul = "";

			foreach($indikator_aktif as $pa=>$param){
				if($param_pilihan[$param]['jenis'] == 'pilihan'){
					$param_name = $param;
					// echo var_dump($query_string);
					$x = count($query_string[$param_name]);
					$i = 1;
					if($x > 0){
						$clausul .= " AND (";
						foreach($query_string[$param_name] as $k=>$nilai){
							$strOr = ($i < $x) ? "OR ":"";
							$col = "d.".$param;
							$clausul .= " (".$col." = '".fixSQL($nilai)."') ".$strOr."\n";
							$i++;
						}
						$clausul .= ")\n";
	
					}
				}elseif($param_pilihan[$param]['jenis'] == 'angka'){
					$param_name_min = $param."_min";
					$param_name_max = $param."_max";
					$col = "d.".$param;
					$val_param_min = (is_array($query_string[$param_name_min]))? $query_string[$param_name_min][0]:$query_string[$param_name_min];
					$val_param_max = (is_array($query_string[$param_name_max]))? $query_string[$param_name_max][0]:$query_string[$param_name_max];

					$clausul .= " AND (";
					if($val_param_max < $val_param_min){
						$clausul .=" $col > '".fixSQL($val_param_min)."'";
					}else{
						$clausul .= " $col BETWEEN ".$val_param_min." AND ".$val_param_max."  \n";
					}
					$clausul .= ")\n";
				}
			}
			$strSQL .= $clausul.") LIMIT ".$offset.",".$data["app"]['limit_tampil'];

			$strSQLNum .= $clausul.")";
			// echo $strSQLNum;
			$query = $this->db->query($strSQLNum);
			$numrows = 0;
			$hasil = false;
			if($query){
				$numrows = $query->num_rows();
				if($numrows > 0){
					$dataset = array();
					$query = $this->db->query($strSQL);
					if($query){
						if($query->num_rows() > 0){
							$dataset = $query->result_array();
						}
					}
					$page_total = ceil($numrows/$data['app']['limit_tampil']);
					$page_next = ($page < $page_total ) ? $page+1:false;
					$page_prev = ($page > 1) ? $page-1:false;
	
					$hasil = array(
						'numrows'=>$numrows,
						'paging'=>array(
							'page'=>$page,
							'offset'=>$offset,
							'page_total'=>$page_total,
							'page_next'=>$page_next,
							'page_prev'=>$page_prev
						),
						'data'=>$dataset,
						);
				}
			}


			// echo var_dump($data['x']);
			$data['pageTitle'] = "Hasil Query Data ART";
			$data['dataset'] = $hasil;
		}else{
			$data['pageTitle'] = "Formulir Query Data RTS";
			$data['collapse'] = "";
			$data['data_rts'] = false;
		}

		$this->load->view('pbdt/query_art',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}				
	}

	function usulanbaru(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['pageTitle'] = "Pendaftaran Usulan RTS Baru ".$data['app']['app_title'];
		$data['periodes'] = $this->bdt_model->periodes(0);
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($data['user']['wilayah']);
		$this->load->view('pbdt/rts_baru',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

}
