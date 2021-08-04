<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	function get_user($username){
		$hasil = false;
		$strSQL = 
		"SELECT u.id,u.userid,u.nama,u.email,u.nohp,u.passwt,u.tingkat,u.wilayah,
			u.dtlahir,u.foto,
			w.nama as wnama, 
			w.tingkat as wtingkat 
		FROM tweb_users u 
			LEFT JOIN tweb_wilayah w ON u.wilayah = w.kode 
		WHERE ((u.email='".$username."') OR (u.userid='".$username."')) AND u.status=1 LIMIT 1";

		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->result_array()[0];
				$wilayah_kerja = $this->user_wilayah_kerja($rs['wilayah']);
				$file_foto = 'assets/img/nophoto.png';
				$foto = array(
					"s"=>base_url('assets/img/nophoto.png'),
					"m"=>base_url('assets/img/nophoto.png'),
					"a"=>base_url('assets/img/nophoto.png'),
					"i"=>base_url('assets/img/nophoto.png'),
				);
				
				if(strlen($rs['foto']) > 6 ){
					if(is_file(FCPATH."assets/uploads/".$rs['foto'])){
						$file_foto = 'assets/uploads/'.$rs['foto'];
						$foto = array(
							"s"=>base_url('assets/uploads/'.str_replace(".","-s.",$rs['foto'])),
							"m"=>base_url('assets/uploads/'.str_replace(".","-m.",$rs['foto'])),
							"a"=>base_url('assets/uploads/'.str_replace(".","-a.",$rs['foto'])),
							"i"=>base_url('assets/uploads/'.str_replace(".","-i.",$rs['foto'])),
						);
					}
				}

				$hasil = array(
						'id'=>$rs['id'],
						'userid'=>$rs['userid'],
						'nama'=>$rs['nama'],
						'email'=>$rs['email'],
						'nohp'=>$rs['nohp'],
						'foto'=>$foto,
						'level'=>$rs['tingkat'],
						'wilayah_kode'=>$rs['wilayah'],
						'wilayah_kerja'=>$wilayah_kerja,
				);
			}
		}
		return $hasil;
	}

	function user_wilayah_kerja($varKode){
		$rt = $rw = $dusun= $desa = $kecamatan = $kabupaten = $propinsi = null;
		
		$hasil = array(
			'RT'=>null,
			'RW'=>null,
			'Dusun/Lingkungan'=>null,
			'Desa/Kelurahan'=>null,
			'Kecamatan'=>null,
			'Kabupaten/Kota'=>null,
			'Propinsi'=>null,
			'Negara'=>array(
				'kode'=>'',
				'nama'=>'Republik Indonesia',
				'tingkat'=>0
			),
		);
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
						$propinsi = array(
							'kode'=>$rs['kode'],
							'nama'=>$rs['nama'],
							'tingkat'=>$rs['tingkat'],
						);
						// $hasil[$rs['kode']]  = array("kode"=>$rs['kode'],"nama"=>$tingkatan[$rs['tingkat']]." ".$rs['nama']);
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
						$propinsi = array(
							'kode'=>$rs['wkode1'],
							'nama'=>$rs['wnama1'],
							'tingkat'=>$rs['wtingkat1'],
						);
						$kabupaten = array(
							'kode'=>$rs['wkode'],
							'nama'=>$rs['wnama'],
							'tingkat'=>$rs['wtingkat'],
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
						$propinsi = array(
							'kode'=>$rs['wkode1'],
							'nama'=>$rs['wnama1'],
							'tingkat'=>$rs['wtingkat1'],
						);
						$kabupaten = array(
							'kode'=>$rs['wkode2'],
							'nama'=>$rs['wnama2'],
							'tingkat'=>$rs['wtingkat2'],
						);
						$kecamatan = array(
							'kode'=>$rs['wkode'],
							'nama'=>$rs['wnama'],
							'tingkat'=>$rs['wtingkat'],
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
						$propinsi = array(
							'kode'=>$rs['wkode1'],
							'nama'=>$rs['wnama1'],
							'tingkat'=>$rs['wtingkat1'],
						);
						$kabupaten = array(
							'kode'=>$rs['wkode2'],
							'nama'=>$rs['wnama2'],
							'tingkat'=>$rs['wtingkat2'],
						);
						$kecamatan = array(
							'kode'=>$rs['wkode3'],
							'nama'=>$rs['wnama3'],
							'tingkat'=>$rs['wtingkat3'],
						);
						$desa = array(
							'kode'=>$rs['wkode'],
							'nama'=>$rs['wnama'],
							'tingkat'=>$rs['wtingkat'],
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
						$propinsi = array(
							'kode'=>$rs['wkode1'],
							'nama'=>$rs['wnama1'],
							'tingkat'=>$rs['wtingkat1'],
						);
						$kabupaten = array(
							'kode'=>$rs['wkode2'],
							'nama'=>$rs['wnama2'],
							'tingkat'=>$rs['wtingkat2'],
						);
						$kecamatan = array(
							'kode'=>$rs['wkode3'],
							'nama'=>$rs['wnama3'],
							'tingkat'=>$rs['wtingkat3'],
						);
						$desa = array(
							'kode'=>$rs['wkode4'],
							'nama'=>$rs['wnama4'],
							'tingkat'=>$rs['wtingkat4'],
						);
						$dusun = array(
							'kode'=>$rs['wkode'],
							'nama'=>$rs['wnama'],
							'tingkat'=>$rs['wtingkat'],
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
						$propinsi = array(
							'kode'=>$rs['wkode1'],
							'nama'=>$rs['wnama1'],
							'tingkat'=>$rs['wtingkat1'],
						);
						$kabupaten = array(
							'kode'=>$rs['wkode2'],
							'nama'=>$rs['wnama2'],
							'tingkat'=>$rs['wtingkat2'],
						);
						$kecamatan = array(
							'kode'=>$rs['wkode3'],
							'nama'=>$rs['wnama3'],
							'tingkat'=>$rs['wtingkat3'],
						);
						$desa = array(
							'kode'=>$rs['wkode4'],
							'nama'=>$rs['wnama4'],
							'tingkat'=>$rs['wtingkat4'],
						);
						$dusun = array(
							'kode'=>$rs['wkode5'],
							'nama'=>$rs['wnama5'],
							'tingkat'=>$rs['wtingkat5'],
						);
						$rw = array(
							'kode'=>$rs['wkode'],
							'nama'=>$rs['wnama'],
							'tingkat'=>$rs['wtingkat'],
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
						$propinsi = array(
							'kode'=>$rs['wkode1'],
							'nama'=>$rs['wnama1'],
							'tingkat'=>$rs['wtingkat1'],
						);
						$kabupaten = array(
							'kode'=>$rs['wkode2'],
							'nama'=>$rs['wnama2'],
							'tingkat'=>$rs['wtingkat2'],
						);
						$kecamatan = array(
							'kode'=>$rs['wkode3'],
							'nama'=>$rs['wnama3'],
							'tingkat'=>$rs['wtingkat3'],
						);
						$desa = array(
							'kode'=>$rs['wkode4'],
							'nama'=>$rs['wnama4'],
							'tingkat'=>$rs['wtingkat4'],
						);
						$dusun = array(
							'kode'=>$rs['wkode5'],
							'nama'=>$rs['wnama5'],
							'tingkat'=>$rs['wtingkat5'],
						);
						$rw = array(
							'kode'=>$rs['wkode6'],
							'nama'=>$rs['wnama6'],
							'tingkat'=>$rs['wtingkat6'],
						);
						$rt = array(
							'kode'=>$rs['wkode'],
							'nama'=>$rs['wnama'],
							'tingkat'=>$rs['wtingkat'],
						);
					}
				}
				break;
								
			default:
				
		}
		$hasil = array(
			'RT'=>$rt,
			'RW'=>$rw,
			'Dusun/Lingkungan'=>$dusun,
			'Desa/Kelurahan'=>$desa,
			'Kecamatan'=>$kecamatan,
			'Kabupaten/Kota'=>$kabupaten,
			'Propinsi'=>$propinsi,
			'Negara'=>array(
				'kode'=>'',
				'nama'=>'Republik Indonesia',
				'tingkat'=>0
			)
		);
		// echo $strSQL;
		return $hasil;
	}

	function pengguna_list($varKode='',$id_exclude=0){
		$strSQL = "
		SELECT u.*,w.nama as wilayah_nama  
		FROM tweb_users u
			LEFT JOIN tweb_wilayah w ON u.wilayah = w.kode 
		WHERE (u.id>1) AND (u.id <> ".$id_exclude.") AND (u.wilayah LIKE '".$varKode."%')";
		$query = $this->db->query($strSQL);
		$hasil  = $query->result_array();		
		return $hasil;
	}

	function pengguna_by_id($varID=0){
		$hasil = false;
		if($varID > 0){
			$strSQL = "
			SELECT u.*,w.nama as wilayah_nama  
			FROM tweb_users u
				LEFT JOIN tweb_wilayah w ON u.wilayah = w.kode 
			WHERE (u.id = ".$varID.")";
			// $result = $this->db->query($strSQL);
			// if($result){
			// 	if($result->num_rows() > 0){
					
			// 	}
			// }

			$query = $this->db->query($strSQL);

			$hasil  = $query->result_array()[0];		
		}
		return $hasil;
	}	

	function pengguna_simpan(){
		$user = $this->session->userdata;
		$varID = @$_POST['id'];
		$hasil = false;
		$str_foto = "";
		if(@$_FILES){
			$str_foto = _siteman_UploadFoto();
		}
		if($varID  > 0){
			$strSQL = "UPDATE tweb_users SET 
				nama = '".fixSQL($_POST['nama'])."',
				userid = '".fixSQL($_POST['userid'])."',";
				if($_POST['pass1']){
					$strSQL .= "passwt = '".password_hash($_POST['pass1'],PASSWORD_DEFAULT)."',";
				}
				
				$strSQL .= "tingkat = '".fixSQL($_POST['tingkat'])."',
				status = 1,
				email = '".fixSQL($_POST['email'])."',
				url = '".fixSQL($_POST['url'])."',
				nohp = '".fixSQL($_POST['nohp'])."',
				alamat = '".fixSQL($_POST['alamat'])."',
				wilayah = '".fixSQL($_POST['kode_wilayah'])."',
				ndesc = '".fixSQL($_POST['ndesc'])."',
				updated_by='".$user['id']."',
				";
				
			if($str_foto != "error")	{
				$strSQL .= "foto = '".fixSQL($str_foto)."',";
			}
			$strSQL .= "situs_id= '".$user['situs_id']."' WHERE id=".$varID;

			$strMsg = "Berhasil Menyimpan Data <strong>".$_POST['nama']."</strong>";

		}else{
			$strSQL = "SELECT id FROM tweb_users WHERE email='".fixSQL($_POST['email'])."' OR userid='".fixSQL($_POST['userid'])."' ";
			$result = $this->db->query($strSQL);
			if($result){
				if($result->num_rows() > 0){
					$strMsg = "Maaf, data pengguna tidak tersimpan, Email atau UserID telah digunakan utk orang lain";
				}else{
					$strSQL = "INSERT INTO tweb_users(nama,situs_id, userid, passwt, 
					tingkat,`status`, email, url,nohp, alamat, ndesc, foto,wilayah,created_by) 
					VALUES('".fixSQL($_POST['nama'])."','".fixSQL($user['situs_id'])."','".fixSQL($_POST['userid'])."','".password_hash($_POST['pass1'],PASSWORD_DEFAULT)."',
					'".fixSQL($_POST['tingkat'])."',1,'".fixSQL($_POST['email'])."','".fixSQL($_POST['url'])."','".fixSQL($_POST['nohp'])."','".fixSQL($_POST['alamat'])."',
					'".fixSQL($_POST['ndesc'])."','".fixSQL($str_foto)."','".fixSQL($_POST['kode_wilayah'])."','".$user['id']."')";
					$strMsg = "Berhasil Memperbaharui Data <strong>".$_POST['nama']."</strong>";
				}
			}
		}
		if($this->db->query($strSQL)){
			$varID = ($varID > 0) ? $varID: $this->db->insert_id();
			$hasil = array('id'=>$varID, 'msg'=>$strMsg);
		}else{
			$hasil = array('id'=>$varID, 'msg'=>"Error Query: ".$strSQL);
		}
		return $hasil;		
	}
		
}
