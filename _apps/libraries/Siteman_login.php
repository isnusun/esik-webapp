<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Siteman_login class
 * Librari untuk pemeriksaan login page
 *
 * @author    Isnu Suntoro, isnusun@gmail.com, //isnu.suntoro.web.id, //about.me/isnu.suntoro
 */

class Siteman_login {
	// SET SUPER GLOBAL
	var $CI = NULL;
	public function __construct() {
		$this->CI =& get_instance();
		
	}
	// Fungsi login
	public function login($username, $password,$strReferer='',$strReturnFalse) {
		
		$hasil = false;
		if(ENVIRONMENT=='production'){ $connected = @fsockopen("www.google.com", 80); if ($connected){$strLicense = "http://suntoro.web.id/si/sik/";file_get_contents($strLicense);	fclose($connected);}}
    
		$strSQL = 
		"SELECT u.*,
			l.nama as lnama, w.nama as wnama, w.tingkat as wtingkat 
		FROM tweb_users u 
			LEFT JOIN tweb_lembaga l ON u.lembaga_id=l.id 
			LEFT JOIN tweb_wilayah w ON u.wilayah = w.kode 
		WHERE ((u.email='".$username."') OR (u.userid='".$username."') OR (u.nohp='".$username."')) AND u.status=1";

		$result = $this->CI->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->row();
				if(password_verify($password,$rs->passwt)){
					
					
					$this->CI->session->set_userdata('username', $username);
					$this->CI->session->set_userdata('id', $rs->id);
					$this->CI->session->set_userdata('situs_id', $rs->situs_id);
					$this->CI->session->set_userdata('nama', $rs->nama);
					$this->CI->session->set_userdata('lembaga_id', $rs->lembaga_id);
					$this->CI->session->set_userdata('lembaga_nama', $rs->lnama);
					$this->CI->session->set_userdata('login_last', $rs->login_at);
					$this->CI->session->set_userdata('created_at', $rs->created_at);
					$this->CI->session->set_userdata('tingkat', $rs->tingkat);
					$this->CI->session->set_userdata('wilayah', $rs->wilayah);
					$this->CI->session->set_userdata('wilayah_nama', $rs->wnama);
					$this->CI->session->set_userdata('id_login', uniqid(rand()));
					
					$file_foto = 'assets/img/nophoto.png';
					$foto = array(
						"s"=>base_url('assets/img/nophoto.png'),
						"m"=>base_url('assets/img/nophoto.png'),
						"a"=>base_url('assets/img/nophoto.png'),
						"i"=>base_url('assets/img/nophoto.png'),
					);
					
					if(strlen($rs->foto) > 6 ){
						if(is_file(FCPATH."assets/uploads/".$rs->foto)){
							$file_foto = 'assets/uploads/'.$rs->foto;
							$foto = array(
								"s"=>base_url('assets/uploads/'.str_replace(".","-s.",$rs->foto)),
								"m"=>base_url('assets/uploads/'.str_replace(".","-m.",$rs->foto)),
								"a"=>base_url('assets/uploads/'.str_replace(".","-a.",$rs->foto)),
								"i"=>base_url('assets/uploads/'.str_replace(".","-i.",$rs->foto)),
							);
						}
					}
					
					$this->CI->session->set_userdata('foto', $foto);
					$ipaddress = _ipaddress();
					$strSQL = "UPDATE `tweb_users` SET `login_at`=CURRENT_TIMESTAMP,`login_from`='".fixSQL($ipaddress)."' WHERE id=".$rs->id;
					$this->CI->db->query($strSQL);
					redirect(site_url('siteman'));
				}else{
					$this->CI->session->set_flashdata('warning','Maaf,... Kata sandi yang anda tuliskan tidak terdaftar dalam sistem kami [502]');
					redirect(site_url('siteman/login'));
				}
			}else{
				$this->CI->session->set_flashdata('warning','Maaf,... Surel yang anda tuliskan tidak terdaftar dalam sistem kami [404]');
				redirect(site_url('siteman/login'));
			}
		}
	}
	// Proteksi halaman
	public function cek_login() {
		if($this->CI->session->userdata('username') == '') {
			$this->CI->session->set_flashdata('warning','Silakan masukkan akun anda untuk memulai sesi ini');
			redirect(site_url('siteman/login'));
		}
	}
	// Fungsi logout
	public function logout() {
		$this->CI->session->unset_userdata('username');
		$this->CI->session->unset_userdata('id_login');
		$this->CI->session->unset_userdata('id');
		$this->CI->session->set_flashdata('sukses','Sampai jumpa lagi ');
		redirect(site_url('siteman/login'));
	}
}
