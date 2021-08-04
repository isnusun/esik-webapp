<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bdt2015_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function pbdt2015_indikator($varSasaran = ''){
		$hasil = false;
		$opsi= array();
		$strSasaran = (strlen($varSasaran) > 0) ? " AND sasaran='".$varSasaran."'":"";
		$strSQL = "SELECT * FROM pbdt2015_opsi WHERE 1 ".$strSasaran ." ORDER BY cast(label as unsigned) asc,indikator_id";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$opsi[$rs['sasaran']][$rs['indikator_id']][$rs['label']] = $rs['nama'];
				}
			}
		}

		$strSQL = "SELECT * FROM pbdt2015_indikator WHERE 1 ".$strSasaran ." ORDER BY id";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$opsinya = false;
					if($rs['jenis'] == 'pilihan') {
						$opsinya = $opsi[$rs['sasaran']][$rs['id']];
					}
					$data[$rs['sasaran']][$rs['id']] = array(
						'id'=>$rs['id'],
						'nama'=>$rs['nama'],
						'jenis'=>$rs['jenis'],
						'opsi'=>$opsinya
					);
				}
				$hasil = $data;
			}
		}
		// echo var_dump($hasil);
		return $hasil;
	}

	function data_desil_by_wilayah_by_periode($varKode,$varSasaran='rts'){
		$hasil = false;
		// $table = ($varSasaran == 'rts') ? 'COUNT(`lead_id`)':'SUM(`jumlah_art`)';
		$table = ($varSasaran == 'rts') ? 'COUNT(`id`)':'SUM(`Jumlah Anggota Rumah Tangga`)';
		$tingkat = _tingkat_by_len_kode($varKode);
		// echo $tingkat;
		$tingkat = $tingkat + 1;

		$desil = _bdt2015_desil();
		
		$strSQL = "SELECT w.kode,w.nama, \n"; 
		$n = count($desil);
		$i=1;
		foreach($desil as $k=>$d){
			$strKoma = ($i < $n) ? ", ":" ";
			$strSQL .="(SELECT ".$table." FROM 
			`pbdt2015_rts` WHERE (
				(`kode_wilayah` LIKE CONCAT(w.kode,'%')) AND 
				(`Status Kesejahteraan` = ".$k.") 
				) 
			)as `".$d."` ".$strKoma ." \n";
			$i++;
		}

		$strSQL .= " FROM tweb_wilayah w WHERE w.tingkat='".$tingkat."' AND w.kode LIKE '".$varKode."%' \n";
		// 
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['kode']] = $rs;
				}
				$hasil = $data;
			}
		}else{
			$hasil = $strSQL;
		}
		return $hasil;

	}

	function data_rts_by_desil_by_wilayah($varDesil=1,$varKode=0){
		$hasil = false;
		if($varDesil != '0'){

			$strSQL = "SELECT r.*, 
				dn.nama as dusun,
				ds.nama as desa,
				kec.nama as kecamatan,
				kab.nama as kabupaten
			FROM pbdt2015_rts r 
				LEFT JOIN tweb_wilayah dn ON SUBSTRING(r.kode_wilayah,1,12)=dn.kode
				LEFT JOIN tweb_wilayah ds ON SUBSTRING(r.kode_wilayah,1,10)=ds.kode
				LEFT JOIN tweb_wilayah kec ON SUBSTRING(r.kode_wilayah,1,7)=kec.kode
				LEFT JOIN tweb_wilayah kab ON SUBSTRING(r.kode_wilayah,1,4)=kab.kode

			WHERE ((r.kode_wilayah LIKE '".$varKode."%') AND (r.`Status Kesejahteraan`= ".$varDesil.") )";
		}else{
			$strSQL = "SELECT r.*, 
			dn.nama as dusun,
			ds.nama as desa,
			kec.nama as kecamatan,
			kab.nama as kabupaten
			FROM pbdt2015_rts r 
				LEFT JOIN tweb_wilayah dn ON SUBSTRING(r.kode_wilayah,1,12)=dn.kode
				LEFT JOIN tweb_wilayah ds ON SUBSTRING(r.kode_wilayah,1,10)=ds.kode
				LEFT JOIN tweb_wilayah kec ON SUBSTRING(r.kode_wilayah,1,7)=kec.kode
				LEFT JOIN tweb_wilayah kab ON SUBSTRING(r.kode_wilayah,1,4)=kab.kode
			WHERE ((r.kode_wilayah LIKE '".$varKode."%') )";
		}

		if($varKode > 0){
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$data[$rs['Nomor Urut Rumah Tangga']] = $rs;
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
			$strSQLR = "SELECT r.`id`,r.`kode_wilayah`, r.`Nomor Urut Rumah Tangga`
				FROM `pbdt2015_rts` r 
				WHERE r.`Nomor Urut Rumah Tangga`='".fixSQL($varID)."'";
			// echo $strSQLR;
			$query = $this->db->query($strSQLR);
			if($query){
				if($query->num_rows() > 0){
					$hasil = array();
					$rts = $query->result_array()[0];

					$anggota = null;
					// Anggota Rumah Tangga 
					$strSQLA = "SELECT d.* FROM pbdt2015_idv d WHERE d.`Nomor Urut Rumah Tangga`='".fixSQL($varID)."'";
					$query = $this->db->query($strSQLA);
					// echo $strSQLA;
					if($query){
						if($query->num_rows() > 0){
							$anggota = array();
							foreach($query->result_array() as $rs){
								$anggota[] = $rs;
								// $anggota[] = array(
								// 	'idartbdt'=>$rs->idartbdt,
								// 	'nik'=>$rs->nik,
								// 	'kk_nomor'=>$rs->kk_nomor,
								// 	'nama'=>$rs->nama,
								// 	'foto'=>$rs->foto,
								// 	'hubungan_rumahtangga'=>$rs->rt_hubungan,
								// 	'jnskel'=>$rs->jnskel,
								// 	'dtlahir'=>$rs->dtlahir,
								// );
							}
						}
					}

					// Data Kesejahteraan
					$strSQL = "SELECT * FROM pbdt2015_rts 
					WHERE ((`Nomor Urut Rumah Tangga`='".fixSQL($varID)."') AND (kode_wilayah LIKE '".$varKode."%') )"; 
					$query = $this->db->query($strSQL);
					if($query->num_rows() > 0){
						$hasil['status']= true;
						$data =$query->result_array()[0];
						$data_bdt =$data;
					}

					$indikator_rts = $this->pbdt2015_indikator('rts');
					$hasil = array(
						'idbdt'=>$rts['Nomor Urut Rumah Tangga'],
						// 'nama_kepala_rumah_tangga'=>$rts['Nama Kepala Rumah Tangga'],
						// 'koordinat_rumah'=>array(
						// 	'latitude'=>$rts['var_lat'],
						// 	'longitude'=>$rts['var_lon'],
						// ),
						// 'foto'=>$rts['foto'],
						'kode_wilayah'=>$rts['kode_wilayah'],
						// 'alamat'=>$rts['Alamat'],
						// 'jumlah_art'=>count($anggota),
						'anggota_rumah_tangga'=>$anggota,
						'data_bdt'=>$data_bdt,
					);
				}
			}			
		}
		return $hasil;
	}

	function data_art_by_desil_by_wilayah($varDesil=1,$varKode=0){
		$hasil = false;
		if($varKode > 0){
			$strSQL = "SELECT a.* FROM pbdt2015_idv a 
			WHERE a.`Status Kesejahteraan`='".$varDesil."' AND a.kode_wilayah LIKE '".$varKode."%'";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$data[$rs['id']] = $rs;
					}
					$hasil = $data;
				}
			}else{
				$hasil = $strSQL;
			}
		}
		return $hasil;
	}

	function get_art_by_id($varID){
		$hasil = false;
		$strSQL = "SELECT * FROM pbdt2015_idv WHERE id=".fixSQL($varID);
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$data = $query->result_array()[0];
				$hasil = $data;
			}
		}else{
			$hasil = $strSQL;
		}
		return $hasil;


	}

	function data_rts_by_indikator_by_periode_by_area($varKode=0,$varIndikator=''){
		$hasil = false;
		$strSQL = "SELECT 
				DISTINCT(`".$varIndikator."`) as opt_id,
				COUNT(id) as numrows 
			FROM `pbdt2015_rts` 
			WHERE (
				(`kode_wilayah` LIKE '".fixSQL($varKode)."%')
			)
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
	
	function data_art_by_indikator_by_periode_by_area($varKode=0,$varIndikator){
		$hasil = false;
		$strSQL = "SELECT 
				DISTINCT(`".$varIndikator."`) as opt_id,
				COUNT(id) as numrows 
			FROM `pbdt2015_idv` 
			WHERE (
				(`kode_wilayah` LIKE '".fixSQL($varKode)."%')
			)
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
	function data_responden_by_opsi_indikator_by_area($varRts,$varKode,$varIndikator){
		// echo ;
		$hasil = false;
		$table = ($varRts =='rts') ? 'pbdt2015_rts':'pbdt2015_idv';
		$strSQL = "SELECT DISTINCT(`".$varIndikator."`) as opsi,COUNT(`id`) as numrows FROM ".$table." WHERE `kode_wilayah` LIKE '".fixSQL($varKode)."%' GROUP BY `".$varIndikator."`";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['opsi']] = $rs['numrows'];
				}
				$hasil = $data;
			}
		}
		return $hasil;
	}

	function data_rts_mana_by_value_indikator_by_area($varIndikator,$varOpsi,$varKode){
		$hasil =false;
		$indikator = $this->pbdt2015_indikator('rts');
		$strSQL = "SELECT * FROM `pbdt2015_rts` WHERE `".$indikator['rts'][$varIndikator]['nama']."`= '".fixSQL($varOpsi)."' AND `kode_wilayah` LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}
	function data_art_mana_by_value_indikator_by_area($varIndikator,$varOpsi,$varKode){
		$hasil =false;
		$indikator = $this->pbdt2015_indikator('art');
		$strSQL = "SELECT * FROM `pbdt2015_idv` WHERE `".$indikator['art'][$varIndikator]['nama']."`= '".fixSQL($varOpsi)."' AND `kode_wilayah` LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}

}