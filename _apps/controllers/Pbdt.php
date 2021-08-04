<?php
/*
 * Pbdt.php
 * 
 * Copyright 2016 Isnu Suntoro <isnusun@isnusun-X450LCP>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbdt extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('pbdt_model');
		$this->load->model('wilayah_model');
		$this->load->helper('cookie');

	}
	   
  public function index($varKode=0,$varPage=1){
		$data['user'] = $this->session->userdata;
		if($varKode == 0){
			$varKode = KODE_BASE;
			if($data['user']['tingkat'] >= 3){
				$varKode = $data['user']['wilayah'];
			}
		}
		$data["pageTitle"] = "Basis Data Terpadu ".APP_TITLE;
		$data['periodes'] = $this->pbdt_model->pbdt_periode();
		if(@$_REQUEST['periode']){
			// $this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
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
			// echo var_dump($_COOKIE);
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}
		$data['msg'] = "";
		$data['kode'] = "";
		$data['statistik'] = false;
		$data["boxTitle"] = "Indek Basis Data Terpadu <strong>".$data['periodes']['periode'][$data['periode']]['nama']."</strong>";
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["varKode"] = $varKode;
		$data["kode"] = $varKode;
		$data["wilayah"] = $this->wilayah_model->wilayah($varKode);
		$data["subwilayah"] = $this->wilayah_model->subwilayah($varKode);

		$data['summary'] = $this->pbdt_model->rangkuman_by_wilayah($varKode,$data['periode']);


		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["desil"] = $this->pbdt_model->pbdt_klasifikasi();

		$this->load->view('pbdt/index',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function indikator($varApa='rtm',$vKode=0){
		$data['user'] = $this->session->userdata;
		
		$data['periodes'] = $this->pbdt_model->pbdt_periode();
		$data['periode2show'] = $this->pbdt_model->pbdt_last3periode();
		
		$varKode = (@$_REQUEST['w']) ? $_REQUEST['w']:KODE_BASE;
		$periode = (@$_REQUEST['p']) ? $_REQUEST['p']:$data['periodes']['periode_aktif'];

		$vKode = ($vKode==0) ? KODE_BASE:$vKode;
		$data["alamat"] = $this->wilayah_model->alamat_bc($vKode);
		$data["sub_wilayah"] = $this->wilayah_model->subwilayah($vKode);
		
		$data['kode'] = $varKode;
		$data['statistik'] = false;
		$data['idv'] = false;
		switch ($varApa)
		{
			case "art":
				/*
				 * Indikator ART
				 * */
				$data['idv'] = true; 
				$data['pageTitle'] = "Data Berbasis Indikator Anggota Rumah Tangga / Individu";
				$data['boxTitle'] = "Data Berbasis Indikator Penduduk";

				$data['param'] = $this->pbdt_model->pbdt_param_idv();
				$data['opsi'] = $this->pbdt_model->pbdt_opsi_idv();
				foreach($data['param'] as $pid=>$param){
					$data['nilai'][$param['id']] = $this->pbdt_model->data_idv_by_indikator_flat($param['id'],$vKode);
				}

				break;

			case "kks":
			default:
				/*
				 * Indikator RTM
				 * */
				$data['pageTitle'] = "Data Berbasis Indikator Rumah Tangga Sasaran";
				$data['boxTitle'] = "Data Berbasis Indikator Rumah Tangga Sasaran";
				$data['param'] = $this->pbdt_model->pbdt_param();
				$data['opsi'] = $this->pbdt_model->pbdt_opsi();
				
				foreach($data['param'] as $pid=>$param){
					if($param['jenis']==1){
						$data['nilai'][$param['id']] = $this->pbdt_model->data_by_indikator_flat($param['id'],$vKode);
						// $data['nilai'][$param['id']] = array();
					}
				}
		}
		
		$this->load->view('pbdt/data_by_indikator',$data);

		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
				
	}

	function show_art($varKode=0,$varKelas=0,$varPeriode=0){
		$data['user'] = $this->session->userdata;

		$data["pageTitle"] = "Data Kesejahteraan PBDT PLus";
		$data['statistik'] = false;
		$data['kode'] = $varKode;
		$data['box_collapse'] = array("","");
		if($varKode > 0){
			$data['box_collapse'] = array(0=>"collapsed-box",1=>"style=\"display:none;\"");
		}
		
		$varKode = ($varKode == "") ? KODE_BASE:$varKode;
		if(KODE_BASE == 3372){
			if(strlen($varKode)==10){
				$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
				$varKode .= "00";
			}else{
				$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
			}
		}
		$tingkat_wilayah = _tingkat_wilayah();
		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);

		$data["varKode"] = $varKode;
		$data['klasifikasi'] = $this->gakin_model->gk_klasifikasi();
		$data["boxTitle"] = "Data Individu dalam ".$data['klasifikasi'][$varKelas]['nama']." di ".$tingkat_wilayah[$data['wilayah']['tingkat']]." ".$data['wilayah']['nama'];

		$data['items'] = $this->gakin_model->data_idv_by_kelas_wilayah(_getIntOfString($varKelas),$varKode);

		$this->load->view('siteman/gakin_show_art',$data);

		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
		
	}

	function show_rts($varKode=0,$varKelas=0,$varPeriode=1){
		$data['user'] = $this->session->userdata;

		$data["pageTitle"] = "Daftar RTS Data PBDT";
		$data['box_collapse'] = array("","");
		if($varKode > 0){
			$data['box_collapse'] = array(0=>"collapsed-box",1=>"style=\"display:none;\"");
		}
		$data['statistik'] = false;
		$data['kode'] = $varKode;
		if($data['user']['tingkat'] < 3){
			$varKode = ($varKode == 0) ? KODE_BASE:$varKode;
		}else{
			$varKode = ($varKode == 0) ? $data['user']['wilayah']:$varKode;
		}
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["varKode"] = $varKode;
		$tingkat_wilayah = _tingkat_wilayah();
		$data['wilayah'] = $this->wilayah_model->wilayah($varKode);
		$data['klasifikasi'] = $this->pbdt_model->pbdt_klasifikasi();
		$data['periodes'] = $this->pbdt_model->pbdt_periode();
		$periode_by_cookie = get_cookie('siteman_pbdt_periode');
		$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
	
		$data["boxTitle"] = "Data Keluarga dalam ".$data['klasifikasi'][$varKelas]['nama']." Periode <strong>".$data['periodes']['periode'][$data['periode']]['nama']."</strong> di ".$tingkat_wilayah[$data['wilayah']['tingkat']]." ".$data['wilayah']['nama'];
		
		$data['items'] = $this->pbdt_model->data_rts_by_desil_wilayah_periode($varKelas,$varKode,$data['periode']);
		
		$this->load->view('pbdt/list_rts_by_desil',$data);

		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function show_rts_nilai($varRTM=0){
		$data['user'] = $this->session->userdata;
		$data['klasifikasi'] = $this->pbdt_model->pbdt_klasifikasi();
		$data['periodes'] = $this->pbdt_model->pbdt_periode();
		$periode_by_cookie = get_cookie('siteman_pbdt_periode');
		$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		
		$data['rts'] = $this->pbdt_model->get_rts($varRID);
		$data["pageTitle"] = "Daftar RTS Data PBDT";
		$data["boxTitle"] = "Data Keluarga dalam ".$data['klasifikasi'][$varKelas]['nama']." Periode <strong>".$data['periodes']['periode'][$data['periode']]['nama']."</strong> di ".$tingkat_wilayah[$data['wilayah']['tingkat']]." ".$data['wilayah']['nama'];

	}

	function indikator_detail($varApa='rts',$varID=0,$varKode=0){
		$data['user'] = $this->session->userdata;

		$varKode = ($varKode == 0)? KODE_BASE : $varKode;
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["varKode"] = $varKode;
		$data["kode"] = $varKode;
		$data["statistik"] = false;
		$data["wilayah"] = $this->wilayah_model->wilayah($varKode);
		$data["subwilayah"] = $this->wilayah_model->subwilayah($varKode);
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		
		switch ($varApa) {
			case 'art':
					# code...
					$data['param'] = $this->pbdt_model->pbdt_param_idv($varID);
					$data['pageTitle'] = "Data ART Per Indikator PBDT di ".$data['wilayah']['nama'];
					$data['boxTitle'] = "Indikator :<strong>".$data['param']['nama']."</strong>";
				break;
			
			default:
				# code...
				$data['param'] = $this->pbdt_model->pbdt_param($varID);
				$data['pageTitle'] = "Data RTS Per Indikator PBDT di ".$data['wilayah']['nama'];
				$data['boxTitle'] = "Indikator :<strong>".$data['param']['nama']."</strong>";
			break;
		}

		if($varID == 0){
			redirect('pbdt/indikator/'.$varApa);
		}else{
			$this->load->view('pbdt/data_detail_per_indikator',$data);

			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
				
		}
	}

	function customquery($varKode=0){
		$data['user'] = $this->session->userdata;
		$varKode = ($varKode == 0) ? KODE_BASE:$varKode;

		$data["sub_wilayah"] = $this->wilayah_model->subwilayah($varKode);
		$data["wilayah"] = $this->wilayah_model->get_wilayah($varKode);
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["tingkatan"] = _tingkat_wilayah();
		$data["varKode"] = $varKode;

		$data["pageTitle"] = "Pemanggilan Data Rumah Tangga Tersesuaikan";
		$data["boxTitle"] = "Pemanggilan Data Rumah Tangga Tersesuaikan";

		$data['periodes'] = $this->pbdt_model->pbdt_periode();
		if(@$_REQUEST['periode']){
			// $this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
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
			// echo var_dump($_COOKIE);
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}
		// Indikator RTM
		$data['param'] = $this->pbdt_model->pbdt_param();
		$data['opsi'] = $this->pbdt_model->pbdt_opsi();

		if($_POST){
			$data['collapse'] = "collapsed-box";
			$param_pilihan = array();
			foreach($data['param'] as $key=>$item){
				$param_pilihan[$item['id']] = $item;
			}
			$data['param_pilihan'] = $param_pilihan;

			$indikator_aktif = array();
			$item =array();
			foreach($_POST as $key=>$rs){
				if($rs){
					$item = explode("_",$key);
					$indikator_aktif[] = $item[1];
				}
			}
			// $data['new_param'] = $new_param;
			$data['indikator_aktif'] = array_unique($indikator_aktif);

			$strSQL = "SELECT d.Provinsi as `Propinsi`,d.Kabupaten as `Kabupaten`,d.Kecamatan as `Kecamatan`,d.Desa as `Desa/Kel.`,
			d.Dusun as `Dusun`,d.RW  as `RW`,d.RT as `RT`,d.RID_RumahTangga as `RID_RumahTangga`,d.Nama_KRT as `Kepala Rumah Tangga`,
			d.status_kesejahteraan as `Desil`
			FROM pbdt_rt d
			WHERE ((id > 0) AND (periode_id='".$data['periode']."')
			";
			foreach($data['indikator_aktif'] as $param){
				if($param_pilihan[$param]['jenis'] == 1){
					$param_name = "param_".$param;
					$strSQL .= " AND (";
					$x = count($_POST[$param_name]);
					$i = 1;
					foreach($_POST[$param_name] as $nilai){
						$strOr = ($i < $x) ? "OR ":"";
						$strSQL .= " (col_".$param."= '".$nilai."') ".$strOr."\n";
						$i++;
					}
					$strSQL .= ")\n";
				}elseif($param['jenis'] == 2){
					$param_name_min = "param_".$param."_min";
					$param_name_max = "param_".$param."_max";
					$strSQL .= " AND (";
					$strSQL .= " ((col_".$param." >= '".$_POST[$param_name_min]."') AND (col_".$param." <= '".$_POST[$param_name_max]."') ) \n";
					$strSQL .= ")\n";
				}
			}
			$strSQL .= ") ORDER BY status_kesejahteraan";
			// echo $strSQL;
			$data['recordset'] = $this->pbdt_model->do_query($strSQL);
		}else{
			$data['collapse'] = "";
			$data['recordset'] = false;
		}

		$this->load->view('pbdt/pbdt_query_rt',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}			
	}


	function customquery_idv($varKode=0){
		$data['user'] = $this->session->userdata;
		$varKode = ($varKode == 0) ? KODE_BASE:$varKode;

		$data["sub_wilayah"] = $this->wilayah_model->subwilayah($varKode);
		$data["wilayah"] = $this->wilayah_model->get_wilayah($varKode);
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["tingkatan"] = _tingkat_wilayah();
		$data["varKode"] = $varKode;

		$data["pageTitle"] = "Pemanggilan Data Rumah Tangga Tersesuaikan";
		$data["boxTitle"] = "Pemanggilan Data Rumah Tangga Tersesuaikan";

		$data['periodes'] = $this->pbdt_model->pbdt_periode();
		if(@$_REQUEST['periode']){
			// $this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
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
			// echo var_dump($_COOKIE);
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}
		// Indikator RTM
		$data['param'] = $this->pbdt_model->pbdt_param_idv();
		$data['opsi'] = $this->pbdt_model->pbdt_opsi_idv();

		if($_POST){
			$data['collapse'] = "collapsed-box";
			$param_pilihan = array();
			foreach($data['param'] as $key=>$item){
				$param_pilihan[$item['id']] = $item;
			}
			$data['param_pilihan'] = $param_pilihan;

			$indikator_aktif = array();
			$item =array();
			foreach($_POST as $key=>$rs){
				if($rs){
					$item = explode("_",$key);
					$indikator_aktif[] = $item[1];
				}
			}
			// $data['new_param'] = $new_param;
			$data['indikator_aktif'] = array_unique($indikator_aktif);

			$strSQL = "SELECT d.Provinsi as `Propinsi`,d.Kabupaten as `Kabupaten`,d.Kecamatan as `Kecamatan`,d.Desa as `Desa/Kel.`,
			d.Dusun as `Dusun`,d.RW  as `RW`,d.RT as `RT`,d.RID_RumahTangga as `RID_RumahTangga`,d.nik as `NIK`,d.nama as `Nama Lengkap`,
			d.Status_Kesejahteraan as `Desil`
			FROM pbdt_idv d
			WHERE ((id > 0) AND (periode_id='".$data['periode']."')
			";
			foreach($data['indikator_aktif'] as $param){
				if($param_pilihan[$param]['jenis'] == 1){
					$param_name = "param_".$param;
					$strSQL .= " AND (";
					$x = count($_POST[$param_name]);
					$i = 1;
					foreach($_POST[$param_name] as $nilai){
						$strOr = ($i < $x) ? "OR ":"";
						$strSQL .= " (col_".$param."= '".$nilai."') ".$strOr."\n";
						$i++;
					}
					$strSQL .= ")\n";
				}elseif($param_pilihan[$param]['jenis'] == 2){
					$param_name_min = "param_".$param."_min";
					$param_name_max = "param_".$param."_max";
					$strSQL .= " AND (";
					$strSQL .= " ((col_".$param." >= '".$_POST[$param_name_min]."') AND (col_".$param." <= '".$_POST[$param_name_max]."') ) \n";
					$strSQL .= ")\n";
				}
			}
			$strSQL .= ") ORDER BY status_kesejahteraan";
			// echo $strSQL;
			$data['recordset'] = $this->pbdt_model->do_query($strSQL);
		}else{
			$data['collapse'] = "";
			$data['recordset'] = false;
		}

		$this->load->view('pbdt/pbdt_query_idv',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}			
	}	

	function pengaturan($varApa = 'rts',$varID = 0){
		$data['user'] = $this->session->userdata;
		
		
		$data['kode'] = KODE_BASE;
		$data['statistik'] = false;
		switch ($varApa) {
			case 'periode':
				# code...
				$data['periodes'] = $this->pbdt_model->pbdt_periode();
				if($varID > 0){
					$data['periode'] = $data['periodes']['periode'][$varID];
				}
				$data['pageTitle'] = "Pengaturan Periode Pemutakhiran Data";
				$data['boxTitle'] = "Daftar Periode Pemutakhiran Data";
				$this->load->view('pbdt/pbdt_settings_periode',$data);
				break;
			case 'art':
				# code...
				$data['pageTitle'] = "Pengaturan Indikator Anggota Rumah Tangga";
				$data['boxTitle'] = "Daftar Indikator Anggota Rumah Tangga";
				$data['param'] = $this->pbdt_model->pbdt_param_idv();
				$data['opsi'] = $this->pbdt_model->pbdt_opsi_idv();
				$this->load->view('pbdt/pbdt_settings_idv',$data);
				break;
			
			default:
				# code...
				$data['pageTitle'] = "Pengaturan Indikator Rumah Tangga Sasaran";
				$data['boxTitle'] = "Daftar Indikator Rumah Tangga Sasaran";
				$data['param'] = $this->pbdt_model->pbdt_param();
				$data['opsi'] = $this->pbdt_model->pbdt_opsi();
	
				$this->load->view('pbdt/pbdt_settings_rt',$data);
				break;
		}

		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}
	function simpan_pengaturan($varApa=''){
		$data['user'] = $this->session->userdata;
		switch ($varApa) {
			case 'periode':
				# code...
				echo var_dump($_POST);
				$tgl = explode(' - ',$_POST['rdate']);
				if($_POST['id'] > 0){
					$strSQL = "UPDATE pbdt_periode SET 
						nama='".fixSQL($_POST['nama'])."',
						ndesc='".fixSQL($_POST['ndesc'])."',
						sdate='".fixSQL($tgl[0])."',
						edate='".fixSQL($tgl[1])."',
						userID='".$data['user']['id']."'
						 WHERE id=".$_POST['id'];
				}else{

				}
				$query = $this->db->query($strSQL);
				if($query){
					redirect('pbdt/pengaturan/periode');
				}
				break;
			
			default:
				# code...
				break;
		}
		echo $strSQL;
	}

}

