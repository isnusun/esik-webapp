<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrasi_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function data_art($varKode=0){
		$hasil = false;
		$strSQL = "SELECT p.id,p.nama,p.nik,p.kk_nomor,p.idartbdt,p.idbdt,p.ruta6,
			r.alamat AS alamat  
		FROM tweb_penduduk p 
			LEFT JOIN bdt_rts r ON p.idbdt=r.idbdt
		WHERE p.kode_wilayah LIKE '".fixSQL($varKode)."%'";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$hasil = array();
			foreach ($query->result_array() as $rs){
				$hasil[$rs['id']] = $rs;
			}			
		}
		return $hasil;
	}
	function data_kk($varKode=0){
		$hasil = false;
		$strSQL = "SELECT p.id,p.nama_kepala_keluarga as nama,p.nik,p.kk_nomor,p.idartbdt,p.idbdt,p.ruta6,
			r.alamat AS alamat  
		FROM tweb_keluarga p 
			LEFT JOIN bdt_rts r ON p.idbdt=r.idbdt
		WHERE p.kode_wilayah LIKE '".fixSQL($varKode)."%'";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$hasil = array();
			foreach ($query->result_array() as $rs){
				$hasil[$rs['id']] = $rs;
			}			
		}
		return $hasil;
	}
	function data_rts($varKode=0){
		$hasil = false;
		$strSQL = "SELECT p.id,p.nama_kepala_rumah_tangga as nama,p.idbdt,p.ruta6,
			r.alamat AS alamat  
		FROM tweb_rumahtangga p 
			LEFT JOIN bdt_rts r ON p.idbdt=r.idbdt
		WHERE p.kode_wilayah LIKE '".fixSQL($varKode)."%'";
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$hasil = array();
			foreach ($query->result_array() as $rs){
				$hasil[$rs['id']] = $rs;
			}			
		}
		return $hasil;
	}
		
}

