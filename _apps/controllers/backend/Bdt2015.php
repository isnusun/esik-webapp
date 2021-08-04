<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Bdt2015 extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->siteman_login->cek_login();

		$this->load->model('siteman_model');
		$this->load->model('bdt2015_model');
		$this->load->model('bdt_model');
		$this->load->model('bpnt_model');
		$this->load->model('wilayah_model');
	}

	function index(){

		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['pageTitle'] = $data['app']['app_title'];

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

		$data['desil'] = _bdt2015_desil();
		$data['data_rts'] = $this->bdt2015_model->data_desil_by_wilayah_by_periode($varKode,'rts');
		$data['data_art'] = $this->bdt2015_model->data_desil_by_wilayah_by_periode($varKode,'art');

		$this->load->view('bdt2015/index',$data);	
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
		$data['desil'] = _bdt2015_desil();

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);

		if($varDesil != '0'){
			$data['pageTitle'] = "Data Rumah Tangga Sasaran PBDT 2015 ".$data['desil'][$varDesil] ." di ".$data['wilayah']['nama'];
		}else{
			$data['pageTitle'] = "Data Rumah Tangga Sasaran PBDT 2015";
		}
		$data['data'] = $this->bdt2015_model->data_rts_by_desil_by_wilayah($varDesil,$varKode);

		$this->load->view('bdt2015/desil_rts',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function rts_detail($varBdtId=''){
		if($varBdtId){
			$data['user'] = $this->session->userdata();
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

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
			$data['data_rts'] = $this->bdt2015_model->get_rts($varBdtId,$varKode);

			$data['data_bpnt'] = $this->bpnt_model->data_by_rts($varBdtId);
			$data['indikator'] = $this->bdt2015_model->pbdt2015_indikator('rts');
			$data['pageTitle'] = "Data RTS ".$data['data_rts']['data_bdt']['Nama Kepala Rumah Tangga'];
			$data['alamat_bc'] = $this->wilayah_model->alamat_bc($data['data_rts']['kode_wilayah']);

			$this->load->view('bdt2015/detail_rts',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
		}else{
			redirect('pbdt');
		}
	}
	
	function art_desil($varDesil){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$data['desil'] = _bdt2015_desil();

		$data['pageTitle'] = "Data Anggota Rumah Tangga PBDT 2015 <strong>".$data['desil'][$varDesil]."</strong> di ".$data['wilayah']['nama'];
		$data['varKode'] = $varKode;
		$data['indikator'] = $this->bdt2015_model->pbdt2015_indikator();

		$data['data'] = $this->bdt2015_model->data_art_by_desil_by_wilayah($varDesil,$varKode);

		$this->load->view('bdt2015/desil_art',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function art_detail($varBdtId=''){
		if($varBdtId){
			$data['user'] = $this->session->userdata();
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
	
			$kode_wilayah = $data['app']['kode_wilayah'];
			if($data['user']['tingkat'] >= 3){
				$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];
			}
			$data['tingkat_wilayah'] = _tingkat_wilayah();
			$data['varKode'] = $kode_wilayah;
			$data['desil'] = _bdt2015_desil();
			// $data['data_rts'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'rts');
			// $data['data_art'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'art');
			$data['data_art'] = $this->bdt2015_model->get_art_by_id($varBdtId);
			// echo var_dump($data['data_art']);
			$data['indikator'] = $this->bdt2015_model->pbdt2015_indikator('art');
			$data['pageTitle'] = $data['data_art']['Nama'];
			$data['alamat_bc'] = $this->wilayah_model->alamat_bc($data['data_art']['kode_wilayah']);

			$this->load->view('bdt2015/detail_art',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
		}else{
			redirect('bdt2015');
		}
	}


	function indikator_rts(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		$data['pageTitle'] = "Data PBDT 2015 Berbasis Indikator Rumah Tangga";

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;

		// Terkait Data
		$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator('rts');
		// echo var_dump($data['bdt_indikator']['rts']);

		// $i=0;
		$data['num_responden'] = false;
		
		foreach ($data['bdt_indikator']['rts'] as $indi => $rs) {
			if($rs['jenis']=='pilihan'){
				$data['num_responden'][$rs['nama']] = $this->bdt2015_model->data_rts_by_indikator_by_periode_by_area($varKode,$rs['nama']);
			}
		}

		$this->load->view('bdt2015/indikator_rts',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function indikator_art(){

		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['HTTP_HOST']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		$data['pageTitle'] = "Data PBDT Berbasis Indikator Individu Anggota Rumah Tangga";

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;
		$data['varKode'] = $varKode;

		// Terkait Data
		$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator('art');

		foreach ($data['bdt_indikator']['art'] as $indi => $rs) {
			if($rs['jenis']=='pilihan'){
				$data['num_responden'][$rs['nama']] = $this->bdt2015_model->data_art_by_indikator_by_periode_by_area($varKode,$rs['nama']);
			}
		}

		$this->load->view('bdt2015/indikator_art',$data);	
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

		$data['pageTitle'] = "Data PBDT Berbasis Indikator";
		// Terkait Data
		$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator($varRts);
		$data['bdt_indikator'] = $data['bdt_indikator'][$varRts];

		$data['indikator'] = $data['bdt_indikator'][$varIndikator];
		// echo var_dump($data['indikator']);
		$data['num_responden'] = false;
		if($data['indikator']['jenis'] == 'pilihan'){
			$data['num_responden'] = $this->bdt2015_model->data_responden_by_opsi_indikator_by_area($varRts,$varKode,$data['indikator']['nama']);
		}
		$this->load->view('bdt2015/indikator_detail',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function rts_mana(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		if(@$_REQUEST['indikator']){
			$indikator = $_REQUEST['indikator'];
			$opsi = $_REQUEST['opsi'];

			$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator('rts');
			$data['bdt_indikator'] = $data['bdt_indikator']['rts'];
	
			$data['indikator'] = $data['bdt_indikator'][$indikator];
			$data['opsi_id'] = $opsi;
			$data['pageTitle'] = "Data PBDT 2015 Berbasis Indikator: <strong>".$data['bdt_indikator'][$_REQUEST['indikator']]['nama']." = ".$data['bdt_indikator'][$_REQUEST['indikator']]['opsi'][$opsi]."</strong>";
	
			$data['data'] = $this->bdt2015_model->data_rts_mana_by_value_indikator_by_area($indikator,$_REQUEST['opsi'],$varKode);

			$this->load->view('bdt2015/indikator_mana_rts',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
	
		}else{
			redirect('pbdt/indikator/rts/?kode='.$varKode);
		}
	}
	function art_mana(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		if(@$_REQUEST['indikator']){
			$indikator = $_REQUEST['indikator'];
			$opsi = $_REQUEST['opsi'];

			$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator('art');
			$data['bdt_indikator'] = $data['bdt_indikator']['art'];
	
			$data['indikator'] = $data['bdt_indikator'][$indikator];
			$data['opsi_id'] = $opsi;
	
			$data['pageTitle'] = "Data PBDT 2015 Berbasis Indikator: <strong>".$data['bdt_indikator'][$_REQUEST['indikator']]['nama']." = ".$data['bdt_indikator'][$_REQUEST['indikator']]['opsi'][$opsi]."</strong>";
	
			$data['data'] = $this->bdt2015_model->data_art_mana_by_value_indikator_by_area($indikator,$_REQUEST['opsi'],$varKode);

			$this->load->view('bdt2015/indikator_mana_art',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
	
		}else{
			redirect('pbdt/indikator/rts/?kode='.$varKode);
		}
	}

	function query_rts(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator('rts');
		$data['indikator'] = $data['bdt_indikator']['rts'];

		if($_REQUEST){

			$indikator_aktif = array();
			$query_string = array();
			$query_value = array();

			if($_POST){
				$params="";
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
			// echo var_dump($params);

			$data['query_string']=siteman_crypt($params,'e');
			$page = (@$_REQUEST['p'])? $_REQUEST['p']:1;
			$offset = ($page - 1) * $data["app"]['limit_tampil'];
			$data['collapse'] = "collapsed-box";

			$param_pilihan = array();
			foreach($data['indikator'] as $key=>$item){
				// if($item['jenis']=='pilihan'){
					$param_pilihan[str_replace(' ','_',$item['nama'])] = $item;
				// }
			}
			$data['param_pilihan'] = $param_pilihan;
			// echo var_dump($data['param_pilihan']);

			foreach($query_string as $key=>$rs){
				// echo $key."\n";
				switch ($key) {
					case 'p':
					case 'periode_id':
						# code...
						break;
					default:
						# code...
						$column = str_replace('__min','',$key);
						$column = str_replace('__max','',$column);
						$indikator_aktif[] = $column;
						break;
				}
			}
			// $data['new_param'] = $new_param;
			$indikator_aktif = array_unique($indikator_aktif);
			$data['indikator_aktif'] = $indikator_aktif;

			$strSQLNum = "SELECT d.id FROM `pbdt2015_rts` d WHERE ((d.kode_wilayah LIKE '".fixSQL($varKode)."%')";

			$strSQL = "SELECT d.`id`,d.`kode_wilayah`,d.`Nama Kepala Rumah Tangga`,d.`Status Kesejahteraan`, d.`Nomor Urut Rumah Tangga`,
				`Provinsi`, `Kabupaten/Kota`, `Kecamatan`, `Desa/Kelurahan`, `Alamat`
			FROM `pbdt2015_rts` d
			WHERE (
				(d.kode_wilayah LIKE '".fixSQL($varKode)."%') ";

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
							$col = "d.`".str_replace('_',' ',$param)."`";
							$clausul .= " (".$col." = '".fixSQL($nilai)."') ".$strOr."\n";
							$i++;
						}
						$clausul .= ")\n";

					}
				}elseif($param_pilihan[$param]['jenis'] == 'angka'){
					$param_name_min = $param."__min";
					$param_name_max = $param."__max";
					$col = "d.`".str_replace('_',' ',$param)."`";
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
			// echo $strSQL;
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
			$data['pageTitle'] = "Hasil Query Data RTS";
			$data['dataset'] = $hasil;

		}else{
			$data['pageTitle'] = "Formulir Query Data RTS";
			$data['collapse'] = "";
			$data['data_rts'] = false;
		}

		$this->load->view('bdt2015/query_rts',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}				
	}

	function query_art(){

		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		$kode_wilayah = ($data['user']['wilayah']==NULL) ? KODE_BASE:$data['user']['wilayah'];		
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$kode_wilayah;

		$data['varKode'] = $varKode;
		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);
		$data['bdt_indikator'] = $this->bdt2015_model->pbdt2015_indikator('art');
		$data['indikator'] = $data['bdt_indikator']['art'];

		if($_REQUEST){
			// echo var_dump($_REQUEST);

			$indikator_aktif = array();
			$query_string = array();
			// $query_value = array();

			if($_POST){
				$params="";
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
			// echo var_dump($params);

			$data['query_string']=siteman_crypt($params,'e');
			$page = (@$_REQUEST['p'])? $_REQUEST['p']:1;
			$offset = ($page - 1) * $data["app"]['limit_tampil'];
			$data['collapse'] = "collapsed-box";

			$param_pilihan = array();
			foreach($data['indikator'] as $key=>$item){
				// if($item['jenis']=='pilihan'){
					$param_pilihan[str_replace(' ','_',$item['nama'])] = $item;
				// }
			}
			$data['param_pilihan'] = $param_pilihan;
			// echo var_dump($data['param_pilihan']);

			foreach($query_string as $key=>$rs){
				// echo $key."\n";
				switch ($key) {
					case 'p':
					case 'periode_id':
						# code...
						break;
					default:
						# code...
						$column = str_replace('__min','',$key);
						$column = str_replace('__max','',$column);
						$indikator_aktif[] = $column;
						break;
				}
			}
			// $data['new_param'] = $new_param;
			$indikator_aktif = array_unique($indikator_aktif);
			$data['indikator_aktif'] = $indikator_aktif;

			$strSQLNum = "SELECT d.id FROM `pbdt2015_idv` d WHERE ((d.kode_wilayah LIKE '".fixSQL($varKode)."%')";

			$strSQL = "SELECT d.`id`,d.`kode_wilayah`,d.`Nama`,d.`NIK`,d.`Status Kesejahteraan`, d.`Nomor Urut Rumah Tangga`,
				`Provinsi`, `Kabupaten/Kota`, `Kecamatan`, `Desa/Kelurahan`, `Alamat`
			FROM `pbdt2015_idv` d
			WHERE (
				(d.kode_wilayah LIKE '".fixSQL($varKode)."%') ";

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
							$col = "d.`".str_replace('_',' ',$param)."`";
							$clausul .= " (".$col." = '".fixSQL($nilai)."') ".$strOr."\n";
							$i++;
						}
						$clausul .= ")\n";

					}
				}elseif($param_pilihan[$param]['jenis'] == 'angka'){
					$param_name_min = $param."__min";
					$param_name_max = $param."__max";
					$col = "d.`".str_replace('_',' ',$param)."`";
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
			// echo $strSQL;
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
			$data['pageTitle'] = "Formulir Query Data ART";
			$data['collapse'] = "";
			$data['data_rts'] = false;
		}				
		$this->load->view('bdt2015/query_art',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}				
	}

}
