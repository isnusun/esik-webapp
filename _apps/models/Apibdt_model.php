<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apibdt_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function do_query($strSQL = ''){
		$hasil = false;
		if($strSQL){
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$hasil = $query->result_array();
				}
			}else{
				echo $strSQL;
			}
		}
		return $hasil;
	}
	
	function do_insert_update($strSQL = ''){
		$hasil = false;
		if($strSQL){
			// $query = $this->db->query($strSQL);
			if($this->db->query($strSQL)){
				$hasil = $this->db->affected_rows();
			}
		}
		return $hasil;
	}



	function periodes($varID = 0){

		$data = false;
		if($varID > 0){
			$strSQL = "SELECT `id`,`nama`,`sdate`,`edate`,`status` FROM bdt_periode WHERE id=".$varID;
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$data = array(
					'id'=>$rs['id'],
					'nama'=>$rs['nama'],
					'sdate'=>$rs['sdate'],
					'edate'=>$rs['edate'],
					'status'=>$rs['status'],
				);
			}
			
		}else{
			$id_aktif = 0;
			$last_archived = 0;
			$strSQL = "SELECT `id`,`nama`,`sdate`,`edate`,`status` FROM bdt_periode ORDER BY sdate DESC";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil = array();
				foreach ($query->result_array() as $rs){
					$hasil[$rs["id"]] = $rs;
					if($rs["status"] == 1){
						$id_aktif = $rs["id"];
					}
				}			
			}
			
			$strSQL = "SELECT id FROM bdt_periode WHERE status=0 ORDER BY sdate DESC";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$last_archived = $rs['id'];
			}
			
			
			$data= array("periode"=>$hasil,"periode_aktif"=>$id_aktif,"last_archived"=>$last_archived);
			
		}
		return $data;
	}

	function bdt_opsi($varRespondenType='rts'){
		$opsi_bdt = array();
		$strSQL = "SELECT * FROM 
		bdt_indikator_opsi 
		WHERE responden='".fixSQL($varRespondenType)."'
		ORDER BY CAST(nama as unsigned)";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$default_value = '';
			foreach ($query->result_array() as $rs){
				if($rs['baku']==1){
					$default_value = $rs['nama'];
					$opsi_bdt['default_value'][$rs['indikator_nama']] = $default_value;
				}
				// $opsi_bdt[$rs['indikator_nama']]['default_value'] = $default_value;
				$opsi_bdt['data'][$rs['indikator_nama']][] = array(
					'nama'=>$rs['nama'],
					'nourut'=>$rs['nourut'],
					'label'=>$rs['label'],
					'default'=>($rs['baku']==1)? true:[],
				);
			}			
			// $opsis = array(
			// 	'data' => $opsi_bdt['data'],
			// 	'default_value'=> $default_value,
			// );
		}
		return $opsi_bdt;
	}

	function bdt_indikator($varRespondenType='rts'){
		$opsi_bdt = $this->bdt_opsi($varRespondenType);
		$indikator = array();
		$strSQL = "SELECT `nourut`,`responden`,`jenis`,`detail`,`foto`,`nama`,`label`,`keterangan`,`default_value`,`query` FROM `bdt_indikator` WHERE `responden`='".$varRespondenType."' ORDER BY nourut";
		$query = $this->db->query($strSQL);
		if($query){
			foreach ($query->result_array() as $rs){
				$opsi = false;
				$default_value = $rs['default_value'];
				if($rs['jenis'] == 'pilihan'){
					$opsi = $opsi_bdt['data'][$rs['nama']];
					$default_value= $opsi_bdt['default_value'][$rs['nama']];
				}
				$indikator[] = array(
					'id'=>$rs['nama'],
					'nourut'=>$rs['nourut'],
					'nama'=>$rs['nama'],
					'label'=>$rs['label'],
					'jenis'=>$rs['jenis'],
					'query'=>$rs['query'],
					'detail'=>(@$rs['detail'])?$rs['detail']:[],
					'foto'=>$rs['foto'],
					'value'=>$default_value,
					'opsi'=>$opsi,
				);
			}			
		}
		return $indikator;
	}

	function bdt_wilayah_kerja_user($varKode=''){
		$hasil = false;
		if($varKode){
			$dusuns =array();
			$strSQL = "SELECT id,kode,nama FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".fixSQL($varKode)."%'";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil = array();
				foreach ($query->result_array() as $rs){
					$dusuns[$rs['kode']] = $rs['nama'];
				}
			}

			$strSQL = "SELECT id,kode,nama,tingkat FROM  tweb_wilayah 
				WHERE kode LIKE '".fixSQL($varKode)."%' AND tingkat > 4 ORDER BY tingkat,kode";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil = array();
				foreach ($query->result_array() as $rs){
					if($rs['tingkat'] == 5){
						$hasil[] = array(
							'tingkat'=>$rs['tingkat'],
							'kode'=>$rs['kode'],
							'nama'=>$rs['nama'],
							'dusun'=>$rs['nama'],
							'rw'=>'000',
							'rt'=>'000',
						);
	
					}elseif($rs['tingkat'] == 6){
						$hasil[] = array(
							'tingkat'=>$rs['tingkat'],
							'kode'=>$rs['kode'],
							'nama'=>$rs['nama'],
							'dusun'=>$dusuns[substr($rs['kode'],0,12)],
							'rw'=>$rs['nama'],
							'rt'=>'000',
						);
					}elseif($rs['tingkat'] == 7){
						$hasil[] = array(
							'tingkat'=>$rs['tingkat'],
							'kode'=>$rs['kode'],
							'nama'=>$rs['nama'],
							'dusun'=>$dusuns[substr($rs['kode'],0,12)],
							'rw'=>substr($rs['kode'],13,3),
							'rt'=>substr($rs['kode'],16,3),
						);
					}
				}
			}
		}
		return $hasil;

	}

	function bdt_rts_by_wilayah($varKode='',$varPage=1){
		$hasil = false;
		if($varKode){
			$periode = $this->periodes();

			$varPage = ($varPage < 1)? 1:$varPage;
			$offset = ($varPage -1) * (LIMIT_PER_PAGE);
			$numrows = 0;
			$strSQL = "SELECT r.id FROM `tweb_rumahtangga` r WHERE r.kode_wilayah LIKE '".fixSQL($varKode)."%'";
			$query = $this->db->query($strSQL);
			if($query){
				$numrows = $query->num_rows();
			}
			$pages = ceil($numrows / LIMIT_PER_PAGE);

			$strSQL = "SELECT r.id,r.idbdt,r.kode_wilayah,r.nopesertapbdt,r.nama_kepala_rumah_tangga,
					d.nama as nama_dusun, 
					(SELECT lead_id FROM bdt_rts WHERE nopesertapbdt=r.nopesertapbdt AND periode_id='".$periode['periode_aktif']."') AS data_status, 
					(SELECT created_at FROM bdt_rts WHERE nopesertapbdt=r.nopesertapbdt AND periode_id='".$periode['periode_aktif']."') AS data_tanggal
				FROM `tweb_rumahtangga` r
					LEFT JOIN tweb_wilayah d ON SUBSTR(r.kode_wilayah,1,12)=d.kode 
				WHERE r.kode_wilayah LIKE '".fixSQL($varKode)."%' 
				LIMIT ".$offset.", ".LIMIT_PER_PAGE;
			$query = $this->db->query($strSQL);
			if($query){
				$record_found =$query->num_rows();
				if($record_found > 0){
					$data = array();
					foreach ($query->result_array() as $rs){
						$data_status = ($rs['data_status']) ? 1:0;
						$data_tanggal = ($rs['data_tanggal']) ? $rs['data_tanggal']: null;
						$rw = '000';
						$rt = '000';
						if(strlen($rs['kode_wilayah'])==18){
							$rw = substr($rs['kode_wilayah'],13,3);
							$rt = substr($rs['kode_wilayah'],16,3);
						}
						$data[] = array(
							'idbdt'=>$rs['idbdt'],
							'nopesertapbdt'=>$rs['nopesertapbdt'],
							'nama_krt'=>$rs['nama_kepala_rumah_tangga'],
							'pendataan_status'=>$data_status,
							'pendataan_tanggal'=>$data_tanggal,
							'kode_wilayah'=>$rs['kode_wilayah'],
							'dusun'=>$rs['nama_dusun'],
							'rw'=>$rw,
							'rt'=>$rt,
	
						);
					}
				}
			}
			$nomer_awal = $offset+1;
			$nomer_akhir = $nomer_awal + $record_found;
			$page_prev = $varPage - 1;
			$page_next = $varPage +1;
			$link_prev = ($page_prev > 0) ? site_url(API_VERSION.'/rumahtangga/?page='.$page_prev) :null;
			$link_next = ($page_next > $pages) ? site_url(API_VERSION.'/rumahtangga/?page='.$pages) :site_url(API_VERSION.'/rumahtangga/?page='.$page_next);
			$hasil = array(
				'data'=>$data,
				'meta'=>array(
					'page_current'=>$varPage,
					'page_from'=>1,
					'page_last'=>$pages,
					'data_num'=>$record_found,
					'data_num_start'=>$nomer_awal,
					'data_num_end'=>$nomer_akhir,
					'data_per_page'=>LIMIT_PER_PAGE,
					'total'=>$numrows,
				),
				'links'=>array(
					'first'=>site_url(API_VERSION.'/rumahtangga/?page=1'),
					'last'=>site_url(API_VERSION.'/rumahtangga/?page='.$pages),
					'prev'=>$link_prev,
					'next'=>$link_next,
				)
			);
			
		}
		return $hasil;
	}

	function get_rts_detail($varID){
		$hasil  = false;
		$strSQLR = "SELECT `id`,`kode_wilayah`, `idbdt`, `nopesertapbdt`, `ruta6`, `nama_kepala_rumah_tangga`, `alamat`, `updated_at`, `updated_by`, `var_lat`, `var_lon`, `foto` FROM `tweb_rumahtangga` WHERE `idbdt`='".fixSQL($varID)."'";
		$query = $this->db->query($strSQLR);
		if($query){
			if($query->num_rows() > 0){
				
				$hasil = $query->result_array()[0];
			}
		}
		return $hasil;
	}

	function get_rts($varID='',$varKode=''){
		$hasil = false;
		if($varID){

			// Data Rumahtangga 
			$strSQLR = "SELECT `id`,`kode_wilayah`, `idbdt`, `nopesertapbdt`, `ruta6`, `nama_kepala_rumah_tangga`, `alamat`, `updated_at`, `updated_by`, `var_lat`, `var_lon`, `foto` FROM `tweb_rumahtangga` WHERE `idbdt`='".fixSQL($varID)."'";
			$query = $this->db->query($strSQLR);
			if($query){
				if($query->num_rows() > 0){
					$hasil = array();
					$rts = $query->result_array()[0];

					$anggota = null;
					// Anggota Rumah Tangga 
					$strSQLA = "SELECT id,idartbdt,nik,kk_nomor,nama,rt_hubungan,jnskel,dtlahir,foto FROM tweb_penduduk WHERE idbdt='".fixSQL($varID)."'";
					$query = $this->db->query($strSQLA);
					if($query){
						if($query->num_rows() > 0){
							$anggota = array();
							foreach($query->result() as $rs){
								$anggota[] = array(
									'idartbdt'=>$rs->idartbdt,
									'nik'=>$rs->nik,
									'kk_nomor'=>$rs->kk_nomor,
									'nama'=>$rs->nama,
									'foto'=>$rs->foto,
									'hubungan_rumahtangga'=>$rs->rt_hubungan,
									'jnskel'=>$rs->jnskel,
									'dtlahir'=>$rs->dtlahir,
								);
							}
						}
					}

					// Data Kesejahteraan
					$periode = $this->periodes();
			
					$strSQL = "SELECT * FROM bdt_rts 
					WHERE ((idbdt='".fixSQL($varID)."') AND (kode_wilayah LIKE '".$varKode."%') AND (periode_id='".fixSQL($periode['periode_aktif'])."'))"; 
					$query = $this->db->query($strSQL);
					if($query->num_rows() > 0){
						$hasil['status']= true;
						$data =$query->result_array()[0];
						$hasil['data']=$data;
					}else{
						$hasil = array(
							'status'=>false,
							'msg'=>"Data rts '".$varID."' untuk peridoe '".$periode_id." (".$periode['periode'][$periode_id]['nama'].")' belum Masuk"
						);
					}
					$indikator_rts = $this->bdt_indikator('rts');
					$hasil = array(
						'idbdt'=>$rts['idbdt'],
						'nama_kepala_rumah_tangga'=>$rts['nama_kepala_rumah_tangga'],
						'koordinat_rumah'=>array(
							'latitude'=>$rts['var_lat'],
							'longitude'=>$rts['var_lon'],
						),
						'foto'=>$rts['foto'],
						'jumlah_art'=>count($anggota),
						'anggota_rumah_tangga'=>$anggota,
					);
				}
			}
		}
		return $hasil;
	}

	function bdt_penduduk_by_wilayah($varKode='',$varPage=1){
		$hasil = false;
		if($varKode){
			$periode = $this->periodes();

			$varPage = ($varPage < 1)? 1:$varPage;
			$offset = ($varPage -1) * (LIMIT_PER_PAGE);
			$numrows = 0;
			$strSQL = "SELECT r.id FROM `tweb_penduduk` r WHERE r.kode_wilayah LIKE '".fixSQL($varKode)."%'";
			$query = $this->db->query($strSQL);
			if($query){
				$numrows = $query->num_rows();
			}
			$pages = ceil($numrows / LIMIT_PER_PAGE);

			$strSQL = "SELECT p.id,p.idartbdt,p.idbdt,p.kode_wilayah,p.nama,p.nik,p.kk_nomor,p.dtlahir,p.jnskel,p.tlahir,
					d.nama as nama_dusun, 
					(SELECT lead_id FROM bdt_idv WHERE idartbdt=p.idartbdt AND periode_id='".$periode['periode_aktif']."') AS data_status, 
					(SELECT created_at FROM bdt_idv WHERE idartbdt=p.idartbdt AND periode_id='".$periode['periode_aktif']."') AS data_tanggal
				FROM `tweb_penduduk` p
					LEFT JOIN tweb_wilayah d ON SUBSTR(p.kode_wilayah,1,12)=d.kode 
				WHERE p.kode_wilayah LIKE '".fixSQL($varKode)."%' 
				LIMIT ".$offset.", ".LIMIT_PER_PAGE;
			$query = $this->db->query($strSQL);
			if($query){
				$record_found =$query->num_rows();
				if($record_found > 0){
					$data = array();
					foreach ($query->result_array() as $rs){
						$data_status = ($rs['data_status']) ? 1:0;
						$data_tanggal = ($rs['data_tanggal']) ? $rs['data_tanggal']: null;
						$rw = '000';
						$rt = '000';
						if(strlen($rs['kode_wilayah'])==18){
							$rw = substr($rs['kode_wilayah'],13,3);
							$rt = substr($rs['kode_wilayah'],16,3);
						}
						$data[] = array(
							'idartbdt'=>$rs['idartbdt'],
							'idbdt'=>$rs['idbdt'],
							'nama'=>$rs['nama'],
							'nik'=>$rs['nik'],
							'nokk'=>$rs['kk_nomor'],
							'tmplahir'=>$rs['tlahir'],
							'tgllahir'=>$rs['dtlahir'],
							'jnskel'=>$rs['jnskel'],
							'pendataan_status'=>$data_status,
							'pendataan_tanggal'=>($data_tanggal) ? $data_tanggal:[],
							'kode_wilayah'=>$rs['kode_wilayah'],
							'dusun'=>$rs['nama_dusun'],
							'rw'=>$rw,
							'rt'=>$rt,
	
						);
					}
					$nomer_awal = $offset+1;
					$nomer_akhir = $nomer_awal + $record_found;
					$page_prev = $varPage - 1;
					$page_next = $varPage +1;
					$link_prev = ($page_prev > 0) ? site_url(API_VERSION.'/penduduk/?page='.$page_prev) :null;
					$link_next = ($page_next > $pages) ? site_url(API_VERSION.'/penduduk/?page='.$pages) :site_url(API_VERSION.'/rumahtangga/?page='.$page_next);
					$hasil = array(
						'data'=>$data,
						'meta'=>array(
							'page_current'=>$varPage,
							'page_from'=>1,
							'page_last'=>$pages,
							'data_num'=>$record_found,
							'data_num_start'=>$nomer_awal,
							'data_num_end'=>$nomer_akhir,
							'data_per_page'=>LIMIT_PER_PAGE,
							'total'=>$numrows,
						),
						'links'=>array(
							'first'=>site_url(API_VERSION.'/penduduk/?page=1'),
							'last'=>site_url(API_VERSION.'/penduduk/?page='.$pages),
							'prev'=>$link_prev,
							'next'=>$link_next,
						)
					);
				}
			}
		}
		return $hasil;
	}
	function get_art($varID='',$varKode=''){
		$hasil = false;
		if($varID){
			$periode = $this->periodes();

			// Detail Penduduk 
			$strSQL = "SELECT p.id,p.idartbdt,p.idbdt,p.kode_wilayah,p.nama,p.nik,
					p.rt_hubungan,p.kk_hubungan,
					p.kk_nomor,p.jnskel,p.dtlahir,p.foto,
					d.nama as nama_dusun, 
					(SELECT lead_id FROM bdt_idv WHERE idartbdt=p.idartbdt AND periode_id='".$periode['periode_aktif']."') AS data_status, 
					(SELECT created_at FROM bdt_idv WHERE idartbdt=p.idartbdt AND periode_id='".$periode['periode_aktif']."') AS data_tanggal
				FROM `tweb_penduduk` p
					LEFT JOIN tweb_wilayah d ON SUBSTR(p.kode_wilayah,1,12)=d.kode 
				WHERE ((p.kode_wilayah LIKE '".fixSQL($varKode)."%') AND (p.idartbdt='".fixSQL($varID)."'))";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$hasil = array();
					$rs = $query->row();
					// Data Kesejahteraan
			
					$strSQL = "SELECT * FROM bdt_idv 
					WHERE ((idbdt='".fixSQL($varID)."') AND (kode_wilayah LIKE '".$varKode."%') AND (periode_id='".fixSQL($periode['periode_aktif'])."'))"; 
					$query = $this->db->query($strSQL);
					$data_bdt = false;
					if($query->num_rows() > 0){
						$hasil['status']= true;
						$data_bdt =$query->result_array()[0];
					}
					$indikator_art = $this->bdt_indikator('art');
					$hasil = array(
						'idartbdt'=>$rs->idartbdt,
						'idbdt'=>$rs->idbdt,
						'nik'=>$rs->nik,
						'kk_nomor'=>$rs->kk_nomor,
						'nama'=>$rs->nama,
						'foto'=> ($rs->foto) ? $rs->foto:[],
						'hubungan_rumahtangga'=>$rs->rt_hubungan,
						'hubungan_kartukeluarga'=>$rs->kk_hubungan,
						'jnskel'=>$rs->jnskel,
						'dtlahir'=>$rs->dtlahir,
						'data_bdt'=> ($data_bdt)? $data_bdt : [],
					);
				}
			}
		}
		return $hasil;
	}

}