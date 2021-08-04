<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload = FCPATH."/vendor/autoload.php";
require_once($autoload);

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Wscapil extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('siteman_model');
	}

	function index(){
		echo "Hello World";
	}

	function dukcapil_by_nik($varNIK){

		$wscfg = WS_CAPIL_CFG;
		$ws_capil_url = $wscfg['proto']."://".$wscfg['host'].":".$wscfg['port']."/".$wscfg['endpoint'];

		$kunci = md5($wscfg['pass'].date('dmY'));
		$ws_capil_url .="?kunci=".$kunci;
		$ws_capil_url .="&user=".$wscfg['user'];
		$ws_capil_url .="&akses=nik";
		$ws_capil_url .="&nomor_nik=".$varNIK;

		// echo $ws_capil_url;
        try {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => $ws_capil_url,
                'timeout' => 60,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'verify' => false,
            ]);

            $response = $client->request('POST', $ws_capil_url);

            $r = $response->getBody()->getContents();
			$hasil = array(
				'status'=>'OK',
				'data'=> json_decode($r)
			);
			// return $results;
        } catch (RequestException $e) {
			// echo date('Y-m-d H:i:s') . '|Error: ' . $e->getResponse()->getBody() . PHP_EOL;
			$hasil = array(
				'status'=>'Error',
				'data'=>false
			);
		}		
		return $hasil;
	}

	function dukcapil_by_nokk($varNokk=''){

		$wscfg = WS_CAPIL_CFG;
		$ws_capil_url = $wscfg['proto']."://".$wscfg['host'].":".$wscfg['port']."/".$wscfg['endpoint'];

		$kunci = md5($wscfg['pass'].date('dmY'));
		$ws_capil_url .="?kunci=".$kunci;
		$ws_capil_url .="&user=".$wscfg['user'];
		$ws_capil_url .="&akses=kk";
		$ws_capil_url .="&nomor_kk=".$varNokk;

		// echo $ws_capil_url;
        try {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => $ws_capil_url,
                'timeout' => 60,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'verify' => false,
            ]);

            $response = $client->request('POST', $ws_capil_url);

            $r = $response->getBody()->getContents();
            $results = json_decode($r);
            return $results;
            // if (count($results) > 0) {
            // }
        } catch (RequestException $e) {
			return false;
            echo date('Y-m-d H:i:s') . '|Error: ' . $e->getResponse()->getBody() . PHP_EOL;
        }		
	}
	function bot2019($varNIK = ''){
		$kode_base = KODE_BASE;
		$kode_no_kab = substr($kode_base,-2);
		if($varNIK != ''){

			$capil = $this->dukcapil_by_nik($varNIK);

			if($capil['status']=='OK'){
				if($capil['data'] != 'NULL'){
					// echo "Data IDV: \n";
					// echo var_dump($capil['data']);
					$lead_id = 1;

					foreach($capil['data'] as $key=>$rs){
						// echo $key ." ->> ".var_dump($rs) ."\n";
						$data_idv = $rs;
					}
					
					// // $no_kk = $capil['data']->nomor_kk;
					// echo $data_idv[0]->nomor_kk;

					// echo "Data KK: \n";
					$data_kk = $this->dukcapil_by_nokk($data_idv[0]->nomor_kk);
					// // $kk = $capil['data']
					// INSERT INTO PENDUDUK

					$strSQL = "INSERT INTO tweb_penduduk(`kode_wilayah`, `idartbdt`, `idbdt`, `ruta6`, `nopesertapbdt`, `nopesertapbdtart`, `nik`, `nama`, `alamat`, `kk_nomor`, `rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`, `foto`, `nama_lengkap`, `nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, `tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, `alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, `nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, `no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, `kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, `kode_status_kawin`) VALUES";
					foreach($data_kk->data as $key=>$rs){
						// echo $key ." ->> ".var_dump($rs) ."\n";
						echo $key ."" . var_dump($rs);
						$strSQL .="()";
					}

					// echo var_dump($data_kk);
					$strSQL = "SELECT ";

					// `id`, `kode_wilayah`, `idartbdt`, `idbdt`, `ruta6`, `nopesertapbdt`, `nopesertapbdtart`, `nik`, `nama`, `alamat`, `kk_nomor`, `rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`, `foto`, `nama_lengkap`, `nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, `tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, `alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, `nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, `no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, `kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, `kode_status_kawin` FROM `tweb_penduduk`
				}else{
					$strSQLHasil = "UPDATE bdt_idv SET capil_status=2, capil_date=NOW() WHERE lead_id='".$lead_id."'";
					$query = $this->db->query($strSQL);
					if($query){
					}
			
				}
			}
		}else{
			// PRODUCTION
			

			$strSQL = "SELECT b.`lead_id`,b.`nik`,b.`nokk`,b.`idartbdt`,b.`idbdt`,b.`kode_wilayah`,b.`ruta6`, b.`nopesertapbdt`, b.`nopesertapbdtart`,b.`hub_krt` 
			FROM bdt_idv b WHERE 
			((b.`kdkab`='".$kode_no_kab."') AND (b.`periode_id`=4) AND (b.`capil_status`=0)
			AND (b.`nik` NOT IN (SELECT p.nik FROM tweb_penduduk p WHERE p.`kode_kab`='".$kode_base."'))) 
			LIMIT 1 ";
			/* 
			// DEVELOPMENT
			$strSQL = "SELECT `lead_id`,`nik`,`nokk`,`idartbdt`,`idbdt`,`kode_wilayah`,`ruta6`, `nopesertapbdt`, `nopesertapbdtart`,`hub_krt`
				FROM bdt_idv WHERE ((kode_wilayah LIKE '3313%') AND (periode_id=4) AND (capil_status=0)) 
				AND (nik='3313051707650004') ";
			*/
			$query = $this->db->query($strSQL);
			// echo $strSQL;
			if($query){
				if($query->num_rows() > 0){
					$rs = $query->result_array()[0];
					// ambil satu record utk ceck berikut nya 
					$lead_id = $rs['lead_id'];
					$kode_wilayah = $rs['kode_wilayah'];
					$kode_wilayah_desa_bdt = substr($kode_wilayah,0,10);
					$idartbdt = $rs['idartbdt'];
					$idbdt = $rs['idbdt'];
					$ruta6  = $rs['ruta6'];
					$nopesertapbdt = $rs['nopesertapbdt'];
					$nopesertapbdtart = $rs['nopesertapbdtart'];
					$hub_krt = $rs['hub_krt'];


					// check ke WS dukcapil 
					$capil = $this->dukcapil_by_nik($rs['nik']);
					if($capil['status']=='OK'){
						// $x = $capil['data']->data;
						// echo var_dump($capil['data']->data);
						// echo "<hr />";
						// echo strtolower(trim($capil['data']->data));
						// echo "<hr />";
						if(is_array($capil['data']->data)){
							// echo var_dump($capil['data']);

							echo date("Y-m-d H:i:s")." : PROSES CAPIL ".$rs['nik']."\n";

							foreach($capil['data'] as $key=>$crs){
								$data_idv = $crs;
							}
							$data_kk = $this->dukcapil_by_nokk($data_idv[0]->nomor_kk);
		
							$strSQL = "INSERT INTO tweb_penduduk(`kode_wilayah`,`kode_wilayah_capil`, `idartbdt`, `idbdt`, 
							`ruta6`, `nopesertapbdt`, `nopesertapbdtart`, 
							`nik`, `nama`, `alamat`, `kk_nomor`, 
							`rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`,`nama_lengkap`, 
							`nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, 
							`tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, 
							`alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, 
							`nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, 
							`no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, 
							`kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, 
							`kode_status_kawin`) VALUES";

							$fix_kode_wilayah = 0;
							$kode_dusun = '00';
							$nik_KK = 0;
							foreach($data_kk->data as $key=>$rx){
								$rs = (array) $rx;
								// echo var_dump($rs);
								// 
								$kode_kecamatan = trim($rs['no_kec']) * 10;
								$kode_desa = trim($rs['no_prop']).trim($rs['no_kab']).trim(str_pad($kode_kecamatan,3,'0',STR_PAD_LEFT)).substr(trim($rs['no_kel']),-3);
								// echo "KODE DESA :".$kode_desa." \n";

								$kode_rt_rw = str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT).str_pad(trim($rs['rt']),3,'0',STR_PAD_LEFT);

								// echo $kode_desa."?==?".$kode_wilayah." -> ".substr($kode_wilayah,0,10)." \n";


								if($fix_kode_wilayah == 0){

									$strSQLX = "SELECT kode FROM tweb_wilayah WHERE tingkat=5 AND nama='".trim(fixSQL($rs['alamat']))."' AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%'";
									// echo $strSQL;
									$query = $this->db->query($strSQLX);
									if($query){
										if($query->num_rows() > 0){
											// Nama Dusun Ketemu
											$dusun = $query->result_array()[0];
											$fix_kode_wilayah = $dusun['kode'].$kode_rt_rw;
											$kode_dusun = substr($fix_kode_wilayah,-2);
											$fix_kode_wilayah_capil = $kode_desa.$kode_dusun.$kode_rt_rw;
											$strSQLWilayahUpdate = "UPDATE tweb_wilayah SET kode_capil='".$fix_kode_wilayah_capil."' WHERE kode='".$fix_kode_wilayah."' ";
											$query = $this->db->query($strSQLWilayahUpdate);

										}else{
											$strSQLW = "SELECT MAX(kode) as maxcode FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%' LIMIT 1";
											// echo $strSQLW;
											$query = $this->db->query($strSQLW);
											if($query){
												if($query->num_rows() > 0){
													$dusun = $query->result_array()[0];
													// echo var_dump($dusun);
													$kode_dusun_new  = $dusun['maxcode']+1;
													$kode_dusun_new_rw  = $dusun['maxcode'].str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
													$kode_dusun_new_rt  = $dusun['maxcode'].$kode_rt_rw;
													$kode_dusun = substr($kode_dusun_new,-2);
													$kode_dusun_capil_new = $kode_desa .$kode_dusun;
													$kode_dusun_capil_new_rw  = $kode_dusun_capil_new.str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
													$kode_dusun_capil_new_rt  = $kode_dusun_capil_new.$kode_rt_rw;
													$fix_kode_wilayah_capil = $kode_dusun_capil_new_rt ;

													$strSQLWilayahBaru = "INSERT INTO tweb_wilayah(nama,kode,kode_capil,tingkat) VALUES
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new)."','".fixSQL($kode_dusun_capil_new)."',5),
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rw)."','".fixSQL($kode_dusun_capil_new_rw)."',6),
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rt)."','".fixSQL($kode_dusun_capil_new_rt)."',6);
													";
													// echo $strSQLWilayahBaru;
													$query = $this->db->query($strSQLWilayahBaru);
												}
											}
		
										}
									}
								}
								if(trim($rs['nama_lengkap'])==trim($rs['nama_kepala_keluarga'])) {
									$nik_KK = $rs['nik'];
								}
								// echo $key ."" . var_dump($rs);
								if($fix_kode_wilayah == 0){
									$fix_kode_wilayah = $fix_kode_wilayah_capil;
								}
								$strSQL .="('".fixSQL($fix_kode_wilayah)."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($idartbdt)."','".fixSQL($idbdt)."',
								'".fixSQL($ruta6)."','".fixSQL($nopesertapbdt)."','".fixSQL($nopesertapbdtart)."',
								'".fixSQL($rs['nik'])."','".fixSQL($rs['nama_lengkap'])."','".fixSQL($rs['alamat'])."','".fixSQL($rs['nomor_kk'])."',
								'".fixSQL($hub_krt)."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['tmpt_lahir'])."','".fixSQL($rs['tgl_lahir'])."','".fixSQL($rs['jenis_kelamin'])."',1,'".date('Y-m-d H:i:s')."','".fixSQL($rs['nama_lengkap'])."',
								'".fixSQL($rs['nama_prop'])."','".fixSQL($rs['no_prop'])."','".fixSQL($rs['nama_kab'])."','".fixSQL($rs['no_kab'])."','".fixSQL($rs['nama_kec'])."','".fixSQL($rs['no_kec'])."','".fixSQL($rs['nama_kel'])."','".fixSQL($rs['no_kel'])."',
								'".fixSQL($rs['tmpt_lahir'])."','".fixSQL($rs['tgl_lahir'])."','".fixSQL($rs['jenis_kelamin'])."','".fixSQL($rs['gol_drh'])."',
								'".fixSQL($rs['alamat'])."','".fixSQL($rs['agama'])."','".fixSQL($rs['pekerjaan'])."','".fixSQL($rs['nama_ibu'])."','".fixSQL($rs['nama_ayah'])."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['pendidikan'])."','".fixSQL($rs['nomor_kk'])."','".fixSQL($rs['status_kawin'])."','".fixSQL($rs['no_akta_lahir'])."','".fixSQL($rs['nama_kepala_keluarga'])."','".fixSQL($rs['kode_jenis_kelamin'])."','".fixSQL($rs['kode_golongan_drh'])."','".fixSQL($rs['kode_agama'])."','".fixSQL($rs['kode_pekerjaan'])."','".fixSQL($rs['kode_pendidikan'])."','".fixSQL($rs['kode_hubungan_keluarga'])."','".fixSQL($rs['kode_status_kawin'])."'
								),";
							}
							$strSQL = trim($strSQL);
							$strSQL = (substr($strSQL,-1)==",") ? substr($strSQL,0,-1).";":$strSQL;
							// INSERT Data Penduduk dalam 1 KK 
							$query = $this->db->query($strSQL);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT PENDUDUK dari KK".$rs['nomor_kk']." \n";
							}else{
								// echo "<pre>".$strSQL."</pre>";
							}
							// INSERT KK 
							$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`,`kode_wilayah_capil`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `capil_status`, `capil_at`, `idbdt`) 
							VALUES('".$fix_kode_wilayah."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($rs['nomor_kk'])."','".fixSQL($nik_KK)."','".fixSQL($rs['nama_kepala_keluarga'])."','".date('Y-m-d H:i:s')."',1,'".date('Y-m-d H:i:s')."','".fixSQL($idbdt)."')";
							$query = $this->db->query($strSQLKK);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}
							// UPDATE data BDT 
							$strSQLBDT = "UPDATE bdt_rts SET kode_wilayah='".$fix_kode_wilayah."',kode_wilayah_capil='".$fix_kode_wilayah_capil."' WHERE idbdt='".fixSQL($idbdt)."'";
							$query = $this->db->query($strSQLBDT);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}
							$strSQLBDTIDV = "UPDATE bdt_idv SET capil_status=1, capil_date='". date('Y-m-d H:i:s')."', kode_wilayah='".$fix_kode_wilayah."',kode_wilayah_capil='".$fix_kode_wilayah_capil."' WHERE idbdt='".fixSQL($idbdt)."'";
							$query = $this->db->query($strSQLBDTIDV);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}
							$strSQLHasil = "INSERT INTO bct_capil_log(`idartbdt`, `hasil`, `keterangan`) VALUE('".$idartbdt."',2,'Tidak Terdaftar di CAPIL');";
							$query = $this->db->query($strSQLHasil);
							if($query){
							}

						}else{
							
							
							$strSQLHasil = "UPDATE bdt_idv SET capil_status=2, capil_date='". date('Y-m-d H:i:s')."' WHERE lead_id='".$lead_id."'";
							$query = $this->db->query($strSQLHasil);
							if($query){
								$strSQLHasil = "INSERT INTO bct_capil_log(`idartbdt`, `hasil`, `keterangan`) VALUE('".$idartbdt."',2,'Tidak Terdaftar di CAPIL');";
								$query = $this->db->query($strSQLHasil);
								echo "OK Data ART ".$idartbdt ." :: GAGAL ".$lead_id;

							}else{
								echo $query->error();
							}
		
						}
					}else{
						echo "HASIL NOT OK".$lead_id;
					}

	
				}
			}
	
		}
		// $this->output->enable_profiler(TRUE);
	}

	function bot2019_desa($varDesa = '',$debug=''){
		$kode_base = substr($varDesa,0,4);
		$kode_no_kab = substr($kode_base,-2);

		// PRODUCTION
		

		$strSQL = "SELECT b.`lead_id`,b.`nik`,b.`nokk`,b.`idartbdt`,b.`idbdt`,b.`kode_wilayah`,b.`ruta6`, b.`nopesertapbdt`, b.`nopesertapbdtart`,b.`hub_krt` 
		FROM bdt_idv b WHERE 
		((b.`kdkab`='".$kode_no_kab."') AND (b.`kode_wilayah`LIKE'".$varDesa."%') AND (b.`periode_id`=4) AND (b.`capil_status`=0))
		LIMIT 1 ";
		if($debug){
			echo var_dump($strSQL);
		}

		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				// ambil satu record utk ceck berikut nya 
				$lead_id = $rs['lead_id'];
				$kode_wilayah = $rs['kode_wilayah'];
				$kode_wilayah_desa_bdt = substr($kode_wilayah,0,10);
				$idartbdt = $rs['idartbdt'];
				$idbdt = $rs['idbdt'];
				$ruta6  = $rs['ruta6'];
				$nopesertapbdt = $rs['nopesertapbdt'];
				$nopesertapbdtart = $rs['nopesertapbdtart'];
				$hub_krt = $rs['hub_krt'];


				// check ke WS dukcapil 
				$capil = $this->dukcapil_by_nik($rs['nik']);
				if($debug){
					echo var_dump($capil);
				}

				if($capil['status']=='OK'){
					if(is_array($capil['data']->data)){

						echo date("Y-m-d H:i:s")." : PROSES CAPIL ".$rs['nik']."\n";

						foreach($capil['data'] as $key=>$crs){
							$data_idv = $crs;
						}
						$data_kk = $this->dukcapil_by_nokk($data_idv[0]->nomor_kk);
						if($debug){
							echo var_dump($data_kk);
						}
	

						if($data_kk->data){
							$strSQLInsert = "INSERT INTO tweb_penduduk(`kode_wilayah`,`kode_wilayah_capil`, `idartbdt`, `idbdt`, 
							`ruta6`, `nopesertapbdt`, `nopesertapbdtart`, 
							`nik`, `nama`, `alamat`, `kk_nomor`, 
							`rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`,`nama_lengkap`, 
							`nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, 
							`tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, 
							`alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, 
							`nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, 
							`no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, 
							`kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, 
							`kode_status_kawin`) VALUES";
	
							$fix_kode_wilayah = 0;
							$kode_dusun = '00';
							$nik_KK = 0;
	
							foreach($data_kk->data as $key=>$rx){
								$rs = (array) $rx;
								// echo var_dump($rs);
								// 
								$kode_kecamatan = trim($rs['no_kec']) * 10;
								$kode_desa = trim($rs['no_prop']).trim($rs['no_kab']).trim(str_pad($kode_kecamatan,3,'0',STR_PAD_LEFT)).substr(trim($rs['no_kel']),-3);
								// echo "KODE DESA :".$kode_desa." \n";
	
								$kode_rt_rw = str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT).str_pad(trim($rs['rt']),3,'0',STR_PAD_LEFT);
	
								// echo $kode_desa."?==?".$kode_wilayah." -> ".substr($kode_wilayah,0,10)." \n";
	
	
								if($fix_kode_wilayah == 0){
	
									$strSQLX = "SELECT kode FROM tweb_wilayah WHERE tingkat=5 AND nama='".trim(fixSQL($rs['alamat']))."' AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%'";
									// echo $strSQL;
									$query = $this->db->query($strSQLX);
									if($query){
										if($query->num_rows() > 0){
											// Nama Dusun Ketemu
											$dusun = $query->result_array()[0];
											$fix_kode_wilayah = $dusun['kode'].$kode_rt_rw;
											$kode_dusun = substr($fix_kode_wilayah,-2);
											$fix_kode_wilayah_capil = $kode_desa.$kode_dusun.$kode_rt_rw;
											$strSQLWilayahUpdate = "UPDATE tweb_wilayah SET kode_capil='".$fix_kode_wilayah_capil."' WHERE kode='".$fix_kode_wilayah."' ";
											$query = $this->db->query($strSQLWilayahUpdate);
	
										}else{
											$strSQLW = "SELECT MAX(kode) as maxcode FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%' LIMIT 1";
											// echo $strSQLW;
											$query = $this->db->query($strSQLW);
											if($query){
												if($query->num_rows() > 0){
													$dusun = $query->result_array()[0];
													// echo var_dump($dusun);
													$kode_dusun_new  = $dusun['maxcode']+1;
													$kode_dusun_new_rw  = $dusun['maxcode'].str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
													$kode_dusun_new_rt  = $dusun['maxcode'].$kode_rt_rw;
													$kode_dusun = substr($kode_dusun_new,-2);
													$kode_dusun_capil_new = $kode_desa .$kode_dusun;
													$kode_dusun_capil_new_rw  = $kode_dusun_capil_new.str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
													$kode_dusun_capil_new_rt  = $kode_dusun_capil_new.$kode_rt_rw;
													$fix_kode_wilayah_capil = $kode_dusun_capil_new_rt ;
	
													$strSQLWilayahBaru = "INSERT INTO tweb_wilayah(nama,kode,kode_capil,tingkat) VALUES
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new)."','".fixSQL($kode_dusun_capil_new)."',5),
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rw)."','".fixSQL($kode_dusun_capil_new_rw)."',6),
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rt)."','".fixSQL($kode_dusun_capil_new_rt)."',6);
													";
													// echo $strSQLWilayahBaru;
													$query = $this->db->query($strSQLWilayahBaru);
												}
											}
		
										}
									}
								}
								if(trim($rs['nama_lengkap'])==trim($rs['nama_kepala_keluarga'])) {
									$nik_KK = $rs['nik'];
								}
								// echo $key ."" . var_dump($rs);
								if($fix_kode_wilayah == 0){
									$fix_kode_wilayah = $fix_kode_wilayah_capil;
								}
								$dt = explode("-",trim($rs['tgl_lahir']));
								$dtlahir = $dt[2]."-".$dt[1]."-".$dt[0];
	
								$strSQLPenduduk = $strSQLInsert ."('".fixSQL($fix_kode_wilayah)."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($idartbdt)."','".fixSQL($idbdt)."',
								'".fixSQL($ruta6)."','".fixSQL($nopesertapbdt)."','".fixSQL($nopesertapbdtart)."',
								'".fixSQL($rs['nik'])."','".fixSQL($rs['nama_lengkap'])."','".fixSQL($rs['alamat'])."','".fixSQL($rs['nomor_kk'])."',
								'".fixSQL($hub_krt)."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."',1,'".date('Y-m-d H:i:s')."','".fixSQL($rs['nama_lengkap'])."',
								'".fixSQL($rs['nama_prop'])."','".fixSQL($rs['no_prop'])."','".fixSQL($rs['nama_kab'])."','".fixSQL($rs['no_kab'])."','".fixSQL($rs['nama_kec'])."','".fixSQL($rs['no_kec'])."','".fixSQL($rs['nama_kel'])."','".fixSQL($rs['no_kel'])."',
								'".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."','".fixSQL($rs['gol_drh'])."',
								'".fixSQL($rs['alamat'])."','".fixSQL($rs['agama'])."','".fixSQL($rs['pekerjaan'])."','".fixSQL($rs['nama_ibu'])."','".fixSQL($rs['nama_ayah'])."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['pendidikan'])."','".fixSQL($rs['nomor_kk'])."','".fixSQL($rs['status_kawin'])."','".fixSQL($rs['no_akta_lahir'])."','".fixSQL($rs['nama_kepala_keluarga'])."','".fixSQL($rs['kode_jenis_kelamin'])."','".fixSQL($rs['kode_golongan_drh'])."','".fixSQL($rs['kode_agama'])."','".fixSQL($rs['kode_pekerjaan'])."','".fixSQL($rs['kode_pendidikan'])."','".fixSQL($rs['kode_hubungan_keluarga'])."','".fixSQL($rs['kode_status_kawin'])."'
								) ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), idartbdt=VALUES(idartbdt), 
								idbdt=VALUES(idbdt), ruta6=VALUES(ruta6), nopesertapbdt=VALUES(nopesertapbdt), nopesertapbdtart=VALUES(nopesertapbdtart), nik=VALUES(nik), nama=VALUES(nama), 
								alamat=VALUES(alamat), kk_nomor=VALUES(kk_nomor), rt_hubungan=VALUES(rt_hubungan), kk_hubungan=VALUES(kk_hubungan), tlahir=VALUES(tlahir), dtlahir=VALUES(dtlahir), 
								jnskel=VALUES(jnskel), capil_status=VALUES(capil_status), capil_status_at=VALUES(capil_status_at), nama_lengkap=VALUES(nama_lengkap), nama_prop=VALUES(nama_prop), 
								no_prop=VALUES(no_prop), nama_kab=VALUES(nama_kab), no_kab=VALUES(no_kab), nama_kec=VALUES(nama_kec), no_kec=VALUES(no_kec), 
								nama_kel=VALUES(nama_kel), no_kel=VALUES(no_kel), tmpt_lahir=VALUES(tmpt_lahir), tgl_lahir=VALUES(tgl_lahir), jenis_kelamin=VALUES(jenis_kelamin), 
								gol_drh=VALUES(gol_drh), alamat_capil=VALUES(alamat_capil), agama=VALUES(agama), pekerjaan=VALUES(pekerjaan), nama_ibu=VALUES(nama_ibu), 
								nama_ayah=VALUES(nama_ayah), hubungan_keluarga=VALUES(hubungan_keluarga), pendidikan=VALUES(pendidikan), nomor_kk=VALUES(nomor_kk), 
								status_kawin=VALUES(status_kawin), no_akta_lahir=VALUES(no_akta_lahir), nama_kepala_keluarga=VALUES(nama_kepala_keluarga), kode_jenis_kelamin=VALUES(kode_jenis_kelamin), 
								kode_golongan_drh=VALUES(kode_golongan_drh), kode_agama=VALUES(kode_agama), kode_pekerjaan=VALUES(kode_pekerjaan), kode_pendidikan=VALUES(kode_pendidikan), 
								kode_hubungan_keluarga=VALUES(kode_hubungan_keluarga), kode_status_kawin=VALUES(kode_status_kawin);";
								// INSERT Data Penduduk dalam 1 KK 
								// echo $strSQLPenduduk;
								$query = $this->db->query($strSQLPenduduk);
								if($query){
									echo date('Y-m-d H:i:s').": SUKSES INSERT PENDUDUK dari KK".$rs['nomor_kk']." \n";
								}else{
									// echo "<pre>".$strSQL."</pre>";
								}
	
							}
							// INSERT KK 
							$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`,`kode_wilayah_capil`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `capil_status`, `capil_at`, `idbdt`) 
							VALUES('".$fix_kode_wilayah."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($rs['nomor_kk'])."','".fixSQL($nik_KK)."','".fixSQL($rs['nama_kepala_keluarga'])."','".date('Y-m-d H:i:s')."',1,'".date('Y-m-d H:i:s')."','".fixSQL($idbdt)."') 
							ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), kk_nomor=VALUES(kk_nomor), kk_nik=VALUES(kk_nik), kk_nama_kepala=VALUES(kk_nama_kepala), created_at=VALUES(created_at), capil_status=VALUES(capil_status), capil_at=VALUES(capil_at), idbdt=VALUES(idbdt);";
							$query = $this->db->query($strSQLKK);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}

						}else{
							$rs = (array) $data_idv[0];
							// Data KK NULL 
							$strSQLInsert = "INSERT INTO tweb_penduduk(`kode_wilayah`,`kode_wilayah_capil`, `idartbdt`, `idbdt`, 
							`ruta6`, `nopesertapbdt`, `nopesertapbdtart`, 
							`nik`, `nama`, `alamat`, `kk_nomor`, 
							`rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`,`nama_lengkap`, 
							`nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, 
							`tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, 
							`alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, 
							`nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, 
							`no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, 
							`kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, 
							`kode_status_kawin`) VALUES";
	
							$fix_kode_wilayah = 0;
							$kode_dusun = '00';
							$nik_KK = 0;
							$kode_kecamatan = trim($rs['no_kec']) * 10;
							$kode_desa = trim($rs['no_prop']).trim($rs['no_kab']).trim(str_pad($kode_kecamatan,3,'0',STR_PAD_LEFT)).substr(trim($rs['no_kel']),-3);
							// echo "KODE DESA :".$kode_desa." \n";

							$kode_rt_rw = str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT).str_pad(trim($rs['rt']),3,'0',STR_PAD_LEFT);

							// echo $kode_desa."?==?".$kode_wilayah." -> ".substr($kode_wilayah,0,10)." \n";


							if($fix_kode_wilayah == 0){

								$strSQLX = "SELECT kode FROM tweb_wilayah WHERE tingkat=5 AND nama='".trim(fixSQL($rs['alamat']))."' AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%'";
								// echo $strSQL;
								$query = $this->db->query($strSQLX);
								if($query){
									if($query->num_rows() > 0){
										// Nama Dusun Ketemu
										$dusun = $query->result_array()[0];
										$fix_kode_wilayah = $dusun['kode'].$kode_rt_rw;
										$kode_dusun = substr($fix_kode_wilayah,-2);
										$fix_kode_wilayah_capil = $kode_desa.$kode_dusun.$kode_rt_rw;
										$strSQLWilayahUpdate = "UPDATE tweb_wilayah SET kode_capil='".$fix_kode_wilayah_capil."' WHERE kode='".$fix_kode_wilayah."' ";
										$query = $this->db->query($strSQLWilayahUpdate);

									}else{
										$strSQLW = "SELECT MAX(kode) as maxcode FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%' LIMIT 1";
										// echo $strSQLW;
										$query = $this->db->query($strSQLW);
										if($query){
											if($query->num_rows() > 0){
												$dusun = $query->result_array()[0];
												// echo var_dump($dusun);
												$kode_dusun_new  = $dusun['maxcode']+1;
												$kode_dusun_new_rw  = $dusun['maxcode'].str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
												$kode_dusun_new_rt  = $dusun['maxcode'].$kode_rt_rw;
												$kode_dusun = substr($kode_dusun_new,-2);
												$kode_dusun_capil_new = $kode_desa .$kode_dusun;
												$kode_dusun_capil_new_rw  = $kode_dusun_capil_new.str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
												$kode_dusun_capil_new_rt  = $kode_dusun_capil_new.$kode_rt_rw;
												$fix_kode_wilayah_capil = $kode_dusun_capil_new_rt ;

												$strSQLWilayahBaru = "INSERT INTO tweb_wilayah(nama,kode,kode_capil,tingkat) VALUES
													('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new)."','".fixSQL($kode_dusun_capil_new)."',5),
													('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rw)."','".fixSQL($kode_dusun_capil_new_rw)."',6),
													('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rt)."','".fixSQL($kode_dusun_capil_new_rt)."',6);
												";
												// echo $strSQLWilayahBaru;
												$query = $this->db->query($strSQLWilayahBaru);
											}
										}
	
									}
								}
							}
							if(trim($rs['nama_lengkap'])==trim($rs['nama_kepala_keluarga'])) {
								$nik_KK = $rs['nik'];
							}
							// echo $key ."" . var_dump($rs);
							if($fix_kode_wilayah == 0){
								$fix_kode_wilayah = $fix_kode_wilayah_capil;
							}
							$dt = explode("-",trim($rs['tgl_lahir']));
							$dtlahir = $dt[2]."-".$dt[1]."-".$dt[0];

							$strSQLPenduduk = $strSQLInsert ."('".fixSQL($fix_kode_wilayah)."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($idartbdt)."','".fixSQL($idbdt)."',
							'".fixSQL($ruta6)."','".fixSQL($nopesertapbdt)."','".fixSQL($nopesertapbdtart)."',
							'".fixSQL($rs['nik'])."','".fixSQL($rs['nama_lengkap'])."','".fixSQL($rs['alamat'])."','".fixSQL($rs['nomor_kk'])."',
							'".fixSQL($hub_krt)."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."',1,'".date('Y-m-d H:i:s')."','".fixSQL($rs['nama_lengkap'])."',
							'".fixSQL($rs['nama_prop'])."','".fixSQL($rs['no_prop'])."','".fixSQL($rs['nama_kab'])."','".fixSQL($rs['no_kab'])."','".fixSQL($rs['nama_kec'])."','".fixSQL($rs['no_kec'])."','".fixSQL($rs['nama_kel'])."','".fixSQL($rs['no_kel'])."',
							'".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."','".fixSQL($rs['gol_drh'])."',
							'".fixSQL($rs['alamat'])."','".fixSQL($rs['agama'])."','".fixSQL($rs['pekerjaan'])."','".fixSQL($rs['nama_ibu'])."','".fixSQL($rs['nama_ayah'])."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['pendidikan'])."','".fixSQL($rs['nomor_kk'])."','".fixSQL($rs['status_kawin'])."','".fixSQL($rs['no_akta_lahir'])."','".fixSQL($rs['nama_kepala_keluarga'])."','".fixSQL($rs['kode_jenis_kelamin'])."','".fixSQL($rs['kode_golongan_drh'])."','".fixSQL($rs['kode_agama'])."','".fixSQL($rs['kode_pekerjaan'])."','".fixSQL($rs['kode_pendidikan'])."','".fixSQL($rs['kode_hubungan_keluarga'])."','".fixSQL($rs['kode_status_kawin'])."'
							) ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), idartbdt=VALUES(idartbdt), 
							idbdt=VALUES(idbdt), ruta6=VALUES(ruta6), nopesertapbdt=VALUES(nopesertapbdt), nopesertapbdtart=VALUES(nopesertapbdtart), nik=VALUES(nik), nama=VALUES(nama), 
							alamat=VALUES(alamat), kk_nomor=VALUES(kk_nomor), rt_hubungan=VALUES(rt_hubungan), kk_hubungan=VALUES(kk_hubungan), tlahir=VALUES(tlahir), dtlahir=VALUES(dtlahir), 
							jnskel=VALUES(jnskel), capil_status=VALUES(capil_status), capil_status_at=VALUES(capil_status_at), nama_lengkap=VALUES(nama_lengkap), nama_prop=VALUES(nama_prop), 
							no_prop=VALUES(no_prop), nama_kab=VALUES(nama_kab), no_kab=VALUES(no_kab), nama_kec=VALUES(nama_kec), no_kec=VALUES(no_kec), 
							nama_kel=VALUES(nama_kel), no_kel=VALUES(no_kel), tmpt_lahir=VALUES(tmpt_lahir), tgl_lahir=VALUES(tgl_lahir), jenis_kelamin=VALUES(jenis_kelamin), 
							gol_drh=VALUES(gol_drh), alamat_capil=VALUES(alamat_capil), agama=VALUES(agama), pekerjaan=VALUES(pekerjaan), nama_ibu=VALUES(nama_ibu), 
							nama_ayah=VALUES(nama_ayah), hubungan_keluarga=VALUES(hubungan_keluarga), pendidikan=VALUES(pendidikan), nomor_kk=VALUES(nomor_kk), 
							status_kawin=VALUES(status_kawin), no_akta_lahir=VALUES(no_akta_lahir), nama_kepala_keluarga=VALUES(nama_kepala_keluarga), kode_jenis_kelamin=VALUES(kode_jenis_kelamin), 
							kode_golongan_drh=VALUES(kode_golongan_drh), kode_agama=VALUES(kode_agama), kode_pekerjaan=VALUES(kode_pekerjaan), kode_pendidikan=VALUES(kode_pendidikan), 
							kode_hubungan_keluarga=VALUES(kode_hubungan_keluarga), kode_status_kawin=VALUES(kode_status_kawin);";
							// INSERT Data Penduduk dalam 1 KK 
							// echo $strSQLPenduduk;
							$query = $this->db->query($strSQLPenduduk);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT PENDUDUK dari KK".$rs['nomor_kk']." \n";
							}else{
								// echo "<pre>".$strSQL."</pre>";
							}

							// INSERT KK 
							$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`,`kode_wilayah_capil`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `capil_status`, `capil_at`, `idbdt`) 
							VALUES('".$fix_kode_wilayah."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($rs['nomor_kk'])."','".fixSQL($nik_KK)."','".fixSQL($rs['nama_kepala_keluarga'])."','".date('Y-m-d H:i:s')."',2,'".date('Y-m-d H:i:s')."','".fixSQL($idbdt)."') 
							ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), kk_nomor=VALUES(kk_nomor), kk_nik=VALUES(kk_nik), kk_nama_kepala=VALUES(kk_nama_kepala), created_at=VALUES(created_at), capil_status=VALUES(capil_status), capil_at=VALUES(capil_at), idbdt=VALUES(idbdt);";
							$query = $this->db->query($strSQLKK);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}
						}
						// UPDATE data BDT 
						$strSQLBDT = "UPDATE bdt_rts SET kode_wilayah='".$fix_kode_wilayah."',kode_wilayah_capil='".$fix_kode_wilayah_capil."' WHERE idbdt='".fixSQL($idbdt)."'";
						$query = $this->db->query($strSQLBDT);
						if($query){
							echo date('Y-m-d H:i:s').": UPDATE BDT RTS IDBDT=".$idbdt." \n";
						}else{
							echo date('Y-m-d H:i:s').": ERROR UPDATE BDT_IDV IDBDT=".$idbdt." \n";
						}
						$strSQLBDTIDV = "UPDATE bdt_idv SET capil_status=1, capil_date='". date('Y-m-d H:i:s')."', kode_wilayah='".$fix_kode_wilayah."',kode_wilayah_capil='".$fix_kode_wilayah_capil."' WHERE idbdt='".fixSQL($idbdt)."'";
						$query = $this->db->query($strSQLBDTIDV);
						if($query){
							echo date('Y-m-d H:i:s').": UPDATE BDT_IDV IDBDT=".$idbdt." \n";
						}else{
							echo date('Y-m-d H:i:s').": ERROR UPDATE BDT_IDV IDBDT=".$idbdt." \n";
						}
						$strSQLHasil = "INSERT INTO bct_capil_log(`idartbdt`, `hasil`, `keterangan`) VALUE('".$idartbdt."',2,'Tidak Terdaftar di CAPIL') ;";
						$query = $this->db->query($strSQLHasil);
						if($query){
							echo "OK Data ART ".$idartbdt ." :: SUKSES ".$lead_id."\n";
							echo "=============================================== \n ";
						}

					}else{
						
						
						$strSQLHasil = "UPDATE bdt_idv SET capil_status=2, capil_date='". date('Y-m-d H:i:s')."' WHERE lead_id='".$lead_id."'";
						$query = $this->db->query($strSQLHasil);
						if($query){
							$strSQLHasil = "INSERT INTO bct_capil_log(`idartbdt`, `hasil`, `keterangan`) VALUE('".$idartbdt."',2,'Tidak Terdaftar di CAPIL');";
							$query = $this->db->query($strSQLHasil);
							echo "\nOK Data ART ".$idartbdt ." :: GAGAL ".$lead_id;

						}else{
							echo $query->error();
						}
	
					}
				}else{
					echo date("Y-m-d H:i:s").": HASIL NOT OK".$lead_id;
				}
			}else{
				echo date("Y-m-d H:i:s").": SELESAI";	
			}
		}else{
			echo date("Y-m-d H:i:s").": QUERY KE BDT FGAGAL";
		}
		// $this->output->enable_profiler(TRUE);
	}

	function update_capil_by_idartbdt($varID='',$debug=false){
		$strSQL = "SELECT b.`lead_id`,b.`nik`,b.`nokk`,b.`idartbdt`,b.`idbdt`,b.`kode_wilayah`,b.`ruta6`, b.`nopesertapbdt`, b.`nopesertapbdtart`,b.`hub_krt` 
		FROM bdt_idv b WHERE b.idartbdt='".$varID."' AND (b.`periode_id`=4) LIMIT 1";

		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				// ambil satu record utk ceck berikut nya 
				$lead_id = $rs['lead_id'];
				$kode_wilayah = $rs['kode_wilayah'];
				$kode_wilayah_desa_bdt = substr($kode_wilayah,0,10);
				$idartbdt = $rs['idartbdt'];
				$idbdt = $rs['idbdt'];
				$ruta6  = $rs['ruta6'];
				$nopesertapbdt = $rs['nopesertapbdt'];
				$nopesertapbdtart = $rs['nopesertapbdtart'];
				$hub_krt = $rs['hub_krt'];


				// check ke WS dukcapil 
				$capil = $this->dukcapil_by_nik($rs['nik']);
				if($debug){
					echo var_dump($capil);
				}

				if($capil['status']=='OK'){
					if(is_array($capil['data']->data)){

						echo date("Y-m-d H:i:s")." : PROSES CAPIL ".$rs['nik']."\n";

						foreach($capil['data'] as $key=>$crs){
							$data_idv = $crs;
						}

						$data_kk = $this->dukcapil_by_nokk($data_idv[0]->nomor_kk);
						if($debug){
							echo var_dump($data_kk);
						}
	
						if($data_kk->data){
							$strSQLInsert = "INSERT INTO tweb_penduduk(`kode_wilayah`,`kode_wilayah_capil`, `idartbdt`, `idbdt`, 
							`ruta6`, `nopesertapbdt`, `nopesertapbdtart`, 
							`nik`, `nama`, `alamat`, `kk_nomor`, 
							`rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`,`nama_lengkap`, 
							`nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, 
							`tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, 
							`alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, 
							`nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, 
							`no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, 
							`kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, 
							`kode_status_kawin`) VALUES";
	
							$fix_kode_wilayah = 0;
							$kode_dusun = '00';
							$nik_KK = 0;
	
							foreach($data_kk->data as $key=>$rx){
								$rs = (array) $rx;
								// echo var_dump($rs);
								// 
								$kode_kecamatan = trim($rs['no_kec']) * 10;
								$kode_desa = trim($rs['no_prop']).trim($rs['no_kab']).trim(str_pad($kode_kecamatan,3,'0',STR_PAD_LEFT)).substr(trim($rs['no_kel']),-3);
								// echo "KODE DESA :".$kode_desa." \n";
	
								$kode_rt_rw = str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT).str_pad(trim($rs['rt']),3,'0',STR_PAD_LEFT);
	
								// echo $kode_desa."?==?".$kode_wilayah." -> ".substr($kode_wilayah,0,10)." \n";
	
	
								if($fix_kode_wilayah == 0){
	
									$strSQLX = "SELECT kode FROM tweb_wilayah WHERE tingkat=5 AND nama='".trim(fixSQL($rs['alamat']))."' AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%'";
									// echo $strSQL;
									$query = $this->db->query($strSQLX);
									if($query){
										if($query->num_rows() > 0){
											// Nama Dusun Ketemu
											$dusun = $query->result_array()[0];
											$fix_kode_wilayah = $dusun['kode'].$kode_rt_rw;
											$kode_dusun = substr($fix_kode_wilayah,-2);
											$fix_kode_wilayah_capil = $kode_desa.$kode_dusun.$kode_rt_rw;
											$strSQLWilayahUpdate = "UPDATE tweb_wilayah SET kode_capil='".$fix_kode_wilayah_capil."' WHERE kode='".$fix_kode_wilayah."' ";
											$query = $this->db->query($strSQLWilayahUpdate);
	
										}else{
											$strSQLW = "SELECT MAX(kode) as maxcode FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%' LIMIT 1";
											// echo $strSQLW;
											$query = $this->db->query($strSQLW);
											if($query){
												if($query->num_rows() > 0){
													$dusun = $query->result_array()[0];
													// echo var_dump($dusun);
													$kode_dusun_new  = $dusun['maxcode']+1;
													$kode_dusun_new_rw  = $dusun['maxcode'].str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
													$kode_dusun_new_rt  = $dusun['maxcode'].$kode_rt_rw;
													$kode_dusun = substr($kode_dusun_new,-2);
													$kode_dusun_capil_new = $kode_desa .$kode_dusun;
													$kode_dusun_capil_new_rw  = $kode_dusun_capil_new.str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
													$kode_dusun_capil_new_rt  = $kode_dusun_capil_new.$kode_rt_rw;
													$fix_kode_wilayah_capil = $kode_dusun_capil_new_rt ;
	
													$strSQLWilayahBaru = "INSERT INTO tweb_wilayah(nama,kode,kode_capil,tingkat) VALUES
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new)."','".fixSQL($kode_dusun_capil_new)."',5),
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rw)."','".fixSQL($kode_dusun_capil_new_rw)."',6),
														('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rt)."','".fixSQL($kode_dusun_capil_new_rt)."',6);
													";
													// echo $strSQLWilayahBaru;
													$query = $this->db->query($strSQLWilayahBaru);
												}
											}
		
										}
									}
								}
								if(trim($rs['nama_lengkap'])==trim($rs['nama_kepala_keluarga'])) {
									$nik_KK = $rs['nik'];
								}
								// echo $key ."" . var_dump($rs);
								if($fix_kode_wilayah == 0){
									$fix_kode_wilayah = $fix_kode_wilayah_capil;
								}
								$dt = explode("-",trim($rs['tgl_lahir']));
								$dtlahir = $dt[2]."-".$dt[1]."-".$dt[0];
	
								$strSQLPenduduk = $strSQLInsert ."('".fixSQL($fix_kode_wilayah)."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($idartbdt)."','".fixSQL($idbdt)."',
								'".fixSQL($ruta6)."','".fixSQL($nopesertapbdt)."','".fixSQL($nopesertapbdtart)."',
								'".fixSQL($rs['nik'])."','".fixSQL($rs['nama_lengkap'])."','".fixSQL($rs['alamat'])."','".fixSQL($rs['nomor_kk'])."',
								'".fixSQL($hub_krt)."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."',1,'".date('Y-m-d H:i:s')."','".fixSQL($rs['nama_lengkap'])."',
								'".fixSQL($rs['nama_prop'])."','".fixSQL($rs['no_prop'])."','".fixSQL($rs['nama_kab'])."','".fixSQL($rs['no_kab'])."','".fixSQL($rs['nama_kec'])."','".fixSQL($rs['no_kec'])."','".fixSQL($rs['nama_kel'])."','".fixSQL($rs['no_kel'])."',
								'".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."','".fixSQL($rs['gol_drh'])."',
								'".fixSQL($rs['alamat'])."','".fixSQL($rs['agama'])."','".fixSQL($rs['pekerjaan'])."','".fixSQL($rs['nama_ibu'])."','".fixSQL($rs['nama_ayah'])."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['pendidikan'])."','".fixSQL($rs['nomor_kk'])."','".fixSQL($rs['status_kawin'])."','".fixSQL($rs['no_akta_lahir'])."','".fixSQL($rs['nama_kepala_keluarga'])."','".fixSQL($rs['kode_jenis_kelamin'])."','".fixSQL($rs['kode_golongan_drh'])."','".fixSQL($rs['kode_agama'])."','".fixSQL($rs['kode_pekerjaan'])."','".fixSQL($rs['kode_pendidikan'])."','".fixSQL($rs['kode_hubungan_keluarga'])."','".fixSQL($rs['kode_status_kawin'])."'
								) ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), idartbdt=VALUES(idartbdt), 
								idbdt=VALUES(idbdt), ruta6=VALUES(ruta6), nopesertapbdt=VALUES(nopesertapbdt), nopesertapbdtart=VALUES(nopesertapbdtart), nik=VALUES(nik), nama=VALUES(nama), 
								alamat=VALUES(alamat), kk_nomor=VALUES(kk_nomor), rt_hubungan=VALUES(rt_hubungan), kk_hubungan=VALUES(kk_hubungan), tlahir=VALUES(tlahir), dtlahir=VALUES(dtlahir), 
								jnskel=VALUES(jnskel), capil_status=VALUES(capil_status), capil_status_at=VALUES(capil_status_at), nama_lengkap=VALUES(nama_lengkap), nama_prop=VALUES(nama_prop), 
								no_prop=VALUES(no_prop), nama_kab=VALUES(nama_kab), no_kab=VALUES(no_kab), nama_kec=VALUES(nama_kec), no_kec=VALUES(no_kec), 
								nama_kel=VALUES(nama_kel), no_kel=VALUES(no_kel), tmpt_lahir=VALUES(tmpt_lahir), tgl_lahir=VALUES(tgl_lahir), jenis_kelamin=VALUES(jenis_kelamin), 
								gol_drh=VALUES(gol_drh), alamat_capil=VALUES(alamat_capil), agama=VALUES(agama), pekerjaan=VALUES(pekerjaan), nama_ibu=VALUES(nama_ibu), 
								nama_ayah=VALUES(nama_ayah), hubungan_keluarga=VALUES(hubungan_keluarga), pendidikan=VALUES(pendidikan), nomor_kk=VALUES(nomor_kk), 
								status_kawin=VALUES(status_kawin), no_akta_lahir=VALUES(no_akta_lahir), nama_kepala_keluarga=VALUES(nama_kepala_keluarga), kode_jenis_kelamin=VALUES(kode_jenis_kelamin), 
								kode_golongan_drh=VALUES(kode_golongan_drh), kode_agama=VALUES(kode_agama), kode_pekerjaan=VALUES(kode_pekerjaan), kode_pendidikan=VALUES(kode_pendidikan), 
								kode_hubungan_keluarga=VALUES(kode_hubungan_keluarga), kode_status_kawin=VALUES(kode_status_kawin);";
								// INSERT Data Penduduk dalam 1 KK 
								// echo $strSQLPenduduk;
								$query = $this->db->query($strSQLPenduduk);
								if($query){
									echo date('Y-m-d H:i:s').": SUKSES INSERT PENDUDUK dari KK".$rs['nomor_kk']." \n";
								}else{
									// echo "<pre>".$strSQL."</pre>";
								}
	
							}
							// INSERT KK 
							$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`,`kode_wilayah_capil`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `capil_status`, `capil_at`, `idbdt`) 
							VALUES('".$fix_kode_wilayah."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($rs['nomor_kk'])."','".fixSQL($nik_KK)."','".fixSQL($rs['nama_kepala_keluarga'])."','".date('Y-m-d H:i:s')."',1,'".date('Y-m-d H:i:s')."','".fixSQL($idbdt)."') 
							ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), kk_nomor=VALUES(kk_nomor), kk_nik=VALUES(kk_nik), kk_nama_kepala=VALUES(kk_nama_kepala), created_at=VALUES(created_at), capil_status=VALUES(capil_status), capil_at=VALUES(capil_at), idbdt=VALUES(idbdt);";
							$query = $this->db->query($strSQLKK);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}

						}else{
							$rs = (array) $data_idv[0];
							// Data KK NULL 
							$strSQLInsert = "INSERT INTO tweb_penduduk(`kode_wilayah`,`kode_wilayah_capil`, `idartbdt`, `idbdt`, 
							`ruta6`, `nopesertapbdt`, `nopesertapbdtart`, 
							`nik`, `nama`, `alamat`, `kk_nomor`, 
							`rt_hubungan`, `kk_hubungan`, `tlahir`, `dtlahir`, `jnskel`, `capil_status`, `capil_status_at`,`nama_lengkap`, 
							`nama_prop`, `no_prop`, `nama_kab`, `no_kab`, `nama_kec`, `no_kec`, `nama_kel`, `no_kel`, 
							`tmpt_lahir`, `tgl_lahir`, `jenis_kelamin`, `gol_drh`, 
							`alamat_capil`, `agama`, `pekerjaan`, `nama_ibu`, 
							`nama_ayah`, `hubungan_keluarga`, `pendidikan`, `nomor_kk`, `status_kawin`, 
							`no_akta_lahir`, `nama_kepala_keluarga`, `kode_jenis_kelamin`, `kode_golongan_drh`, 
							`kode_agama`, `kode_pekerjaan`, `kode_pendidikan`, `kode_hubungan_keluarga`, 
							`kode_status_kawin`) VALUES";
	
							$fix_kode_wilayah = 0;
							$kode_dusun = '00';
							$nik_KK = 0;
							$kode_kecamatan = trim($rs['no_kec']) * 10;
							$kode_desa = trim($rs['no_prop']).trim($rs['no_kab']).trim(str_pad($kode_kecamatan,3,'0',STR_PAD_LEFT)).substr(trim($rs['no_kel']),-3);
							// echo "KODE DESA :".$kode_desa." \n";

							$kode_rt_rw = str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT).str_pad(trim($rs['rt']),3,'0',STR_PAD_LEFT);

							// echo $kode_desa."?==?".$kode_wilayah." -> ".substr($kode_wilayah,0,10)." \n";


							if($fix_kode_wilayah == 0){

								$strSQLX = "SELECT kode FROM tweb_wilayah WHERE tingkat=5 AND nama='".trim(fixSQL($rs['alamat']))."' AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%'";
								// echo $strSQL;
								$query = $this->db->query($strSQLX);
								if($query){
									if($query->num_rows() > 0){
										// Nama Dusun Ketemu
										$dusun = $query->result_array()[0];
										$fix_kode_wilayah = $dusun['kode'].$kode_rt_rw;
										$kode_dusun = substr($fix_kode_wilayah,-2);
										$fix_kode_wilayah_capil = $kode_desa.$kode_dusun.$kode_rt_rw;
										$strSQLWilayahUpdate = "UPDATE tweb_wilayah SET kode_capil='".$fix_kode_wilayah_capil."' WHERE kode='".$fix_kode_wilayah."' ";
										$query = $this->db->query($strSQLWilayahUpdate);

									}else{
										$strSQLW = "SELECT MAX(kode) as maxcode FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($kode_wilayah_desa_bdt)."%' LIMIT 1";
										// echo $strSQLW;
										$query = $this->db->query($strSQLW);
										if($query){
											if($query->num_rows() > 0){
												$dusun = $query->result_array()[0];
												// echo var_dump($dusun);
												$kode_dusun_new  = $dusun['maxcode']+1;
												$kode_dusun_new_rw  = $dusun['maxcode'].str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
												$kode_dusun_new_rt  = $dusun['maxcode'].$kode_rt_rw;
												$kode_dusun = substr($kode_dusun_new,-2);
												$kode_dusun_capil_new = $kode_desa .$kode_dusun;
												$kode_dusun_capil_new_rw  = $kode_dusun_capil_new.str_pad(trim($rs['rw']),3,'0',STR_PAD_LEFT);
												$kode_dusun_capil_new_rt  = $kode_dusun_capil_new.$kode_rt_rw;
												$fix_kode_wilayah_capil = $kode_dusun_capil_new_rt ;

												$strSQLWilayahBaru = "INSERT INTO tweb_wilayah(nama,kode,kode_capil,tingkat) VALUES
													('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new)."','".fixSQL($kode_dusun_capil_new)."',5),
													('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rw)."','".fixSQL($kode_dusun_capil_new_rw)."',6),
													('".trim($rs['alamat'])."','".fixSQL($kode_dusun_new_rt)."','".fixSQL($kode_dusun_capil_new_rt)."',6);
												";
												// echo $strSQLWilayahBaru;
												$query = $this->db->query($strSQLWilayahBaru);
											}
										}
	
									}
								}
							}
							if(trim($rs['nama_lengkap'])==trim($rs['nama_kepala_keluarga'])) {
								$nik_KK = $rs['nik'];
							}
							// echo $key ."" . var_dump($rs);
							if($fix_kode_wilayah == 0){
								$fix_kode_wilayah = $fix_kode_wilayah_capil;
							}
							$dt = explode("-",trim($rs['tgl_lahir']));
							$dtlahir = $dt[2]."-".$dt[1]."-".$dt[0];

							$strSQLPenduduk = $strSQLInsert ."('".fixSQL($fix_kode_wilayah)."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($idartbdt)."','".fixSQL($idbdt)."',
							'".fixSQL($ruta6)."','".fixSQL($nopesertapbdt)."','".fixSQL($nopesertapbdtart)."',
							'".fixSQL($rs['nik'])."','".fixSQL($rs['nama_lengkap'])."','".fixSQL($rs['alamat'])."','".fixSQL($rs['nomor_kk'])."',
							'".fixSQL($hub_krt)."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."',1,'".date('Y-m-d H:i:s')."','".fixSQL($rs['nama_lengkap'])."',
							'".fixSQL($rs['nama_prop'])."','".fixSQL($rs['no_prop'])."','".fixSQL($rs['nama_kab'])."','".fixSQL($rs['no_kab'])."','".fixSQL($rs['nama_kec'])."','".fixSQL($rs['no_kec'])."','".fixSQL($rs['nama_kel'])."','".fixSQL($rs['no_kel'])."',
							'".fixSQL($rs['tmpt_lahir'])."','".fixSQL($dtlahir)."','".fixSQL($rs['jenis_kelamin'])."','".fixSQL($rs['gol_drh'])."',
							'".fixSQL($rs['alamat'])."','".fixSQL($rs['agama'])."','".fixSQL($rs['pekerjaan'])."','".fixSQL($rs['nama_ibu'])."','".fixSQL($rs['nama_ayah'])."','".fixSQL($rs['hubungan_keluarga'])."','".fixSQL($rs['pendidikan'])."','".fixSQL($rs['nomor_kk'])."','".fixSQL($rs['status_kawin'])."','".fixSQL($rs['no_akta_lahir'])."','".fixSQL($rs['nama_kepala_keluarga'])."','".fixSQL($rs['kode_jenis_kelamin'])."','".fixSQL($rs['kode_golongan_drh'])."','".fixSQL($rs['kode_agama'])."','".fixSQL($rs['kode_pekerjaan'])."','".fixSQL($rs['kode_pendidikan'])."','".fixSQL($rs['kode_hubungan_keluarga'])."','".fixSQL($rs['kode_status_kawin'])."'
							) ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), idartbdt=VALUES(idartbdt), 
							idbdt=VALUES(idbdt), ruta6=VALUES(ruta6), nopesertapbdt=VALUES(nopesertapbdt), nopesertapbdtart=VALUES(nopesertapbdtart), nik=VALUES(nik), nama=VALUES(nama), 
							alamat=VALUES(alamat), kk_nomor=VALUES(kk_nomor), rt_hubungan=VALUES(rt_hubungan), kk_hubungan=VALUES(kk_hubungan), tlahir=VALUES(tlahir), dtlahir=VALUES(dtlahir), 
							jnskel=VALUES(jnskel), capil_status=VALUES(capil_status), capil_status_at=VALUES(capil_status_at), nama_lengkap=VALUES(nama_lengkap), nama_prop=VALUES(nama_prop), 
							no_prop=VALUES(no_prop), nama_kab=VALUES(nama_kab), no_kab=VALUES(no_kab), nama_kec=VALUES(nama_kec), no_kec=VALUES(no_kec), 
							nama_kel=VALUES(nama_kel), no_kel=VALUES(no_kel), tmpt_lahir=VALUES(tmpt_lahir), tgl_lahir=VALUES(tgl_lahir), jenis_kelamin=VALUES(jenis_kelamin), 
							gol_drh=VALUES(gol_drh), alamat_capil=VALUES(alamat_capil), agama=VALUES(agama), pekerjaan=VALUES(pekerjaan), nama_ibu=VALUES(nama_ibu), 
							nama_ayah=VALUES(nama_ayah), hubungan_keluarga=VALUES(hubungan_keluarga), pendidikan=VALUES(pendidikan), nomor_kk=VALUES(nomor_kk), 
							status_kawin=VALUES(status_kawin), no_akta_lahir=VALUES(no_akta_lahir), nama_kepala_keluarga=VALUES(nama_kepala_keluarga), kode_jenis_kelamin=VALUES(kode_jenis_kelamin), 
							kode_golongan_drh=VALUES(kode_golongan_drh), kode_agama=VALUES(kode_agama), kode_pekerjaan=VALUES(kode_pekerjaan), kode_pendidikan=VALUES(kode_pendidikan), 
							kode_hubungan_keluarga=VALUES(kode_hubungan_keluarga), kode_status_kawin=VALUES(kode_status_kawin);";
							// INSERT Data Penduduk dalam 1 KK 
							// echo $strSQLPenduduk;
							$query = $this->db->query($strSQLPenduduk);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT PENDUDUK dari KK".$rs['nomor_kk']." \n";
							}else{
								// echo "<pre>".$strSQL."</pre>";
							}

							// INSERT KK 
							$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`,`kode_wilayah_capil`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `capil_status`, `capil_at`, `idbdt`) 
							VALUES('".$fix_kode_wilayah."','".fixSQL($fix_kode_wilayah_capil)."','".fixSQL($rs['nomor_kk'])."','".fixSQL($nik_KK)."','".fixSQL($rs['nama_kepala_keluarga'])."','".date('Y-m-d H:i:s')."',2,'".date('Y-m-d H:i:s')."','".fixSQL($idbdt)."') 
							ON DUPLICATE KEY UPDATE kode_wilayah=VALUES(kode_wilayah), kode_wilayah_capil=VALUES(kode_wilayah_capil), kk_nomor=VALUES(kk_nomor), kk_nik=VALUES(kk_nik), kk_nama_kepala=VALUES(kk_nama_kepala), created_at=VALUES(created_at), capil_status=VALUES(capil_status), capil_at=VALUES(capil_at), idbdt=VALUES(idbdt);";
							$query = $this->db->query($strSQLKK);
							if($query){
								echo date('Y-m-d H:i:s').": SUKSES INSERT KK".$rs['nomor_kk']." \n";
							}
						}

						// UPDATE data BDT 
						$strSQLBDT = "UPDATE bdt_rts SET kode_wilayah='".$fix_kode_wilayah."',kode_wilayah_capil='".$fix_kode_wilayah_capil."' WHERE idbdt='".fixSQL($idbdt)."'";
						$query = $this->db->query($strSQLBDT);
						if($query){
							echo date('Y-m-d H:i:s').": UPDATE BDT RTS IDBDT=".$idbdt." \n";
						}else{
							echo date('Y-m-d H:i:s').": ERROR UPDATE BDT_IDV IDBDT=".$idbdt." \n";
						}
						$strSQLBDTIDV = "UPDATE bdt_idv SET capil_status=1, capil_date='". date('Y-m-d H:i:s')."', kode_wilayah='".$fix_kode_wilayah."',kode_wilayah_capil='".$fix_kode_wilayah_capil."' WHERE idbdt='".fixSQL($idbdt)."'";
						$query = $this->db->query($strSQLBDTIDV);
						if($query){
							echo date('Y-m-d H:i:s').": UPDATE BDT_IDV IDBDT=".$idbdt." \n";
						}else{
							echo date('Y-m-d H:i:s').": ERROR UPDATE BDT_IDV IDBDT=".$idbdt." \n";
						}
						$strSQLHasil = "INSERT INTO bct_capil_log(`idartbdt`, `hasil`, `keterangan`) VALUE('".$idartbdt."',2,'Tidak Terdaftar di CAPIL') ;";
						$query = $this->db->query($strSQLHasil);
						if($query){
							echo "OK Data ART ".$idartbdt ." :: SUKSES ".$lead_id."\n";
							echo "=============================================== \n ";
						}
					}else{
						
						
						$strSQLHasil = "UPDATE bdt_idv SET capil_status=2, capil_date='". date('Y-m-d H:i:s')."' WHERE lead_id='".$lead_id."'";
						$query = $this->db->query($strSQLHasil);
						if($query){
							$strSQLHasil = "INSERT INTO bct_capil_log(`idartbdt`, `hasil`, `keterangan`) VALUE('".$idartbdt."',2,'Tidak Terdaftar di CAPIL');";
							$query = $this->db->query($strSQLHasil);
							echo "OK Data ART ".$idartbdt ." :: GAGAL ".$lead_id;

						}else{
							echo $query->error();
						}
	
					}
				}else{
					echo "HASIL NOT OK".$lead_id;
				}


			}
		}		

	}
}
