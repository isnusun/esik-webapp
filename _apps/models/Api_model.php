<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	function auth($username,$password){
		$hasil = array(
			'success'=>false,
			'msg'=>'Username dan atau Password tidak sesuai/terdaftar'
		);
		$strSQL = 
		"SELECT u.id,u.nama,u.passwt,u.tingkat,u.wilayah as wilayah_kerja,
			w.nama as wnama, w.tingkat as wtingkat 
		FROM tweb_users u 
			LEFT JOIN tweb_wilayah w ON u.wilayah = w.kode 
		WHERE ((u.email='".fixSQL($username)."') OR (u.userid='".fixSQL($username)."')) AND u.status=1";

		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->row();
				if(password_verify($password,$rs->passwt)){
					/**
					 * Log last login
					 *  */ 
					$strSQL = "UPDATE tweb_users SET login_at=NOW(),login_from='".$_SERVER['REMOTE_ADDR']."' WHERE id=".$rs->id;
					$this->db->query($strSQL);
					$hasil = array(
						'success'=>true,
						'wilayah_kerja'=>$rs->wilayah_kerja,
						'msg'=>'Login berhasil'
					);
				}
			}
		}
		return $hasil;
	}
}
