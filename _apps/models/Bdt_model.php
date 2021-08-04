<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bdt_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function do_query($strSQL = ''){
		$hasil = false;
		if($strSQL){
			$query= $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}


	function desil(){
		$desil = array(
			'desil1'=>
			array(
				'id'=>'desil1',
				'nama'=>'Desil 1',
				'batas_bawah'=>0,
				'batas_atas'=>10),
			'desil2'=>
			array(
				'id'=>'desil2',
				'nama'=>'Desil 2',
				'batas_bawah'=>11,
				'batas_atas'=>20),
			'desil3'=>
			array(
				'id'=>'desil3',
				'nama'=>'Desil 3',
				'batas_bawah'=>21,
				'batas_atas'=>30),
			'desil4'=>
			array(
				'id'=>'desil4',
				'nama'=>'Desil 4',
				'batas_bawah'=>31,
				'batas_atas'=>40),
			'desil5'=>
			array(
				'id'=>'desil5',
				'nama'=>'Desil 5',
				'batas_bawah'=>40,
				'batas_atas'=>0),
		);
		return $desil;
	}

	function periodes($varID = 0){

		$data = false;
		if($varID > 0){
			$strSQL = "SELECT * FROM bdt_periode WHERE id=".$varID ."";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$data = $query->result_array()[0];
			}
			
		}else{
			$id_aktif = 0;
			$id_terbaru = 0;
			$last_archived = 0;
			$strSQL = "SELECT * FROM bdt_periode ORDER BY id DESC";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil = array();
				foreach ($query->result_array() as $rs){
					$hasil[$rs["id"]] = $rs;
					if($rs["status"] == 1){
						$id_aktif = $rs["id"];
					}
					if($rs["status"] == 2){
						$id_terbaru = $rs["id"];
					}
				}			
			}
			
			$strSQL = "SELECT id FROM bdt_periode WHERE status=0 ORDER BY sdate DESC";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$last_archived = $rs['id'];
			}
			
			
			$data= array(
				"periode"=>$hasil,
				"periode_terbaru"=>$id_terbaru,
				"periode_aktif"=>$id_aktif,
				"last_archived"=>$last_archived);
			
		}
		return $data;
	}

	function bdt_opsi($varRespondenType='rts'){
		$opsi_bdt = array();
		$strSQL = "SELECT * FROM 
		bdt_indikator_opsi 
		WHERE responden='".fixSQL($varRespondenType)."'
		ORDER BY nama";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $rs){
				$opsi_bdt[$rs['indikator_nama']][$rs['nama']] = array(
					'nama'=>$rs['nama'],
					'nourut'=>$rs['nourut'],
					'label'=>$rs['label'],
				);
			}			
		}
		return $opsi_bdt;
	}

	function bdt_indikator($varRespondenType='rts'){
		$opsi_bdt = $this->bdt_opsi($varRespondenType);
		$indikator = array();
		$strSQL = "SELECT `nourut`,`responden`,`jenis`,`nama`,`label`,`keterangan` FROM `bdt_indikator` WHERE `responden`='".$varRespondenType."' ORDER BY nourut";
		$query = $this->db->query($strSQL);
		if($query){
			foreach ($query->result_array() as $rs){
				$opsi = false;
				if($rs['jenis'] == 'pilihan'){
					$opsi = $opsi_bdt[$rs['nama']];
				}
				$indikator[$rs['nama']] = array(
					'nourut'=>$rs['nourut'],
					'nama'=>$rs['nama'],
					'label'=>$rs['label'],
					'jenis'=>$rs['jenis'],
					'opsi'=>$opsi,
				);
			}			
		}
		return $indikator;
	}

	function angka($varKode){
		// Rumah tangga;
		$strSQL = "SELECT distinct(idbdt) FROM tweb_rumahtangga WHERE kode_wilayah LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		$nrts = 0;
		if($query){
			if($query->num_rows() > 0){
				$nrts = $query->num_rows();
			}
		}
		// ART
		$strSQL = "SELECT distinct(idartbdt) FROM tweb_penduduk WHERE kode_wilayah LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		$nart = 0;
		if($query){
			if($query->num_rows() > 0){
				$nart = $query->num_rows();
			}
		}
		// KK
		$strSQL = "SELECT distinct(kk_nomor) FROM tweb_keluarga WHERE kode_wilayah LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		$nkks = 0;
		if($query){
			if($query->num_rows() > 0){
				$nkks = $query->num_rows();
			}
		}

		$hasil = array(
			'art'=>$nart,
			'kks'=>$nkks,
			'rts'=>$nrts,
		);
		return $hasil;
	}

	function data_bdt_by_desil_wilayah($varKode){

		if($varKode){
			$desil = $this->desil();
			$periodes = $this->periodes();
			$strSQLRTS = array();
			$strSQLART = array();
			foreach($periodes['periode'] as $periode){
				foreach($desil as $d){
					$strSQLRTS[] = "SELECT DISTINCT(idbdt),COUNT(lead_id) FROM bdt_rts WHERE periode_id='".$periode['id']."' GROUP BY idbdt";
					$strSQLART[] = "SELECT DISTINCT(idartbdt),COUNT(lead_id) FROM bdt_idv WHERE periode_id='".$periode['id']."' GROUP BY idartbdt";
				}
			}

			
			$strSQL = "SELECT w.kode,w.nama 
				FROM tweb_wilayah w 
				WHERE w.kode LIKE '".fixSQL($varKode)."%' AND tingkat='".$tingkat."' ORDER BY nama";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result() as $rs){
						$hasil[] = array(
							'kode'=>$rs->kode,
							'nama'=>$rs->nama,

						);
					}
				}
			}

		}
	}


	// Data per Indikator RTS
	function data_rts_by_indikator_by_periode_by_area($varKode=0,$varPeriode=1,$varIndikator=''){
		$hasil = false;
		$strSQL = "SELECT 
				DISTINCT(`".$varIndikator."`) as opt_id,
				COUNT(lead_id) as numrows 
			FROM `bdt_rts` 
			WHERE ((`kode_wilayah` LIKE '".fixSQL($varKode)."%')
				AND (`periode_id`=".$varPeriode."))
			GROUP bY `".$varIndikator."`";
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['opt_id']] = $rs['numrows'];
				}
				$hasil = $data;
			}
		}
		return $hasil;
	}
	

	// Data per Indikator ART
	function data_art_by_indikator_by_periode_by_area($varKode=0,$varPeriode=1,$varIndikator=''){
		$hasil = false;
		$strSQL = "SELECT 
				DISTINCT(`".$varIndikator."`) as opt_id,
				COUNT(lead_id) as numrows 
			FROM `bdt_idv` 
			WHERE ((`kode_wilayah` LIKE '".fixSQL($varKode)."%')
				AND (`periode_id`=".$varPeriode."))
			GROUP bY `".$varIndikator."`";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['opt_id']] = $rs['numrows'];
				}
				$hasil = $data;
			}
		}
		return $hasil;
	}


	function data_desil_by_wilayah_by_periode($varKode,$varPeriode,$varSasaran='rts'){

		$hasil = false;
		// $table = ($varSasaran == 'rts') ? 'COUNT(`lead_id`)':'SUM(`jumlah_art`)';
		$table = ($varSasaran == 'rts') ? 'bdt_rts' : 'bdt_idv';
		$tingkat = _tingkat_by_len_kode($varKode);
		// echo $tingkat;
		$tingkat = $tingkat + 1;

		$desil = $this->desil();
		
		$strSQL = "SELECT w.kode, \n"; 
		$n = count($desil);
		$i=1;
		foreach($desil as $d){
			$strKoma = ($i < $n)? ", ":" ";
			if($d['batas_atas'] < $d['batas_bawah']){
				$strSQL .="(SELECT COUNT(`lead_id`) FROM `".$table."` WHERE ((kode_wilayah LIKE CONCAT(w.kode,'%')) AND (periode_id='".fixSQL($varPeriode)."') AND (`percentile` > ".$d['batas_bawah'].") )) as ".$d['id']."".$strKoma ." \n";
			}else{
				$strSQL .="(SELECT COUNT(`lead_id`) FROM `".$table."` WHERE ((kode_wilayah LIKE CONCAT(w.kode,'%')) AND (periode_id='".fixSQL($varPeriode)."') AND (`percentile` BETWEEN ".$d['batas_bawah']." AND ".$d['batas_atas'].") )) as ".$d['id']."".$strKoma." \n";
			}
			$i++;
		}

		$strSQL .= " FROM tweb_wilayah w WHERE w.tingkat='".$tingkat."' AND w.kode LIKE '".$varKode."%' \n";
		
		// echo $strSQL;
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['kode']] = $rs;
				}
				$hasil = $data;
			}
	 	}
		return $hasil;

	}

	function data_rts_by_desil_by_wilayah($varPeriode=1,$varDesil=1,$varKode=0){
		$hasil = false;
		$desil = $this->desil();
		if($varDesil != '0'){
			$percentile = "BETWEEN ".$desil[$varDesil]['batas_bawah']." AND ".$desil[$varDesil]['batas_atas']."";
			if($desil[$varDesil]['batas_atas'] < $desil[$varDesil]['batas_bawah']){
				$percentile = " > ".$desil[$varDesil]['batas_bawah']."";
			}
			$strSQL = "SELECT r.*, 
				dn.nama as dusun,
				ds.nama as desa,
				kec.nama as kecamatan,
				kab.nama as kabupaten
			FROM bdt_rts r 
				LEFT JOIN tweb_wilayah dn ON SUBSTRING(r.kode_wilayah,1,12)=dn.kode
				LEFT JOIN tweb_wilayah ds ON SUBSTRING(r.kode_wilayah,1,10)=ds.kode
				LEFT JOIN tweb_wilayah kec ON SUBSTRING(r.kode_wilayah,1,7)=kec.kode
				LEFT JOIN tweb_wilayah kab ON SUBSTRING(r.kode_wilayah,1,4)=kab.kode

			WHERE ((r.periode_id='".fixSQL($varPeriode)."') AND (r.kode_wilayah LIKE '".$varKode."%') AND (r.percentile ".$percentile.") )";
		}else{
			$strSQL = "SELECT r.*, 
			dn.nama as dusun,
			ds.nama as desa,
			kec.nama as kecamatan,
			kab.nama as kabupaten
		FROM bdt_rts r 
			LEFT JOIN tweb_wilayah dn ON SUBSTRING(r.kode_wilayah,1,12)=dn.kode
			LEFT JOIN tweb_wilayah ds ON SUBSTRING(r.kode_wilayah,1,10)=ds.kode
			LEFT JOIN tweb_wilayah kec ON SUBSTRING(r.kode_wilayah,1,7)=kec.kode
			LEFT JOIN tweb_wilayah kab ON SUBSTRING(r.kode_wilayah,1,4)=kab.kode
			WHERE ((r.periode_id='".fixSQL($varPeriode)."') AND (r.kode_wilayah LIKE '".$varKode."%') )";
		}
		if($varKode > 0){
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$data[$rs['idbdt']] = $rs;
					}
					$hasil = $data;
				}
			}
		}
		return $hasil;
	}
	
	function data_art_by_desil_by_wilayah($varPeriode=1,$varDesil=1,$varKode=0){
		$hasil = false;
		$desil = $this->desil();
		$percentile = "BETWEEN ".$desil[$varDesil]['batas_bawah']." AND ".$desil[$varDesil]['batas_atas']."";
		if($desil[$varDesil]['batas_atas'] < $desil[$varDesil]['batas_bawah']){
			$percentile = " > ".$desil[$varDesil]['batas_bawah']."";
		}
		if($varKode > 0){
			$strSQL = "SELECT a.lead_id,a.idbdt,a.idartbdt,a.nama,a.nik,a.nokk FROM bdt_idv a 
			WHERE a.idbdt IN (SELECT b.idbdt FROM bdt_rts b WHERE ((b.periode_id='".fixSQL($varPeriode)."') AND (b.kode_wilayah LIKE '".$varKode."%') AND (b.percentile ".$percentile.") ))";
			// echo $strSQL;
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$data[$rs['idartbdt']] = $rs;
					}
					$hasil = $data;
				}
			}
		}
		return $hasil;
	}

	function get_rts($varID,$varKode){
		$hasil = false;
		if($varID){

			// Data Rumahtangga 
			$strSQLR = "SELECT r.`id`,r.`kode_wilayah`, r.`idbdt`, r.`nopesertapbdt`, r.`ruta6`, r.`nama_kepala_rumah_tangga`, r.`updated_at`, r.`updated_by`, r.`var_lat`, r.`var_lon`, r.`foto` ,
					d.`percentile` as percentile, d.`alamat` as alamat
				FROM `tweb_rumahtangga` r 
					LEFT JOIN bdt_rts d ON r.idbdt=d.idbdt
				WHERE r.`idbdt`='".fixSQL($varID)."'";
			$query = $this->db->query($strSQLR);
			if($query){
				if($query->num_rows() > 0){
					$hasil = array();
					$rts = $query->result_array()[0];

					$anggota = array();
					$data_bdt = array();
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
					$periode_id  = $periode['periode_aktif'];

					foreach($periode['periode'] as $p){
						$strSQL = "SELECT * FROM bdt_rts 
						WHERE ((idbdt='".fixSQL($varID)."') AND (kode_wilayah LIKE '".$varKode."%') AND (periode_id='".fixSQL($p['id'])."'))"; 
						$query = $this->db->query($strSQL);
						if($query->num_rows() > 0){
							$hasil['status']= true;
							$data =$query->result_array()[0];
							$data_bdt[$p['id']]=$data;
						}
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
						'kode_wilayah'=>$rts['kode_wilayah'],
						'percentile'=>$rts['percentile'],
						'desil'=>_desil_mana($rts['percentile']),
						'alamat'=>$rts['alamat'],
						'jumlah_art'=>count($anggota),
						'anggota_rumah_tangga'=>$anggota,
						'data_bdt'=>$data_bdt,
					);
				}
			}			
		}
		return $hasil;
	}

	function get_art($varID,$varKode){
		$hasil = false;
		if($varID){
			// Data ART
			$art = false;
			$strSQLA = "SELECT id,idartbdt,idbdt,nik,kk_nomor,nama,tlahir,dtlahir,rt_hubungan,jnskel,dtlahir,foto FROM tweb_penduduk WHERE idartbdt='".fixSQL($varID)."'";
			$query = $this->db->query($strSQLA);
			if($query){
				if($query->num_rows() > 0){
					$art = $query->result_array()[0];
					// Data Rumahtangga 
					$strSQLR = "SELECT r.`id`,r.`kode_wilayah`, r.`idbdt`, r.`nopesertapbdt`, r.`ruta6`, r.`nama_kepala_rumah_tangga`, r.`updated_at`, r.`updated_by`, r.`var_lat`, r.`var_lon`, r.`foto` ,
							d.`percentile` as percentile, d.`alamat` as alamat
						FROM `tweb_rumahtangga` r 
							LEFT JOIN bdt_rts d ON r.idbdt=d.idbdt
						WHERE r.`idbdt`='".fixSQL($art['idbdt'])."'";
					$query = $this->db->query($strSQLR);
					if($query){
						if($query->num_rows() > 0){
							$hasil = array();
							$rts = $query->result_array()[0];
						}
					}
					// Data Kesejahteraan
					$periode = $this->periodes();
					$periode_id  = $periode['periode_aktif'];
					foreach($periode['periode'] as $p){
						$strSQL = "SELECT * FROM bdt_idv 
						WHERE ((idartbdt='".fixSQL($varID)."') AND (idbdt='".fixSQL($art['idbdt'])."') AND (kode_wilayah LIKE '".$varKode."%') AND (periode_id='".fixSQL($p['id'])."'))"; 
						$query = $this->db->query($strSQL);
						if($query->num_rows() > 0){
							$hasil['status']= true;
							$data =$query->result_array()[0];
							$data_bdt[$p['id']]=$data;
						}
					}

					$indikator_rts = $this->bdt_indikator('art');
					$hasil = array(
						'idbdt'=>$art['idbdt'],
						'idartbdt'=>$art['idartbdt'],
						'nama'=>$art['nama'],
						'nik'=>$art['nik'],
						'tmplahir'=>$art['tlahir'],
						'tgllahir'=>indonesian_date($art['dtlahir'],"j F Y"),
						'koordinat_rumah'=>array(
							'latitude'=>$rts['var_lat'],
							'longitude'=>$rts['var_lon'],
						),
						'foto'=>$art['foto'],
						'kode_wilayah'=>$rts['kode_wilayah'],
						'nama_kepala_rumah_tangga'=>$rts['nama_kepala_rumah_tangga'],
						'percentile'=>$rts['percentile'],
						'desil'=>_desil_mana($rts['percentile']),
						'alamat'=>$rts['alamat'],
						'data_bdt'=>$data_bdt,
					);


				}
			}
		}
		return $hasil;
	}	

	function data_rts_by_value_indikator_by_area_by_periode($varIndikator='',$varOpsi=0,$varKode=0,$varPeriode=1){
		$hasil = false;
		$strSQL = "SELECT 
				DISTINCT(`idbdt`),nama_krt,jumlah_art,alamat,kode_wilayah,percentile
			FROM `bdt_rts` 
			WHERE ((`kode_wilayah` LIKE '".fixSQL($varKode)."%')
				AND (`periode_id`='".$varPeriode."')
				AND (`".$varIndikator."`='".fixSQL($varOpsi)."')
				)";
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['idbdt']] = $rs;
				}
				$hasil = $data;
			}
		}
		return $hasil;
	}	

	function data_art_by_value_indikator_by_area_by_periode($varIndikator='',$varOpsi=0,$varKode=0,$varPeriode=1,$varPage=1){
		$hasil = false;
		$strSQL = "SELECT 
				DISTINCT(d.`idartbdt`),d.idbdt,d.nama,d.umur,d.kode_wilayah,
				(SELECT percentile FROM bdt_rts WHERE periode_id='".$varPeriode."' AND idbdt=d.idbdt LIMIT 1) as percentile
			FROM `bdt_idv` d
			WHERE ((d.`kode_wilayah` LIKE '".fixSQL($varKode)."%')
				AND (d.`periode_id`='".$varPeriode."')
				AND (d.`".$varIndikator."`='".fixSQL($varOpsi)."')
				)";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['idartbdt']] = $rs;
				}
				$hasil = $data;
			}else{
				$hasil = $strSQL;	
			}
		}else{
			$hasil = $strSQL;
		}
		return $hasil;
	}		

	function data_bdt_by_wilayah($varKode=''){
		// Data RTS
		$data_rts = array();
		$strSQL = "SELECT DISTINCT(periode_id),COUNT(lead_id) as numrows FROM bdt_rts WHERE kode_wilayah LIKE '".$varKode."%' GROUP BY periode_id";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data_rts[$rs['periode_id']] = $rs['numrows'];
				}
			}
		}
		// Data RTS
		$data_idv = array();
		$strSQL = "SELECT DISTINCT(periode_id),COUNT(lead_id) as numrows FROM bdt_idv WHERE kode_wilayah LIKE '".$varKode."%' GROUP BY periode_id";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data_idv[$rs['periode_id']] = $rs['numrows'];
				}
			}
		}

		$hasil = array(
			'rts'=>$data_rts,
			'idv'=>$data_idv,
		);
		return $hasil;

	}
}

