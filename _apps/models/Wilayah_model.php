<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	
	public function list_subwilayah($varKode="",$varTingkat=1){
		$hasil = array();
		$strSQL = "SELECT kode,nama,tingkat,path FROM tweb_wilayah WHERE kode LIKE '".$varKode."%' AND tingkat='".$varTingkat."' ORDER BY nama";
		$query = $this->db->query($strSQL);
		foreach ($query->result() as $rs){
			$hasil[$rs->kode] = array(
				"nama"=>$rs->nama,
				"kode"=>$rs->kode,
				"tingkat"=>$rs->tingkat,
				"path"=>$rs->path);
		}
		return $hasil;
	}

	public function list_desa($varKode=""){
		$hasil = array();
		$tingkatan = _tingkat_wilayah();
		$strSQL = "SELECT w.kode,w.nama,w.tingkat,w.is_desa as isDesa,w.`path`,
			(SELECT c.base_url FROM tweb_situs c WHERE c.kode_base=w.kode) as `sites`
		FROM tweb_wilayah w
		WHERE kode LIKE '".$varKode."%' AND tingkat='4' ORDER BY kode";
		$query = $this->db->query($strSQL);
		
		foreach ($query->result() as $rs){
			$desa = ($rs->isDesa == 1) ? "Desa ":"Kelurahan";
			$hasil[substr($rs->kode,0,7)][$rs->kode] = array(
				"nama"=>$desa." ".$rs->nama,
				"kode"=>$rs->kode,
				"tingkat"=>$rs->tingkat,
				"sites"=>$rs->sites,
				"path"=>$rs->path);
		}
		return $hasil;
	}

	function list_alamat_detail($varKode){
		if(strlen($varKode)>0){
			$dusun = array();
			$rw = array();
			$rt = array();

			$strSQL = "SELECT nama,tingkat,kode FROM `tweb_wilayah` WHERE kode LIKE '".$varKode."%' AND tingkat > 4";
			$query = $this->db->query($strSQL);
			foreach ($query->result_array() as $rs){
				
				if($rs['tingkat'] == 5){
					$dusun[$rs['kode']] = $rs['nama'];
				}
				if($rs['tingkat'] == 6){
					$rws[$rs['kode']] = $rs['nama'];
				}
				if($rs['tingkat'] == 7){
					$rts[$rs['kode']] = array(
						'dusun' => substr($rs['kode'],0,12),
						'rw' => substr($rs['kode'],0,15),
						'rt' => $rs['nama']
					);
				}
			}
			$data = array();
			foreach($rts as $key=>$rt){
				$the_dusun = (array_key_exists($rt['dusun'],$dusun)) ? $dusun[$rt['dusun']]:"";
				$data[$key] = ucwords($the_dusun) ." - 
					RW ".$rws[$rt['rw']] ." / 
					RT ".$rt['rt'];
			}
			
			return $data;
		}else{
			return false;
		}
		
	}
	
	function list_rt_by_kel($varKode){
		$list_rw = array();$list_rt = array();
		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=6 AND kode LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		foreach ($query->result_array() as $rs){
			$list_rw[$rs['kode']] = $rs['nama'];
		}
		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=7 AND kode LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		foreach ($query->result_array() as $rs){
			$list_rt[substr($rs['kode'],0,14)][$rs['kode']] = $rs['nama'];
		}
		$hasil = array('rw'=>$list_rw,'rt'=>$list_rt);
		return $hasil;
	}
	public function subwilayah($varKode=""){
		$hasil = array();
		$wilayah = $this->wilayah($varKode);
		$tingkat=$wilayah['tingkat']+1;
		$strSQL = "SELECT kode,nama,tingkat,path 
			FROM tweb_wilayah 
			WHERE kode LIKE '".$varKode."%' AND tingkat='".$tingkat."' ORDER BY nama";
		$query = $this->db->query($strSQL);
		foreach ($query->result() as $rs){
			$hasil[$rs->kode] = array(
				"nama"=>$rs->nama,
				"tingkat"=>$rs->tingkat,
				"path"=>$rs->path,
				"kode"=>$rs->kode);
		}
		return $hasil;
	}

	function statistik_sub_wilayah($varKode=""){
		$hasil = array();
		$wilayah = $this->wilayah($varKode);
		$tingkat=$wilayah['tingkat']+1;
		$strSQL = "SELECT w.id,w.kode,w.nama,w.tingkat,w.`path`,
			(SELECT COUNT(id) FROM tweb_rumahtangga WHERE kode_wilayah LIKE CONCAT(w.kode,'%') ) as sumRTS,
			(SELECT COUNT(id) FROM tweb_keluarga WHERE kode_wilayah LIKE CONCAT(w.kode,'%') ) as sumKK,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE kode_wilayah LIKE CONCAT(w.kode,'%') ) as sumART

			FROM tweb_wilayah w
			WHERE w.kode LIKE '".$varKode."%' AND w.tingkat='".$tingkat."' ORDER BY w.nama";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		foreach ($query->result() as $rs){
			$hasil[$rs->kode] = array(
				"id"=>$rs->id,
				"nama"=>$rs->nama,
				"tingkat"=>$rs->tingkat,
				"path"=>$rs->path,
				"kode"=>$rs->kode,
				"sum_rts"=>$rs->sumRTS,
				"sum_kk"=>$rs->sumKK,
				"sum_art"=>$rs->sumART,
			);
		}
		return $hasil;
	}
	
	function wilayah_export($varKode){
		$strSQL = "SELECT kode,nama,tingkat,path FROM tweb_wilayah WHERE kode LIKE '".$varKode."%' ORDER BY kode";
		$query = $this->db->query($strSQL);
		
		foreach ($query->result() as $rs){
			$hasil[$rs->kode] = array("nama"=>$rs->nama,"tingkat"=>$rs->tingkat,"path"=>$rs->path);
		}
		return $hasil;
	}

	function wilayah($varKode){
		$hasil = false;
		$strSQL = "SELECT nama,tingkat,kode FROM tweb_wilayah WHERE kode='".$varKode."'";
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->result_array()[0];
				$hasil  = array("tingkat"=>$rs['tingkat'],
				"nama"=>$rs['nama'],
				"kode"=>$rs['kode']);
			}
		}
		return $hasil;
	}
	
	function get_wilayah($varKode){
		$hasil =false;
		$tingkatan = _tingkat_wilayah();
		$strSQL = "SELECT nama,tingkat FROM tweb_wilayah WHERE kode='".$varKode."'";
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->result_array()[0];
				$hasil  = $tingkatan[$rs['tingkat']]." ".$rs['nama'];
			}
		}				
		return $hasil;
	}
	
	function get_alamat($varKode){
		$hasil =false;
		$tingkatan = _tingkat_wilayah();
		$switch = strlen($varKode);
		
		//  .", ".$tingkatan[$rs['wtingkat1']]." ".$rs['wnama1'];
		switch ($switch)
		{
			case 2:
				$strSQL = "SELECT nama,tingkat FROM tweb_wilayah WHERE kode='".fixSQL($varKode)."' AND tingkat=1";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['tingkat']]." ".$rs['nama'];
					}
				}				
				break;

			case 4:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w1 ON w1.kode = SUBSTRING(w.kode,1,2)
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=2";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['wtingkat']]." ".$rs['wnama'] ;
					}
				}				
				
				break;

			case 7:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w2.nama as wnama2,w2.tingkat as wtingkat2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."'
					LEFT JOIN tweb_wilayah w1 ON w1.kode = SUBSTRING(w.kode,1,2)
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=3";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['wtingkat']]." ".$rs['wnama'] .", ".$tingkatan[$rs['wtingkat2']]." ".$rs['wnama2'] ;
					}
				}
				break;

			case 10:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w3.nama as wnama3,w3.tingkat as wtingkat3, 
					w2.nama as wnama2,w2.tingkat as wtingkat2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = SUBSTRING(w.kode,1,2)
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=4";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['wtingkat']]." ".$rs['wnama'] .", ".$tingkatan[$rs['wtingkat3']]." ".$rs['wnama3'] .", ".$tingkatan[$rs['wtingkat2']]." ".$rs['wnama2'] ;
					}
				}
				
				break;

			case 12:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w3.nama as wnama3,w3.tingkat as wtingkat3, 
					w2.nama as wnama2,w2.tingkat as wtingkat2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = SUBSTRING(w.kode,1,2)
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=4";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['wtingkat']]." ".$rs['wnama'] .", ".$tingkatan[$rs['wtingkat3']]." ".$rs['wnama3'] .", ".$tingkatan[$rs['wtingkat2']]." ".$rs['wnama2'] ;
					}
				}
				break;
				
			case 14:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w5.nama as wnama5,w5.tingkat as wtingkat5, 
					w3.nama as wnama3,w3.tingkat as wtingkat3, 
					w2.nama as wnama2,w2.tingkat as wtingkat2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w5 ON w5.kode = '".substr($varKode,0,10)."' AND w5.tingkat=4
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = SUBSTRING(w.kode,1,2)
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=6";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['wtingkat']]." ".$rs['wnama'] .", ".$tingkatan[$rs['wtingkat5']]." ".$rs['wnama5'] .", ".$tingkatan[$rs['wtingkat3']]." ".$rs['wnama3'] .", ".$tingkatan[$rs['wtingkat2']]." ".$rs['wnama2'] ;
					}
				}
				break;
				
			case 16:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w6.nama as wnama6,w6.tingkat as wtingkat6, 
					w5.nama as wnama5,w5.tingkat as wtingkat5, 
					w3.nama as wnama3,w3.tingkat as wtingkat3, 
					w2.nama as wnama2,w2.tingkat as wtingkat2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w6 ON w6.kode = '".substr($varKode,0,14)."' AND w6.tingkat=6
					LEFT JOIN tweb_wilayah w5 ON w5.kode = '".substr($varKode,0,10)."' AND w5.tingkat=4
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = SUBSTRING(w.kode,1,2)
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=7";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = $tingkatan[$rs['wtingkat']]." ".$rs['wnama'] .", ".$tingkatan[$rs['wtingkat6']]." ".$rs['wnama6'] .", ".$tingkatan[$rs['wtingkat5']]." ".$rs['wnama5'] .", ".$tingkatan[$rs['wtingkat3']]." ".$rs['wnama3'] .", ".$tingkatan[$rs['wtingkat2']]." ".$rs['wnama2'] ;
					}
				}
				break;
								
			default:
				
		}
		return $hasil;
	}
	

	function alamat_bc($varKode){
		$hasil =false;
		$tingkatan = _tingkat_wilayah();
		$switch = strlen($varKode);
		
		switch ($switch)
		{
			case 2:
				$strSQL = "SELECT nama,tingkat,kode FROM tweb_wilayah WHERE kode='".fixSQL($varKode)."' AND tingkat=1";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil[$rs['kode']]  = array("kode"=>$rs['kode'],"nama"=>$tingkatan[$rs['tingkat']]." ".$rs['nama']);
					}
				}				
				break;

			case 4:
				$strSQL = "
				SELECT 
					w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w1.kode as wkode1,w1.nama as wnama1,w1.tingkat as wtingkat1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w1 ON w1.kode = '".substr($varKode,0,2)."'
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=2";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = array(
						array("kode"=>$rs['wkode'],"nama"=>$tingkatan[$rs['wtingkat']]." ".strtoupper($rs['wnama'])),
						);
					}
				}				
				
				break;

			case 7:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w2.nama as wnama2,w2.tingkat as wtingkat2,w2.kode as wkode2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1,w1.kode as wkode1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."'
					LEFT JOIN tweb_wilayah w1 ON w1.kode = '".substr($varKode,0,2)."'
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=3";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = array(
						array("kode"=>$rs['wkode2'],"nama"=>$tingkatan[$rs['wtingkat2']]." ".strtoupper($rs['wnama2'])),
						array("kode"=>$rs['wkode'],"nama"=>$tingkatan[$rs['wtingkat']]." ".strtoupper($rs['wnama'])),
						);
					}
				}
				break;

			case 10:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w3.nama as wnama3,w3.tingkat as wtingkat3,w3.kode as wkode3, 
					w2.nama as wnama2,w2.tingkat as wtingkat2,w2.kode as wkode2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1,w1.kode as wkode1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = '".substr($varKode,0,2)."'
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=4";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = array(
						array("kode"=>$rs['wkode2'],"nama"=>$tingkatan[$rs['wtingkat2']]." ".strtoupper($rs['wnama2'])),
						array("kode"=>$rs['wkode3'],"nama"=>$tingkatan[$rs['wtingkat3']]." ".strtoupper($rs['wnama3'])),
						array("kode"=>$rs['wkode'],"nama"=>$tingkatan[$rs['wtingkat']]." ".strtoupper($rs['wnama'])),
						);

					}
				}
				
				break;

			case 12:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w4.nama as wnama4,w4.tingkat as wtingkat4,w4.kode as wkode4, 
					w3.nama as wnama3,w3.tingkat as wtingkat3,w3.kode as wkode3, 
					w2.nama as wnama2,w2.tingkat as wtingkat2,w2.kode as wkode2, 
					w1.nama as wnama1,w1.tingkat as wtingkat1,w1.kode as wkode1  
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w4 ON w4.kode = '".substr($varKode,0,10)."' AND w4.tingkat=4
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = '".substr($varKode,0,2)."'
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=5";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
						$hasil  = array(
						array("kode"=>$rs['wkode2'],"nama"=>$tingkatan[$rs['wtingkat2']]." ".strtoupper($rs['wnama2'])),
						array("kode"=>$rs['wkode3'],"nama"=>$tingkatan[$rs['wtingkat3']]." ".strtoupper($rs['wnama3'])),
						array("kode"=>$rs['wkode4'],"nama"=>$tingkatan[$rs['wtingkat4']]." ".strtoupper($rs['wnama4'])),
						array("kode"=>$rs['wkode'],"nama"=>$tingkatan[$rs['wtingkat']]." ".strtoupper($rs['wnama'])),
						);
						
					}
				}
				break;
				
			case 15:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w5.nama as wnama5,w5.tingkat as wtingkat5,w5.kode as wkode5, 
					w4.nama as wnama4,w4.tingkat as wtingkat4,w4.kode as wkode4, 
					w3.nama as wnama3,w3.tingkat as wtingkat3,w3.kode as wkode3,  
					w2.nama as wnama2,w2.tingkat as wtingkat2,w2.kode as wkode2,  
					w1.nama as wnama1,w1.tingkat as wtingkat1,w1.kode as wkode1 
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w5 ON w5.kode = '".substr($varKode,0,12)."' AND w5.tingkat=5
					LEFT JOIN tweb_wilayah w4 ON w4.kode = '".substr($varKode,0,10)."' AND w4.tingkat=4
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = '".substr($varKode,0,2)."'
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=6";
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
//						array("kode"=>$rs['wkode1'],"nama"=>$tingkatan[$rs['wtingkat1']]." ".$rs['wnama1']),
						$hasil  = array(
						array("kode"=>$rs['wkode2'],"nama"=>$tingkatan[$rs['wtingkat2']]." ".strtoupper($rs['wnama2'])),
						array("kode"=>$rs['wkode3'],"nama"=>$tingkatan[$rs['wtingkat3']]." ".strtoupper($rs['wnama3'])),
						array("kode"=>$rs['wkode4'],"nama"=>$tingkatan[$rs['wtingkat4']]." ".strtoupper($rs['wnama4'])),
						array("kode"=>$rs['wkode5'],"nama"=>$tingkatan[$rs['wtingkat5']]." ".strtoupper($rs['wnama5'])),
						array("kode"=>$rs['wkode'],"nama"=>$tingkatan[$rs['wtingkat']]." ".strtoupper($rs['wnama'])),
						);
					}
				}
				break;
				
			case 18:
				$strSQL = "
				SELECT w.nama as wnama,w.tingkat as wtingkat,w.kode as wkode,
					w6.nama as wnama6,w6.tingkat as wtingkat6, w6.kode as wkode6,
					w5.nama as wnama5,w5.tingkat as wtingkat5,w5.kode as wkode5,
					w4.nama as wnama4,w4.tingkat as wtingkat4,w4.kode as wkode4,
					w3.nama as wnama3,w3.tingkat as wtingkat3, w3.kode as wkode3,
					w2.nama as wnama2,w2.tingkat as wtingkat2, w2.kode as wkode2,
					w1.nama as wnama1,w1.tingkat as wtingkat1, w1.kode as wkode1
				FROM tweb_wilayah w 
					LEFT JOIN tweb_wilayah w6 ON w6.kode = '".substr($varKode,0,15)."' AND w6.tingkat=6
					LEFT JOIN tweb_wilayah w5 ON w5.kode = '".substr($varKode,0,12)."' AND w5.tingkat=5
					LEFT JOIN tweb_wilayah w4 ON w4.kode = '".substr($varKode,0,10)."' AND w4.tingkat=4
					LEFT JOIN tweb_wilayah w3 ON w3.kode = '".substr($varKode,0,7)."' AND w3.tingkat=3
					LEFT JOIN tweb_wilayah w2 ON w2.kode = '".substr($varKode,0,4)."' AND w2.tingkat=2
					LEFT JOIN tweb_wilayah w1 ON w1.kode = '".substr($varKode,0,2)."'
				WHERE w.kode='".fixSQL($varKode)."' AND w.tingkat=7";
				// echo $strSQL;
				$result = $this->db->query($strSQL);
				if($result){
					if($result->num_rows() > 0){
						$rs = $result->result_array()[0];
//						array("kode"=>$rs['wkode1'],"nama"=>$tingkatan[$rs['wtingkat1']]." ".$rs['wnama1']),
						$hasil  = array(
						array("kode"=>$rs['wkode2'],"nama"=>$tingkatan[$rs['wtingkat2']]." ".strtoupper($rs['wnama2'])),
						array("kode"=>$rs['wkode3'],"nama"=>$tingkatan[$rs['wtingkat3']]." ".strtoupper($rs['wnama3'])),
						array("kode"=>$rs['wkode4'],"nama"=>$tingkatan[$rs['wtingkat4']]." ".strtoupper($rs['wnama4'])),
						array("kode"=>$rs['wkode5'],"nama"=>$tingkatan[$rs['wtingkat5']]." ".strtoupper($rs['wnama5'])),
						array("kode"=>$rs['wkode6'],"nama"=>$tingkatan[$rs['wtingkat6']]." ".strtoupper($rs['wnama6'])),
						array("kode"=>$rs['wkode'],"nama"=>$tingkatan[$rs['wtingkat']]." ".strtoupper($rs['wnama'])),
						);
					}
				}
				break;
								
			default:
				
		}
		// echo $strSQL;
		return $hasil;
	}	
	
	function data_wilayah($varKode){
		$tingkatan = _tingkat_wilayah();
		$hasil = false;
		$strSQL = "SELECT id,nama,tingkat FROM tweb_wilayah WHERE kode='".fixSQL($varKode)."' LIMIT 1";
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->row();
				
				$tingkat = $rs->tingkat+1;
				
				$nama = $rs->nama;
				$result = ""; $rs= "";
				
				$subWilayah = array();
				$strSQL = "SELECT nama,kode,tingkat,path FROM tweb_wilayah WHERE tingkat=".$tingkat." AND kode LIKE '".fixSQL($varKode)."%'";
				$query = $this->db->query($strSQL);
				foreach ($query->result() as $rs){
					$subWilayah[$rs->kode] = array(
						"nama"=>$tingkatan[$rs->tingkat]." ".$rs->nama,
						"tingkat"=>$rs->tingkat,
						"path"=>$rs->path);
				}
				$hasil = array(
					"nama"=>$nama, 
					"tingkat"=>$tingkat, 
					"tingkatan"=>$tingkatan[$tingkat], 
					"sub"=>$subWilayah,
					);
			}
		}
		return $hasil;
	}
	
}
