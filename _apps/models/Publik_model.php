<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publik_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function base_config(){
		
	}
	function dalam_angka(){
		$sum_rtm = 0;
		$sum_art = 0;
		$strSQL = "SELECT COUNT(id) as sum_rtm FROM pbdt_rt WHERE 1";
		$query = $this->db->query($strSQL);
		if($query){
			$rs = $query->result_array()[0];
			$sum_rtm = $rs['sum_rtm'];
		}
		$strSQL = "SELECT COUNT(id) as sum_art FROM pbdt_idv WHERE 1";
		$query = $this->db->query($strSQL);
		if($query){
			$rs = $query->result_array()[0];
			$sum_art = $rs['sum_art'];
		}
		$hasil = array(
			'sum_rts'=>$sum_rtm, 
			'sum_art'=>$sum_art, 
		);
		return $hasil;

	}
	
}
