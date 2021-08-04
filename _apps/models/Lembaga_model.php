<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembaga_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	function lembaga_load($varID = 0){
		$hasil =false;
		if($varID > 0){
			/*
			 * Muat data seroang semua pengguna kecuali admin
			 * 
			 * */
			$strSQL = "
			SELECT u.* 
			FROM tweb_lembaga u
			WHERE (u.id='".$varID."')
			";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$hasil  = $query->result_array()[0];
			}else{
				$hasil = $strSQL;
			}

		}else{
			/*
			 * List semua pengguna kecuali admin
			 * 
			 * */
			$strSQL = "
			SELECT u.*, (SELECT COUNT(id) as n FROM tweb_users WHERE lembaga_id=u.id) as nuser  
			FROM tweb_lembaga u
			WHERE 1
			";
			$hasil =array();
			$query = $this->db->query($strSQL);
			foreach ($query->result() as $rs){
				$hasil[$rs->id] = array(
												"id"=>$rs->id,
												"nama"=>$rs->nama,
												"parma"=>$rs->parma,
												"ndesc"=>$rs->ndesc,
												"foto"=>$rs->foto,
												"alamat"=>$rs->alamat,
												"kodewilayah"=>$rs->kodewilayah,
												"kode_org"=>$rs->kode_org,
												"kodepos"=>$rs->kodepos,
												"url"=>$rs->url,
												"email"=>$rs->email,
												"nuser"=>$rs->nuser,
												);
			}
		}
		return $hasil;
	}
	
	function simpan_lembaga(){
		$varID = $_POST['id'];
		$hasil = false;
		$str_foto = "";
		if($_FILES){
			$str_foto = _siteman_UploadFoto();
		}
		if($varID  > 0){
			$strSQL = "UPDATE tweb_lembaga SET 
				nama = '".fixSQL($_POST['nama'])."',
				email = '".fixSQL($_POST['email'])."',
				url = '".fixSQL($_POST['url'])."',
				telp = '".fixSQL($_POST['telp'])."',
				fax = '".fixSQL($_POST['fax'])."',
				alamat = '".fixSQL($_POST['alamat'])."',
				";
			if(strlen($str_foto) > 0)	{
				$strSQL .= "foto = '".fixSQL($str_foto)."',";
			}
			$strSQL .= " ndesc = '".fixSQL($_POST['ndesc'])."' WHERE id=".$varID;

			$strMsg = "Berhasil Memutakhirkan Data <strong>".$_POST['nama']."</strong>";

		}else{
			$strSQL = "INSERT INTO tweb_lembaga(nama, email, url,telp, fax, alamat, ndesc, foto) 
			VALUES('".fixSQL($_POST['nama'])."','".fixSQL($_POST['email'])."','".fixSQL($_POST['url'])."',
			'".fixSQL($_POST['telp'])."','".fixSQL($_POST['fax'])."','".fixSQL($_POST['alamat'])."',
			'".fixSQL($_POST['ndesc'])."','".fixSQL($str_foto)."')";
			$strMsg = "Berhasil Menyimpan Data Lembaga <strong>".$_POST['nama']."</strong>";
		}
		if($this->db->query($strSQL)){
			$varID = ($varID > 0) ? $varID: $this->db->insert_id();
			$hasil = array('id'=>$varID, 'msg'=>$strMsg);
		}else{
			$hasil = array('id'=>$varID, 'msg'=>"Error Query");
		}
		return $hasil;
	}
	
}
