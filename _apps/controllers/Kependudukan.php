<?php
/*
 * Kependudukan.php
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

class Kependudukan extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('wilayah_model');
		$this->load->model('kependudukan_model');
		$this->load->helper('cookie');
	}
	   
  public function index($varKode=0,$varPage=1){
		$data['user'] = $this->session->userdata;
		// echo var_dump($data['user']);
		if($varKode == 0){
			$varKode = KODE_BASE;
			if($data['user']['tingkat'] >= 3){
				$varKode = $data['user']['wilayah'];
			}
		}
		$data["pageTitle"] = "Data Administrasi dan Kependudukan ".APP_TITLE;

		$data['msg'] = "";
		$data['kode'] = "";
		$data['statistik'] = false;
		$data["boxTitle"] = "Data Administrasi dan Kependudukan";
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["varKode"] = $varKode;
		$data["kode"] = $varKode;
		$data["wilayah"] = $this->wilayah_model->wilayah($varKode);
		$data["subwilayah"] = $this->wilayah_model->subwilayah($varKode);

		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data['demografi'] = $this->kependudukan_model->demografi_wilayah($varKode);

		$this->load->view('kependudukan/index',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function wilayah($varDo='',$varKode=0){
		
	}

	function data_rts($varKode = 0){
		$data['user'] = $this->session->userdata;
		// echo var_dump($data['user']);
		if($varKode == 0){
			$varKode = KODE_BASE;
			if($data['user']['tingkat'] >= 3){
				$varKode = $data['user']['wilayah'];
			}
		}

		$data['msg'] = "";
		$data['kode'] = "";
		$data['statistik'] = false;
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["varKode"] = $varKode;
		$data["kode"] = $varKode;
		$data["wilayah"] = $this->wilayah_model->wilayah($varKode);
		$data["subwilayah"] = $this->wilayah_model->subwilayah($varKode);

		$data["pageTitle"] = "Data Rumah Tangga ".APP_TITLE;
		$data["boxTitle"] = "Data Rumah Tangga";

		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data['dataset'] = $this->kependudukan_model->rumahtangga_by_wilayah($varKode);

		$this->load->view('kependudukan/data_rumahtangga',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function rts_pindah($varRTS = 0){
		ae_nocache();
		$data['user'] = $this->session->userdata;
		$kode_ref = @$_REQUEST['ref'];
		$rts = "";
		if($varRTS > 0){
			$rts = $this->kependudukan_model->load_rtm($varRTS);
			$rts_dest = $this->wilayah_model->list_tujuan_pindah_satudesa(substr($rts['kode_wilayah'],0,10));
		}else{
			$strInfo = "<div class=\"alert alert-danger\"><h4>Maaf, data tidak ditemukan</h4></div>";
		}
		echo "
		<div id=\"mdl_rts\" class=\"modal fade\">
    <div class=\"modal-dialog\">
			<div class=\"modal-content\">
				<div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
						<h4 class=\"modal-title\">RTS Pindah Alamat dalam Satu Desa/Kelurahan</h4>
				</div>
				<form action=\"".site_url('kependudukan/rts_pindah_do')."\" method=\"POST\" role=\"form\">
				<div class=\"modal-body\">
						<fieldset class=\"kotak\">
							<legend>Detail Rumah Tangga</legend>
							<input type=\"hidden\" name=\"rtm_id\" value=\"".$rts['rtm_id']."\"/>
							<input type=\"hidden\" name=\"kode_ref\" value=\"".$kode_ref."\"/>
							<dl class=\"dl-horizontal\">
								<dt>RID RT</dt><dd>".$rts['rtm_no']."</dd>
								<dt>Kepala RT</dt><dd>".$rts['kepala_rumahtangga']."</dd>
								<dt>Alamat Saat Ini</dt><dd>".$rts['dusun']."</dd>
								<dt>RW / RT</dt><dd>".$rts['rw']." / ".$rts['rt']." </dd>
							</dl>
						</fieldset>
						<div class=\"form-group\">
							<label for=\"recipient-name\" class=\"control-label\">Alamat Tujuan</label>
							<select name=\"tujuan\" id=\"tujuan\" class=\"form-control\">";
							foreach($rts_dest as $key=>$rs){
								$strDisabled = ($key == $rts['kode_wilayah']) ? "disabled=\"disabled\"":"";
								echo "<option ".$strDisabled." value=\"".$key."\">".$rs."</option>";
							}
							echo "
							</select>
						</div>
					</div>
					<div class=\"modal-footer\">
						<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Batal</button>
						<button type=\"submit\" class=\"btn btn-primary\"><i class=\"fa fa-save fa-fw\"></i>Simpan</button>
					</div>
				</form>
			</div>
    </div>
	</div>
		";
	}

	function rts_pindah_do(){
		$strSQL = "UPDATE tweb_rumahtangga SET kode_wilayah='".fixSQL($_POST['tujuan'])."' WHERE id=".$_POST['rtm_id'];
		if($this->db->query($strSQL)){
			redirect('kependudukan/data_rts/'.$_POST['kode_ref']);
		}
	}
	function data_penduduk($varKode = 0){
		$data['user'] = $this->session->userdata;
		// echo var_dump($data['user']);
		$varKode = KODE_BASE;
		if($varKode == 0){
			if($data['user']['tingkat'] >= 3){
				$varKode = $data['user']['wilayah'];
			}
		}

		$data['msg'] = "";
		$data['kode'] = "";
		$data['statistik'] = false;
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data["varKode"] = $varKode;
		$data["kode"] = $varKode;
		$data["wilayah"] = $this->wilayah_model->wilayah($varKode);
		$data["subwilayah"] = $this->wilayah_model->subwilayah($varKode);

		$data["pageTitle"] = "Data Rumah Tangga ".APP_TITLE;
		$data["boxTitle"] = "Data Rumah Tangga";

		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		$data['dataset'] = $this->kependudukan_model->penduduk_by_wilayah($varKode);

		$this->load->view('kependudukan/data_penduduk',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function impor_capil(){
		// Impor data dari xls capiil
		$data['user'] = $this->session->userdata;
		// echo var_dump($data['user']);
		$varKode = KODE_BASE;
		if($_POST){
			$data["kode"] = (@$_POST['kode']) ? $_POST['kode']:$data['user']['wilayah'];
			$varKode = $data["kode"];
		}
		
		if(@$data["kode"] == ''){
			if($data['user']['tingkat'] >= 3){
				$varKode = $data['user']['wilayah'];
			}
		}

		if($data['user']['tingkat'] < 3){
			$data['kecamatan'] = $this->wilayah_model->subwilayah(KODE_BASE);
			$data['desa'] = $this->wilayah_model->list_desa(KODE_BASE);

		}
		$data['form_action'] = site_url('kependudukan/impor_capil_do');

		$data['msg'] = @$this->session->flashdata('message');
		// $data['statistik'] = false;

		$data["pageTitle"] = "Impor Data CAPIL ";
		$data["boxTitle"] = "Formulir Impor Data CAPIL";

		// $data["alamat"] = $this->wilayah_model->alamat_bc($varKode);

		$this->load->view('kependudukan/impor_capil',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
		
	}
	function impor_capil_do(){
		// Impor data dari xls capiil
		$hasil = "";
		// Definisi2 
		$shdk = array(
			'kepala keluarga'=>1,
			'suami'=>2,
			'istri'=>3,
			'anak'=>4,
			'menantu'=>5,
			'cucu'=>6,
			'orang tua'=>7,
			'mertua'=>8,
			'famili lain'=>9,
			'pembantu'=>10,
			'lainnya'=>11,
		);
		$status_kawin = array(
			'belum kawin'=>1,
			'kawin'=>2,
			'cerai hidup'=>3,
			'cerai mati'=>4,
		);
		$agama = array(
			'islam'=>1,
			'kristen'=>2,
			'katholik'=>3,
			'hindu'=>4,
			'budha'=>5,
			'konghucu'=>6,
			'lainnya'=>7,
		);
		$gol_darah = array(
			'a'=>1,
			'b'=>2,
			'ab'=>3,
			'o'=>4,
			'a+'=>5,
			'a-'=>6,
			'b+'=>7,
			'b-'=>8,
			'ab+'=>9,
			'ab-'=>10,
			'o+'=>11,
			'o-'=>12,
			'tdk th'=>13,
		);
		$cacat = array(
			'fisik'=>1,
			'netra/buta'=>2,
			'rungu/wicara'=>3,
			'mental/jiwa'=>4,
			'fisik mental'=>5,
			'lainnya'=>6,
		);

		$pendidikan = array(
			'tidak/belum sekolah'=>1,
			'belum tamat sd/sederajat'=>2,
			'tamat sd/sederajat'=>3,
			'sltp/sederajat'=>4,
			'slta/sederajat'=>5,
			'diploma i/ii'=>6,
			'akademi/diploma iii/s. muda'=>7,
			'diploma iv/strata i'=>8,
			'strata ii'=>9,
			'strata iii'=>10,
		);
		$pekerjaan = array(
			'belum/tidak bekerja'=>1,
			'mengurus rumah tangga'=>2,
			'pelajar/mahasiswa'=>3,
			'pensiunan'=>4,
			'pegawai negeri sipil'=>5,
			'tentara nasional indonesia'=>6,
			'kepolisian ri'=>7,
			'perdagangan'=>8,
			'petani/pekebun'=>9,
			'peternak'=>10,
			'nelayan/perikanan'=>11,
			'industri'=>12,
			'konstruksi'=>13,
			'transportasi'=>14,
			'karyawan swasta'=>15,
			'karyawan bumn'=>16,
			'karyawan bumd'=>17,
			'karyawan honorer'=>18,
			'buruh harian lepas'=>19,
			'buruh tani/perkebunan'=>20,
			'buruh nelayan/perikanan'=>21,
			'buruh peternakan'=>22,
			'pembantu rumah tangga'=>23,
			'tukang cukur'=>24,
			'tukang listrik'=>25,
			'tukang batu'=>26,
			'tukang kayu'=>27,
			'tukang sol sepatu'=>28,
			'tukang las/pandai besi'=>29,
			'tukang jahit'=>30,
			'tukang gigi'=>31,
			'penata rias'=>32,
			'penata busana'=>33,
			'penata rambut'=>34,
			'mekanik'=>35,
			'seniman'=>36,
			'tabib'=>37,
			'paraji'=>38,
			'perancang busana'=>39,
			'penterjemah'=>40,
			'imam mesjid'=>41,
			'pendeta'=>42,
			'pastor'=>43,
			'wartawan'=>44,
			'ustadz/mubaligh'=>45,
			'juru masak'=>46,
			'promotor acara'=>47,
			'anggota dpr-ri'=>48,
			'anggota dpd'=>49,
			'anggota bpk'=>50,
			'presiden'=>51,
			'wakil presiden'=>52,
			'anggota mahkamah konstitusi'=>53,
			'anggota kabinet/kementerian'=>54,
			'duta besar'=>55,
			'gubernur'=>56,
			'wakil gubernur'=>57,
			'bupati'=>58,
			'wakil bupati'=>59,
			'walikota'=>60,
			'wakil walikota'=>61,
			'anggota dprd provinsi'=>62,
			'anggota dprd kabupaten/kota'=>63,
			'dosen'=>64,
			'guru'=>65,
			'pilot'=>66,
			'pengacara'=>67,
			'notaris'=>68,
			'arsitek'=>69,
			'akuntan'=>70,
			'konsultan'=>71,
			'dokter'=>72,
			'bidan'=>73,
			'perawat'=>74,
			'apoteker'=>75,
			'psikiater/psikolog'=>76,
			'penyiar televisi'=>77,
			'penyiar radio'=>78,
			'pelaut'=>79,
			'peneliti'=>80,
			'sopir'=>81,
			'pialang'=>82,
			'paranormal'=>83,
			'pedagang'=>84,
			'perangkat desa'=>85,
			'kepala desa'=>86,
			'biarawati'=>87,
			'wiraswasta'=>88,
			'lainnya'=>89
		);

		$dusun_array = array();
		$strSQL = "SELECT kode,nama,tingkat FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($_POST['desa'])."%' ORDER BY kode";
		$query = $this->db->query($strSQL);
		if($query){
			foreach ($query->result() as $rs){
				$dusun_array[$rs->nama] = array("nama"=>$rs->nama,
					"tingkat"=>$rs->tingkat,
					"kode"=>$rs->kode);
			}
		}

		$limit = file_upload_max_size();

		if($_FILES['berkas']['size'] < $limit){
			$varKode = $_POST['desa'];
			$desa_import = $this->wilayah_model->get_alamat($varKode);
			/*
			 * Proses berkas unggahan 
			 * 
			 * */
			$file_name = false;
			$name = $_FILES["berkas"]["name"];
			$temp_name = explode(".", $_FILES["berkas"]["name"]);
			$ext = end($temp_name); 
			$newFile = "CAPIL_".$varKode."_".time().".".$ext; 
			$vdir_upload = FCPATH."assets/uploads/";
			$vfile_upload = $vdir_upload .$newFile;
			if(move_uploaded_file($_FILES["berkas"]["tmp_name"], $vfile_upload)){
				$file_name = $vdir_upload.$newFile;
			}

			if(is_file($file_name)){
			
				$this->load->library('PHPExcel');

				$read   = PHPExcel_IOFactory::createReaderForFile($file_name);

				$read->setReadDataOnly(true);
				$excel  = $read->load($file_name);
				PHPExcel_Calculation::getInstance($excel)->cyclicFormulaCount = 1;
				$sheets = $read->listWorksheetNames($file_name);
				// echo var_dump($sheets);
				$_sheet = $excel->setActiveSheetIndexByName('Data penduduk');
				$maxRow = $_sheet->getHighestRow();
				$maxCol = $_sheet->getHighestColumn();
				$sheetData = $_sheet->toArray(null, true, true, true);
				$i=1;

				$penduduk = array();
				$keluarga = array();
				$i=1;
				$data = false;
				$sqlPenduduk = "INSERT INTO `tweb_penduduk`(`nama`,`nik`, `kode_wilayah`, `alamat`, `kk_nomor`, `kk_hubungan`, `sex`, `tlahir`, `dtlahir`, `kawin_status`, `kawin_buku`, `nama_ayah`, `nama_ibu`, `pendidikan_id`, `pekerjaan_id`, `akta_lahir`, `akta_nikah`, `akta_cerai`, `ktp`, `ktp_date`) VALUES \n";
				$strSQL = $sqlPenduduk;


				foreach($sheetData as $item=>$rs){
					if($rs['B'] == 1){
						$data = true;
					}

					if($data){
						$kode_dusun = (array_key_exists(strtoupper(trim($rs['E'])),$dusun_array)) ? $dusun_array[strtoupper(trim($rs['E']))]['kode'] : $varKode.'00';
						$kode_wilayah = $kode_dusun.str_pad($rs['F'],2,'0',STR_PAD_LEFT).str_pad($rs['G'],2,'0',STR_PAD_LEFT);
						$nomor_kk = trim(str_replace(".","",$rs['H']));
						$nik = trim(str_replace(".","",$rs['J']));
						$dtlahir = '0000-00-00';
						if(strlen($rs['N'])> 5){
							echo $rs['N'];
							$lahir = 	explode("/",trim($rs['N']));
							$dtlahir = $lahir[2] ."-".$lahir[1]."-".$lahir[0];
						}

						$ktp_id = (strtolower(trim($rs['S'])) == "ya") ? 1:0;

						$ktp_end = '0000-00-00';
						if(strlen($rs['T'])> 5){
							$ektp  = 	explode("/",trim($rs['T']));
							$ktp_end = $ektp[2] ."-".$ektp[1]."-".$ektp[0];
						}

						$alamat = $rs['E']." RW ".$rs['F']." / RT ".$rs['G'];

						$pendidikan_id = (array_key_exists(strtolower($rs['X']),$pendidikan)) ? $pendidikan[strtolower($rs['X'])]:0;
						$pekerjaan_id = (array_key_exists(strtolower($rs['Y']),$pekerjaan)) ? $pekerjaan[strtolower($rs['Y'])]:0;
						$shdk_id = (array_key_exists(strtolower($rs['R']),$shdk)) ? $shdk[strtolower($rs['R'])]:0;
						$sex_id = (strtolower($rs['L']) == 'p') ? 2:1;
						$kawin_id = (array_key_exists(strtolower($rs['Q']),$status_kawin)) ? $status_kawin[strtolower($rs['Q'])]:0;

						$strSQLData ="('".fixSQL($rs['K'])."','".fixSQL($nik)."','".fixSQL($kode_wilayah)."','".fixSQL($alamat)."','".fixSQL($nomor_kk)."','".fixSQL($shdk_id)."','".$sex_id."','".fixSQL($rs['M'])."','".$dtlahir."','".fixSQL($kawin_id)."','".fixSQL($rs['AC'])."','".fixSQL($rs['Z'])."','".fixSQL($rs['AA'])."','".fixSQL($pendidikan_id)."','".fixSQL($pendidikan_id)."','".fixSQL($rs['AB'])."','".fixSQL($rs['AC'])."','".fixSQL($rs['AD'])."','".$ktp_id."','".fixSQL($ktp_end)."')";
					
						$strSQL .= $strSQLData.",\n";

						if($shdk_id  == 1){
							$keluarga[$nomor_kk] = array(
								'kk_no'=>$nomor_kk,
								'kode_wilayah'=>$kode_wilayah,
								'nik_kk'=>$nik,
								'nama_kk'=>$rs['K']
							);
						}

					}
					$i++;
				}

				if(substr(trim($strSQL),-1) == ","){
					$strSQL = substr(trim($strSQL),0,-1).";";
				}
				$hasil ="<h4>Hasil Import Data Capil ".$desa_import."</h4>
					<ul>";
				$query = $this->db->query($strSQL);
				if($query){
					$hasil .= "<li>Data Penduduk Masuk ".$this->db->affected_rows()."</li>";
				}

				$sqlKeluarga = "INSERT INTO `tweb_keluarga`(`kk_no`, `kode_wilayah`, `nik_kk`, `nama_kk`) VALUES \n";
				foreach($keluarga as $key=>$k){
					$sqlKeluarga.= "('".fixSQL($k['kk_no'])."','".fixSQL($k['kode_wilayah'])."','".fixSQL($k['nik_kk'])."','".fixSQL($k['nama_kk'])."'),\n";
				}
				if(substr(trim($sqlKeluarga),-1) == ","){
					$sqlKeluarga = substr(trim($sqlKeluarga),0,-1);
				}
				$query = $this->db->query($sqlKeluarga).";";
				if($query){
					$hasil .= "<li>Data KK Masuk ".$this->db->affected_rows()."</li>";
				}

				$hasil .="</ul>";
			}
		}
		$this->session->set_flashdata('message', $hasil);
		redirect('kependudukan/impor_capil');
	}
	
}

