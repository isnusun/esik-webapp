<?php
/*
 * Publik.php
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

require FCPATH .'/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Verval extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->siteman_login->cek_login();

		$this->load->model('siteman_model');
		$this->load->model('verval_model');
		$this->load->model('bdt_model');
		$this->load->model('wilayah_model');
		$this->load->model('rumahtangga_model');
	}

	public function index(){
		$data['user'] = $this->session->userdata;
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		$data['periodes'] = $this->bdt_model->periodes(0);
		$data['pageTitle']= "Verivali Data";

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
		
		$data['dusun'] = $this->wilayah_model->subwilayah($kode_wilayah);
		
		$data['wilayah'] = $this->wilayah_model->wilayah($kode_wilayah);
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode'] : $data['user']['wilayah'];
		$data['varKode'] = $varKode;
		$data['sasaran'] = false;
		$data['area'] = $this->wilayah_model->wilayah($varKode);
		$data['sub_wilayah'] = $this->verval_model->responden_statistik_by_wilayah($varKode);
		$this->load->view('backend/verval/index',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}

	function form_rts($varRTM = 0){
		if($varRTM > 0)
		{
			$this->load->model('wilayah_model');
			$this->load->model('penduduk_model');

			$data['user'] = $this->session->userdata;
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
			$data["pageTitle"] = "Verifikasi dan Validasi Data Rumah Tangga Sasaran ";
	
			$data['msg'] = "";
	
			$data['periodes'] = $this->bdt_model->periodes(0);
			$data['periode_q']= $data['periodes']['periode_aktif'];

			$data['kecamatan']= $this->wilayah_model->list_subwilayah(substr($data['user']['wilayah'],0,4),3);

			$data['indikator'] = $this->bdt_model->bdt_indikator('rts');

			$data['form_action_domisili'] = site_url('verval/simpan_rts_domisili');
			// $data['form_action_rts'] = site_url('verivali/simpan_rts');
			// $data['form_action_peta'] = site_url('verivali/simpan_peta');
			// $data['form_add_kk2rtm'] = site_url('verivali/add_kk2rtm');
			// $data['form_add_newkk2rtm'] = site_url('verivali/add_kk2rtm_new');
			$data_rtm = array(
				'rts'=>$this->penduduk_model->rtm_load($varRTM),
				'kks'=>$this->penduduk_model->rtm_load_kk($varRTM),
				'anggota'=>$this->penduduk_model->rtm_load_art($varRTM),
			);
			$data['data_rtm'] = $data_rtm;

			$data["boxTitle"] = "Data Rumah Tangga Sasaran <strong>".$varRTM."</strong> periode <strong>".$data['periodes']['periode'][$data['periode_q']]['nama']."</strong>";

			$this->load->view('backend/verval/form_rts',$data);	
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
		}
		else{
			redirect('verval');
		}
	}
	function hapus_rts(){
		// echo var_dump($_POST);
		$user = $this->session->userdata;
		if($_POST){
			if(strlen($_POST['alasan']) == 0)
			{
				$strMsg = array(
					'title'=>"Data Tidak bisa dihapus",
					'body'=>"Anda belum menyertakan alasan penghapusan",
					'jenis'=>'error'
				);
				$_SESSION['msg'] = $strMsg;
				redirect('verval/?kode='.$_POST['kode']);
			}
			else
			{
				// echo var_dump($_POST);
				$strSQL = "SELECT * FROM tweb_rumahtangga WHERE id=".$_POST['rtm_id'];
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0)
				{
					$data_rtm = $query->result_array()[0];
					$rtm_no = $data_rtm['rtm_no'];

					$strSQL = "SELECT * FROM tweb_keluarga WHERE rtm_no=".fixSQL($rtm_no);
					$query = $this->db->query($strSQL);
					$data_kk = $query->result_array();
	
					$strSQL = "SELECT * FROM tweb_penduduk WHERE rtm_no=".fixSQL($rtm_no);
					$query = $this->db->query($strSQL);
					$data_art = $query->result_array();
	
					$strData = array(
						'rtm_id'=>$_POST['rtm_id'],
						'rtm_no'=>$rtm_no,
						'data_rtm'=>$data_rtm,
						'data_kk'=>$data_kk,
						'data_art'=>$data_art
					);
					$data = json_encode($strData);
					$strSQL = "INSERT INTO tweb_rts_dihapus(`rtm_no`,`data`,`created_by`,`alasan`)
					VALUES('".fixSQL($rtm_no)."','".fixSQL($data)."','".$user['id']."','".fixSQL($_POST['alasan'])."')";
					// echo $strSQL;
					if($this->db->query($strSQL))
					{
						$strSQL = "DELETE FROM tweb_rumahtangga WHERE id=".$_POST['rtm_id'];
						if($this->db->query($strSQL))
						{
							$strMsg = array(
								'title'=>"Data BERHASIL dihapus",
								'body'=>"RTM NO ".$rtm_no." telah DIHAPUS dari SISTEM",
								'jenis'=>'success'
							);
							$_SESSION['msg'] = $strMsg;
							redirect('verval/?kode='.$_POST['kode']);
						}
					}
						
				}
				else
				{
					$strMsg = array(
						'title'=>"Data tidak ditemukan/sudah dihapus",
						'body'=>"RTM NO ".$rtm_no." telah DIHAPUS dari SISTEM",
						'jenis'=>'warning'
					);
					$_SESSION['msg'] = $strMsg;
					redirect('verval/?kode='.$_POST['kode']);

				}
			}
		}
	}

	function form_kks($varNOKK=0)
	{
		// $this->load->model('kependudukan_model');
		$this->load->model('wilayah_model');

		$data['user'] = $this->session->userdata;
		$data['app'] = $this->session->userdata;
		$data["pageTitle"] = "Verifikasi dan Validasi Data Keluarga ".APP_TITLE;
		$data['msg'] = "";
		$data['periodes'] = $this->gakin_model->gakin_periode(0);
		$data['form_action_domisili'] = site_url('verval/simpan_kk_domisili');
		$data['form_action_kks'] = site_url('verval/simpan_data_kk');

		$data['kecamatan'] = $this->wilayah_model->subwilayah(KODE_BASE);

		$data['kk'] = $this->kependudukan_model->data_kartu_keluarga($varNOKK);

		$data["boxTitle"] = "VeriVali data Kartu Keluarga <strong>".$data['kk']['pnama']." [NOMOR KK ".$data['kk']['kk_no']."]</strong>";

		$data['rtm'] = $this->gakin_model->rts_by_no($data['kk']['rtm_no']);
		
		if($data['kk']['rts_utama']==1){
			$data['indikator_kategori'] = $this->gakin_model->gakin_kategori(2);
			$data['indikator_item'] = $this->gakin_model->gakin_param(1);
		}else{
			$data['indikator_kategori'] = $this->gakin_model->gakin_kategori(2);
			$data['indikator_item'] = $this->gakin_model->gakin_param(0);
		}
		// $data['opsi'] = $this->gakin_model->
			// foreach ($data['periodes']['periode'] as $key => $value) {
			// 	# code...
			// 	$data['vali'][$key] = $this->gakin_model->gakin_data_by_kk_flat($varNOKK,$key);
			// }
		// echo var_dump($data['vali']);
		$data['vali'] = $this->gakin_model->gakin_data_by_kk($varNOKK,$data['periodes']['periode_aktif']);
		$data['valix'] = $this->gakin_model->gakin_data_by_kk_flat($varNOKK,$data['periodes']['periode_aktif']);
		$this->load->view('verivali/form_kks',$data);	
		

		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function simpan_data_kk(){
		echo var_dump($_POST);
	}

	function simpan_rts_domisili(){
		echo var_dump($_POST);
	}

	function form_art($varID=0)
	{
		echo $varID;
	}

	// Migrasi KK
	
	function set_rts_utama($varRTM_ID=0,$varRTM_No=0,$varKK_ID=0,$varDO=0){
		/* '.$rtm['rtm_id'].'/'.$o['kk_id'].'/'.$o['pnik'].'/1
		 * Set KK sbg RTS utama dgn KK pd orang yg dibaris tsb
		 * echo $varDO;
		 * */
		$strSQL = "SELECT rtm_no,kk_no,capil_stat FROM tweb_keluarga WHERE id='".$varKK_ID."'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$kk_no = $rs['kk_no'];
				$rtm_no = $rs['rtm_no'];
				if($varDO == 1){
					$strSQL = "UPDATE tweb_keluarga SET rts_utama=1 WHERE id='".$varKK_ID."'";
					$query = $this->db->query($strSQL);
					if($query){
						$strSQL = "UPDATE gk_data SET responden_tipe=1,responden_id='".$kk_no."' WHERE ((responden_id='".$kk_no."') OR (responden_id='".$rtm_no."'))";
						$query = $this->db->query($strSQL);
						if($query){
							redirect('verivali/form_rts/'.$varRTM_No);
						}
					}
				}
				if($varDO == 0){
					$strSQL = "UPDATE tweb_keluarga SET rts_utama=0 WHERE id='".$varKK_ID."';";
					$query = $this->db->query($strSQL);
					if($query){
						$strSQL = "UPDATE gk_data SET responden_tipe=2 WHERE responden_id='".$kk_no."'";
						$strSQL = "UPDATE gk_data SET responden_tipe=2,responden_id='".$kk_no."' WHERE ((responden_id='".$kk_no."') OR (responden_id='".$rtm_no."'))";
						$query = $this->db->query($strSQL);
						if($query){
							redirect('verivali/form_rts/'.$varRTM_No);
						}
					}
				}
			}else{
				
			}
		}
		
	}
	
	function set_rts_out($varKK_NO=0,$varRTM_No=0,$varKK_ID=0){
		if($varKK_ID > 0){
			$strSQL = "UPDATE tweb_keluarga SET rtm_no=0 WHERE id=".$varKK_ID;
			$query = $this->db->query($strSQL);
			if($query){
				$strSQL = "UPDATE tweb_penduduk SET rtm_no=0 WHERE kk_nomor='".fixSQL($varKK_NO)."'";
				if($this->db->query($strSQL)){
					$_SESSION['strMsg'] = array('status'=>true,'msg'=>"Berhasil menghapus data KK <strong>".$varKK_NO."</strong> dari RTS <strong>".$varRTM_No."</strong>");
					redirect('verivali/form_rts/'.$varRTM_No);
				}
			}
		}else{
			redirect('verivali');
		}
	}

	function progres(){
		echo "Hello World";
	}

	function import_data_baru(){
		$this->load->library('PHPExcel');

		$data['user'] = $this->session->userdata;
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$data['periodes'] = $this->bdt_model->periodes(0);
		if($_FILES){
			$kode_desa = $data['user']['wilayah'];
			if(strlen($kode_desa) < 10){

				redirect('backend/verval');
			}
			$dusuns = $this->wilayah_model->subwilayah($kode_desa);
			$dusun_by_nama = array();

			foreach ($dusuns as $key => $value) {
				# code...
				$dusun_by_nama[strtolower(trim($value['nama']))] = $value['kode'];
			}

			$file_name = $_FILES["berkas"]["tmp_name"];
			$file_ext = strtoupper(pathinfo($file_name, PATHINFO_EXTENSION));
			$inputFile = FCPATH."assets/uploads/rts_usulan_baru_".time().".".$file_ext;
			// echo $inputFile;
			if(move_uploaded_file($_FILES["berkas"]["tmp_name"], $inputFile)){
				if(is_file($inputFile)){
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFile);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFile);
					} catch(Exception $e) {
						die($e->getMessage());
					}
			
					//Get worksheet dimensions
					$sheet = $objPHPExcel->getSheet(0); 
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
			
					//Loop through each row of the worksheet in turn
					$baris_data = 0;
					$nomor_rts = "";
					$nomor_kk = "";
					$kode_wilayah = "";
					$nama_kepala_rumahtangga = "";
					$alamat = "";
					$keluarga = array();
					$penduduk = array();
					$rumahtagga = array();

					for ($row = 1; $row <= $highestRow; $row++){ 
						$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
						if(trim(strtoupper($rowData[0][0])) == 'NOMOR RUMAH TANGGA'){
							$baris_data= $row+1;
						}
						if($baris_data >= $row){
							if(trim(strtoupper($rowData[0][0])) != 'NOMOR RUMAH TANGGA'){
								// echo var_dump($rowData);
								if(strlen(trim($rowData[0][0])) > 0){
									if(array_key_exists(strtolower(trim($rowData[0][1])),$dusun_by_nama)){
										$kode_wilayah = $dusun_by_nama[strtolower(trim($rowData[0][1]))];
										if($rowData[0][2]){
											$kode_wilayah .= str_pad(trim($rowData[0][2]),3,'0',STR_PAD_LEFT);
										}
										if($rowData[0][3]){
											$kode_wilayah .= str_pad(trim($rowData[0][2]),3,'0',STR_PAD_LEFT);
										}
									}else{
										$strSQL = "SELECT max(kode) as max_kode FROM tweb_wilayah WHERE kode LIKE '".$kode_desa."%' AND tingkat=5";
										$query = $this->db->query($strSQL);
										if($query->num_rows() > 0){
											$rs = $query->result_array()[0];
											$kode_dusun_baru = (int) $rs['max_kode'] + 1;
											$strSQL = "INSERT INTO tweb_wilayah(`kode`,`nama`,`tingkat`) 
												VALUES('".fixSQL($kode_dusun_baru)."','".fixSQL(strtoupper(trim($rowData[0][1])))."',5);";
											// echo $strSQL;
											$kode_wilayah = $kode_dusun_baru;
											if($query = $this->db->query($strSQL)){
												$dusun_by_nama[strtoupper(trim($rowData[0][1]))] = $kode_dusun_baru;
												$strSQL = "INSERT INTO tweb_wilayah(`kode`,`nama`,`tingkat`) VALUES \n";
												if($rowData[0][2]){
													$kode_wilayah .= str_pad(trim($rowData[0][2]),3,'0',STR_PAD_LEFT);
													$strSQL .="('".fixSQL($kode_wilayah)."','".fixSQL(strtoupper(trim($rowData[0][2])))."',6)";
												}
												if($rowData[0][3]){
													$kode_wilayah .= str_pad(trim($rowData[0][3]),3,'0',STR_PAD_LEFT);
													$strSQL .=", ('".fixSQL($kode_wilayah)."','".fixSQL(strtoupper(trim($rowData[0][3])))."',7) \n";
												}
												$query = $this->db->query($strSQL);
											}
										}
									}
									$nama_kepala_rumahtangga = $rowData[0][7];
									$alamat = $rowData[0][1] ." RW ".$rowData[0][2] ." RT ".$rowData[0][3];
									$nomor_rts = (strlen(trim($rowData[0][0]))> 0)? trim($rowData[0][0]):$nomor_rts;
									$idbdt_baru = $kode_desa.str_pad(trim($rowData[0][0]),4,'0',STR_PAD_LEFT);
									if(strlen(trim($rowData[0][0]))> 0){
										// Rumah Tangga baru
										$rumahtagga[] = array(
											'kode_wilayah'=>$kode_wilayah,
											'idbdt_baru'=>$idbdt_baru,
											'nama_kepala_rumahtangga'=>$nama_kepala_rumahtangga,
											'nik_kepala_rumahtangga'=>trim($rowData[0][6]),
											'alamat'=>$alamat
										);

									}
								}

								$nomor_kk = (strlen(trim($rowData[0][5]))> 0)? $rowData[0][5]:$nomor_kk;
								if(strlen(trim($rowData[0][5]))> 0){
									// KK Baru baru
									$keluarga[$nomor_kk] = array(
										'idbdtbaru'=>$idbdt_baru,
										'nomor_kk'=>$nomor_kk,
										'kode_wilayah'=>$kode_wilayah);
								}
								
								$nomor_nik = trim($rowData[0][6]);
								$nama_lengkap = trim($rowData[0][7]);
								if(strlen(trim($nama_lengkap)) > 0){
									$penduduk[] = array(
										'kode_wilayah'=>$kode_wilayah,
										'idbdtbaru'=>$idbdt_baru,
										'nomor_kk'=>$nomor_kk,
										'nik'=>$nomor_nik,
										'nama_lengkap'=>$nama_lengkap,
										'alamat'=>$alamat,
									);
								}
								$baris_data++;
							}
						}
					}
					$err = array();
					$hasil_import = array();
					if(count($rumahtagga) > 0){
						$strSQLRTS = "INSERT INTO tweb_rumahtangga(`kode_wilayah`, `idbdt`, `nopesertapbdt`, `nama_kepala_rumah_tangga`,`nik_kepala_rumahtangga`, `alamat`, `usulan_baru`,`updated_by`) VALUES \n";
						$nkk = count($rumahtagga);
						$k = 1;
						$idbdt_baru_baris = 0;
						foreach ($rumahtagga as $key => $value) {
							# code...
							$strKoma = ($k < $nkk) ? ", \n":";\n";
							$strSQLRTS .= "('".fixSQL($value['kode_wilayah'])."','".fixSQL($value['idbdt_baru'])."','".fixSQL($value['idbdt_baru'])."','".fixSQL($value['nama_kepala_rumahtangga'])."','".fixSQL($value['nik_kepala_rumahtangga'])."','".fixSQL($value['alamat'])."',1,'".$data['user']['id']."')".$strKoma;
							$k++;
						}
						if($this->db->query($strSQLRTS)){
							$hasil_import['Jumlah Rumah Tangga Baru'] = $nkk;
						}else{
							$err['rts']=$strSQLRTS;
						}
					}
					
					if(count($keluarga) > 0){
						$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`,`idbdt`,`kk_nomor`,`created_by`,`created_at`) VALUES \n";
						$nkk = count($keluarga);
						$k = 1;
						foreach ($keluarga as $key => $value) {
							# code...
							$strKoma = ($k < $nkk) ? ", \n":"; \n";
							$strSQLKK .= "('".fixSQL($value['kode_wilayah'])."','".fixSQL($value['idbdtbaru'])."','".fixSQL($value['nomor_kk'])."','".$data['user']['id']."','".date('Y-m-d H:i:s')."')".$strKoma;
							$k++;
						}
						if($this->db->query($strSQLKK)){
							$hasil_import['Jumlah Kartu Keluarga Baru'] = $nkk;
						}else{
							$err['kk']=$strSQLKK;
						}

					}
					if(count($penduduk) > 0){
						$strSQLART = "INSERT IGNORE INTO tweb_penduduk(`kode_wilayah`, `kode_kab`, `nopesertapbdtart`, `idartbdt`, `idbdt`, `nopesertapbdt`, `nik`, `nama`, `alamat`, `kk_nomor`) VALUES \n";
						$nkk = count($penduduk);
						$k = 1;
						$idbdt_baru_baris = 0;
						$j=1;
						foreach ($penduduk as $key => $value) {
							# code...
							if($idbdt_baru_baris != $value['idbdtbaru']){
								$idbdt_baru_baris = $value['idbdtbaru'];
								$j=1;
							}
							$idbdt_art_baru = $idbdt_baru_baris.str_pad($j,2,'0',STR_PAD_LEFT);

							$strKoma = ($k < $nkk) ? ", \n":";\n";
							$nik_value = (strlen($value['nik']) == 16) ? $value['nik']: randomNumber(16);
							$strSQLART .= "('".fixSQL($value['kode_wilayah'])."','".substr($kode_wilayah,0,4)."','".fixSQL($idbdt_art_baru)."','".fixSQL($idbdt_art_baru)."','".fixSQL($value['idbdtbaru'])."','".fixSQL($value['idbdtbaru'])."','".fixSQL($nik_value)."','".fixSQL($value['nama_lengkap'])."','".fixSQL($value['alamat'])."','".fixSQL($value['nomor_kk'])."')".$strKoma;
							$k++;
							$j++;
						}
						if($this->db->query($strSQLART)){
							$hasil_import['Jumlah Anggota Rumah Tangga Baru'] = $nkk;
						}else{
							$err['art']=$strSQLART;
						}
					}
					if(count($err) > 0){
						echo var_dump($err);
					}else{
						$strMsg = "<ul>";
						foreach($hasil_import as $key=>$value){
							$strMsg .= "<li>".$key." = <strong>".$value."</strong></li>";
						}
						$strMsg .= "</ul>";
						$_SESSION['msg'] = array(
							'jenis'=>'success',
							'title'=>'Berhasil Import Data Rumahtangga baru',
							'body'=>$strMsg,
						);
						redirect('backend/verval');
					}

				}
			}else{
				echo "Gagal Upload file .xls";
			}
		}

	}
}
