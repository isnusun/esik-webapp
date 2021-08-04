<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	function get_all_sites(){
		$hasil = array();
		$strSQL = "SELECT * FROM tweb_situs ORDER BY id";
		$query = $this->db->query($strSQL);
		foreach ($query->result_array() as $rs){
			$hasil[$rs['id']] = $rs;
		}
		return $hasil;
	}	

}
