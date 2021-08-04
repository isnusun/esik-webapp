<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rumahtangga_model extends CI_Model {
	function list_rumahtangga_by_wilayah($varKode=0,$varPage=0){
		$hasil = false;
		if($varKode > 0){
			$strSQL = "SELECT r.id, r.idbdt,r.nama_kepala_rumah_tangga,
			(SELECT COUNT(id) FROM tweb_keluarga WHERE idbdt=r.idbdt) as sumKK, 
			(SELECT COUNT(id) FROM tweb_penduduk WHERE idbdt=r.idbdt) as sumART
			FROM tweb_rumahtangga r 
			WHERE r.kode_wilayah LIKE '".$varKode."%'";
			// echo $strSQL;
			if($varPage > 0){
				$limit = 25;
				$offset = ($varPage - 1 ) * $limit;
				$strSQL .= " LIMIT ".$offset.", ".$limit;
			}
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}

	function rumahtangga_by_id($varID=0){
		$hasil = false;
		if($varID > 0){

			$strSQL = "SELECT r.id, r.idbdt,r.nama_kepala_rumah_tangga,r.kode_wilayah,r.var_lat,r.var_lon FROM tweb_rumahtangga r WHERE r.idbdt='".$varID."'";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$rumahtangga = $query->result_array()[0];
				$keluarga = array();
				$art = array();
				$strSQL = "SELECT * FROM tweb_keluarga WHERE idbdt='".fixSQL($rumahtangga['idbdt'])."'";
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0){
					$i=0;
					$utama = 0;
					foreach ($query->result_array() as $key => $rs) {
						if($rs['utama']==1){
							$utama = $rs['id'];
						}
						if($i==0){
							$idutama = $rs['id'];
						}
						$keluarga[$rs['id']] = $rs;
						$i++;
					}
					if($utama == 0){
						$strSQL = "UPDATE tweb_keluarga SET utama=1 WHERE id=".$idutama;
						$query = $this->db->query($strSQL);
						if($query){
							$new_keluarga = array();
							foreach($keluarga as $key=>$rs){
								if($key == $idutama){
									$temp_keluarga = array();
									foreach($rs as $k=>$r){
										if($k=='utama'){
											$temp_keluarga = array('utama'=>1);
										}else{
											$temp_keluarga = array($k=>$r);
										}
									}
								}else{
									$new_keluarga[$key] = $rs;
								}
								array_merge($new_keluarga,$temp_keluarga);
							}
						}
					}else{
						$new_keluarga = $keluarga;
					}

				}
				$strSQL = "SELECT * FROM tweb_penduduk WHERE idbdt='".fixSQL($rumahtangga['idbdt'])."'";
				$query = $this->db->query($strSQL);
				if($query->num_rows() > 0){
					$art = $query->result_array();
				}
				$hasil = array(
					'rumahtangga'=>$rumahtangga,
					'kartu_keluarga'=>$new_keluarga,
					'anggota'=>$art,
				);
	
			}
		}
		return $hasil;

	}

	function status_data_bdt_by_periode($varIDBDT=0){
		$hasil =false;
		if($varIDBDT > 0){
			$strSQL = "SELECT DISTINCT(periode_id),COUNT(lead_id),percentile FROM bdt_rts WHERE idbdt='".fixSQL($varIDBDT)."' GROUP BY periode_id";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				foreach ($query->result_array() as $key => $value) {
					# code...
					$data[$value['periode_id']] = $value;
				}
				$hasil = $data;
			}
		}
		return $hasil;
	}
}
