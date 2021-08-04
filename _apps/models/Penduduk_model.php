<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penduduk_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	function rtm_load($varRTM=''){
		$hasil=false;
		if($varRTM){
			$strSQL = "SELECT r.* ,
				d.alamat as alamat
			FROM tweb_rumahtangga r 
				LEFT JOIN bdt_rts d ON r.idbdt=d.idbdt
			WHERE r.idbdt='".fixSQL($varRTM)."' LIMIT 1";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$hasil = $query->result_array()[0];
				}
			}
		}
		return $hasil;
	}

	function rtm_load_kk($varRTM){
		$hasil=false;
		if($varRTM){
			$strSQL = "SELECT k.*  FROM tweb_keluarga k
			WHERE k.idbdt='".fixSQL($varRTM)."'";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$hasil = $query->result_array()[0];
				}
			}
		}
		return $hasil;
	}

	function rtm_load_art($varRTM){
		$hasil=false;
		if($varRTM){
			$strSQL = "SELECT k.*  FROM tweb_penduduk k 
			WHERE k.idbdt='".fixSQL($varRTM)."'";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$hasil = $query->result_array();
				}
			}
		}
		return $hasil;
	}
	
}