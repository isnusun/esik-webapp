<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbdt_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	function pbdt_periode($varID=0){
		$data = false;
		if($varID > 0){
			$strSQL = "SELECT * FROM pbdt_periode WHERE id=".$varID;
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$data = $query->result_array()[0];
			}
		}else{
			$id_aktif = 0;
			$last_archived = 0;
			$strSQL = "SELECT * FROM pbdt_periode ORDER BY sdate DESC";
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
			
			$strSQL = "SELECT id FROM pbdt_periode WHERE status=0 ORDER BY sdate DESC";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$last_archived = $rs['id'];
			}
			
			
			$data= array("periode"=>$hasil,"periode_aktif"=>$id_aktif,"last_archived"=>$last_archived);
			
		}
		return $data;
	}

	function pbdt_last3periode(){
		$hasil = false;

		$strSQL  = "SELECT id,nama,sdate,edate FROM pbdt_periode ORDER BY id DESC LIMIT 3";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$hasil =array();
			foreach ($query->result_array() as $rs){
				$hasil[$rs["id"]] = $rs;
			}			
		}
		return $hasil;
	}


	function pbdt_klasifikasi(){

		$hasil = false;
		$strSQL = "SELECT * FROM pbdt_klasifikasi ORDER BY id";
		$query = $this->db->query($strSQL);
		if($query->num_rows()>0){
			$hasil = array();
			foreach($query->result_array() as $row){
				$hasil[$row["id"]] = array("id"=>$row["id"],"nama"=>$row["nama"],"skor_min"=>$row["skor_min"], "skor_max"=>$row["skor_max"]);
			}
		}
		return $hasil;
	}	
	
	function pbdt_param($varID=0){
		$hasil = false;
		if($varID > 0){
			$strSQL = "SELECT * FROM pbdt_param WHERE id=".$varID;
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = $query->result_array()[0];
			}
		}else{
			$strSQL = "SELECT * FROM pbdt_param WHERE 1 ORDER BY id";
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}

	function pbdt_opsi($varID=0){
		$hasil = false;
		if($varID > 0){
			$strSQL = "SELECT * FROM pbdt_param_opsi WHERE param_id=".$varID."ORDER BY p.id";
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = array();
				foreach ($query->result_array() as $row){
					$hasil[$row['id']] = $row;
				}
			}
		}else{
			$strSQL = "SELECT p.* FROM pbdt_param_opsi p ORDER BY p.id";
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = array();
				foreach ($query->result_array() as $row){
					$hasil[$row['param_id']][$row['opsi_kode']] = $row;
				}
			}
		}
		return $hasil;
	}
	
	function pbdt_param_idv($varID=0){
		$hasil = false;
		if($varID > 0){
			$strSQL = "SELECT * FROM pbdt_param_idv WHERE id=".$varID;
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = $query->result_array()[0];
			}
		}else{
			$strSQL = "SELECT * FROM pbdt_param_idv WHERE 1 ORDER BY id";
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}

	function pbdt_opsi_idv($varID=0){
		$hasil = false;
		if($varID > 0){
			$strSQL = "SELECT * FROM pbdt_param_opsi_idv WHERE param_id=".$varID."ORDER BY p.id";
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = array();
				foreach ($query->result_array() as $row){
					$hasil[$row['id']] = $row;
				}
			}
		}else{
			$strSQL = "SELECT p.* FROM pbdt_param_opsi_idv p ORDER BY p.id";
			$query = $this->db->query($strSQL);
			if($query->num_rows()>0){
				$hasil = array();
				foreach ($query->result_array() as $row){
					$hasil[$row['param_id']][$row['opsi_kode']] = $row;
				}
			}
		}
		return $hasil;
	}
	function rangkuman_by_wilayah($varKode,$varPeriode=1){
		$hasil = false;
		$varL = strlen($varKode);
		switch ($varL)
		{
			case 14:
				$param = array(0=>'rt_kode',1=>7);
				break;
			case 12:
				$param = array(0=>'rw_kode',1=>6);
				break;
			case 10:
				$param = array(0=>'dusun_kode',1=>5);
				break;
			case 7:
				$param = array(0=>'desa_kode',1=>4);
				break;
			default:
				$param = array(0=>'kecamatan_kode',1=>3);
		}
		
				/*
				 * Data RTM
				 * */
				$strSQL = "
					SELECT w.kode,w.nama,
						(SELECT COUNT(id) FROM pbdt_rt WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=1)) as `1`,
						(SELECT COUNT(id) FROM pbdt_rt WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=2)) as `2`,
						(SELECT COUNT(id) FROM pbdt_rt WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=3)) as `3`,
						(SELECT COUNT(id) FROM pbdt_rt WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=4)) as `4`
					FROM tweb_wilayah w
					WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$param[1]."";

				$data_kk = array();
				$query = $this->db->query($strSQL);
				if($query){
					if($query->num_rows() > 0){
						foreach($query->result_array() as $rs){
							$data_kk[$rs['kode']] = $rs;
						}
					}
				}
				/*
				 * Data IDV
				 * */
				$strSQL = "
					SELECT w.kode,w.nama,
						(SELECT COUNT(id) FROM pbdt_idv WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=1)) as `1`,
						(SELECT COUNT(id) FROM pbdt_idv WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=2)) as `2`,
						(SELECT COUNT(id) FROM pbdt_idv WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' AND (status_kesejahteraan=3)) as `3`,
						(SELECT COUNT(id) FROM pbdt_idv WHERE (".$param[0]."=w.kode) AND periode_id='".$varPeriode."' 	AND (status_kesejahteraan=4)) as `4` 
					FROM tweb_wilayah w
					WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$param[1]."";
					//echo $strSQL;
				$data_idv = array();
				$query = $this->db->query($strSQL);
				if($query){
					if($query->num_rows() > 0){
						foreach($query->result_array() as $rs){
							$data_idv[$rs['kode']] = $rs;
						}
					}
				}
						
		$hasil = array('kk'=>$data_kk,'idv'=>$data_idv);
		return $hasil;
	}		

	function data_by_indikator_flat($varID = 0,$varKode=0){
		// $varID = param_id 
		$last_3periode = $this->pbdt_last3periode();
		$kode = ($varKode == 0)? KODE_BASE : $varKode;

		$klausul = "kabupaten_kode='".$kode."'";
		// echo $kode ." - ".strlen($kode);
		switch (strlen($kode))
		{
			case 16:
				$klausul = "rt_kode='".$kode."'";
				break;
			case 14:
				$klausul = "rw_kode='".$kode."'";
				break;
			case 12:
				$klausul = "dusun_kode='".$kode."'";
				break;
			case 10:
				$klausul = "desa_kode='".$kode."'";
				break;
			case 7:
				$klausul = "kecamatan_kode='".$kode."'";
				break;
			default:
				$klausul = "kabupaten_kode='".$kode."'";
		}
		
		$hasil = array();
		foreach($last_3periode as $periode_id=>$per){
			$strSQL = "
			SELECT DISTINCT(col_".$varID.") as opt_id,COUNT(id) as numrows 
			FROM `pbdt_rt` 
			WHERE ".$klausul." AND periode_id='".$periode_id."' GROUP bY col_".$varID."";
			
			$data = array();
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$data[$rs['opt_id']] = $rs['numrows'];
					}
				}
			}
			$hasil[$periode_id] = $data;
	
		}
		return $hasil;
	}

	

	function data_idv_by_indikator_flat($varID = 0,$varKode=0){
		// $varID = param_id 
		$last_3periode = $this->pbdt_last3periode();
		$kode = ($varKode == 0)? KODE_BASE : $varKode;

		$klausul = "kabupaten_kode='".$kode."'";
		// echo $kode ." - ".strlen($kode);
		switch (strlen($kode))
		{
			case 16:
				$klausul = "rt_kode='".$kode."'";
				break;
			case 14:
				$klausul = "rw_kode='".$kode."'";
				break;
			case 12:
				$klausul = "dusun_kode='".$kode."'";
				break;
			case 10:
				$klausul = "desa_kode='".$kode."'";
				break;
			case 7:
				$klausul = "kecamatan_kode='".$kode."'";
				break;
			default:
				$klausul = "kabupaten_kode='".$kode."'";
		}
		
		$hasil = array();
		foreach($last_3periode as $periode_id=>$per){
			$strSQL = "
			SELECT DISTINCT(col_".$varID.") as opt_id,COUNT(id) as numrows 
			FROM `pbdt_idv` 
			WHERE ".$klausul." AND periode_id='".$periode_id."' GROUP bY col_".$varID."";
			
			$data = array();
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$data[$rs['opt_id']] = $rs['numrows'];
					}
				}
			}
			$hasil[$periode_id] = $data;
	
		}
		return $hasil;
	}


	function data_rts_by_desil_wilayah_periode($varKelas=0,$varKode=0,$varPeriode=1){
		$data = false;
		$klasifikasi = $this->pbdt_klasifikasi();
		$kelas = $klasifikasi[$varKelas];
		
		$strL = strlen($varKode);
		
		switch ($strL)
		{
			case 16:
				$param = 'rt_kode';
				break;
			case 14:
				$param = 'rw_kode';
				break;
			case 12:
				$param = 'dusun_kode';
				break;
			case 10:
				$param = 'desa_kode';
				break;
			case 7:
				$param = 'kecamatan_kode';
				break;
			case 4:
				$param = 'kabupaten_kode';
				break;
			default:
				
		}
		
		
		$strSQL = "
		SELECT g.* FROM pbdt_rt g 
		WHERE g.".$param."='".$varKode."' 
			AND (g.status_kesejahteraan='".$varKelas."')";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$data[$rs['id']] = $rs;
				}
			}
		}else{
			$data = "error ".$strSQL;
		}
		//echo var_dump($data);
		return $data;

	}
	
	function get_rtm($varRID =0 ){
		if($varRID > 0){
			$strSQL = "SELECT * FROM tweb_rumahtangga WHERE rtm_no='".$varRID."'";
			// Member RTS
			$strSQL = "SELECT * FROM tweb_penduduk WHERE rtm_no='".$varRID."'";
		}
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

}
