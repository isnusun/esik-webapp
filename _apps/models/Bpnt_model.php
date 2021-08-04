<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bpnt_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function periodes($varID=''){
		$data = false;
		if($varID ==''){
			$strSQL = "SELECT id,nama,label FROM bpnt_periodes WHERE 1 ORDER BY nama DESC";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$data = array();
					foreach ($query->result_array() as $rs){
						$data[$rs['nama']]= $rs;
					}
				}
			}
		}else{
			$strSQL = "SELECT id,nama,label FROM bpnt_periodes WHERE nama='".fixSQL($varID)."'";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$data = array();
					foreach ($query->result_array() as $rs){
						$data[$rs['nama']]= $rs;
					}
				}
			}
		}
		return $data;
	}

	function periode_simpan(){
		$hasil = false;
		$strSQL = "INSERT INTO bpnt_periodes(`nama`,`label`) VALUES('".fixSQL($_POST['nama'])."','".fixSQL($_POST['label'])."')";
		$query = $this->db->query($strSQL);
		if($query){
			$hasil = "Berhasil Menyimpan data Periode BPNT ".$_POST['label'];
		}
		return $hasil;
	}

	function load_data($varKode){
		$hasil = false;
		$lvkode = strlen($varKode);
		
		switch ($lvkode) {
			case 2:
				# code...
				$strSQL = "SELECT DISTINCT(p.KDKAB),p.kode_desa,COUNT(p.ID_Pengurus) as kpm,
					w.nama as nama, w.kode as kode
				FROM bpnt_pengurus p 
					LEFT JOIN tweb_wilayah w ON SUBSTR(p.kode_desa,1,4) = w.kode
				WHERE p.kode_desa LIKE '".$varKode."%'
				GROUP BY p.KDKAB ORDER BY w.nama";
				break;
			case 4:
				# code...
				$strSQL = "SELECT DISTINCT(p.KDKEC),p.kode_desa,COUNT(p.ID_Pengurus) as kpm,
					w.nama as nama, w.kode as kode
				FROM bpnt_pengurus p 
					LEFT JOIN tweb_wilayah w ON SUBSTR(p.kode_desa,1,7) = w.kode
				WHERE p.kode_desa LIKE '".$varKode."%'					
				GROUP BY p.KDKEC ORDER BY w.nama";
				break;
			case 7:
				# code...
				$strSQL = "SELECT DISTINCT(p.KDDESA),p.kode_desa,COUNT(p.ID_Pengurus) as kpm,
					w.nama as nama, w.kode as kode
				FROM bpnt_pengurus p 
					LEFT JOIN tweb_wilayah w ON SUBSTR(p.kode_desa,1,10) = w.kode
				WHERE p.kode_desa LIKE '".$varKode."%'					
				GROUP BY p.KDDESA ORDER BY w.nama";
				break;
			case 10:
				# code...
				break;
			
			default:
				# code...
				# code...
				$strSQL = "SELECT DISTINCT(p.KDPROP),p.kode_desa,
					w.nama as nama, w.kode as kode
				FROM bpnt_pengurus p 
					LEFT JOIN tweb_wilayah w ON p.kode_desa = w.kode
				GROUP BY p.KDPROP";
				break;
				break;
		}

		// $strSQL = "SELECT w.kode,w.nama,
		// FROM tweb_wilayah w 
		// WHERE kode LIKE '".$varKode."%' AND tingkat=((SELECT tingkat FROM tweb_wilayah WHERE kode='".$varKode."')+1)";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			$hasil = $query->result_array();
		}
		return $hasil;
	}

	function load_data_penerimaan($varKode){
		$periodes = $this->periodes();
		$hasil = array();
		foreach($periodes as $key=>$p){
			$hasil[$key] = $this->data_penerimaan($varKode,$key);
		}
		return $hasil;
	}

	function data_penerimaan($varKode,$varPeriode){
		$strSQL = "SELECT w.kode,
			(SELECT COUNT(id) FROM bpnt_penerimaan WHERE ((periode_bpnt='".$varPeriode."') AND (kode_desa LIKE CONCAT(w.kode,'%')) AND (kelas <> ''))) as ada, 
			(SELECT COUNT(id) FROM bpnt_penerimaan WHERE ((periode_bpnt='".$varPeriode."') AND (kode_desa LIKE CONCAT(w.kode,'%')) AND (kelas = ''))) as tidakada 
		FROM tweb_wilayah w 
		WHERE w.kode LIKE '".$varKode."%' AND tingkat=((SELECT tingkat FROM tweb_wilayah WHERE kode='".$varKode."')+1) ";
		$query = $this->db->query($strSQL);
		if($query){
			$hasil = array();
			foreach ($query->result_array() as $key => $value) {
				$hasil[$value['kode']] = $value;
			}
		}
		return $hasil;

	}

	function data_by_rts($varRts=''){
		$hasil = false;
		if($varRts != ''){
			$strSQL = "SELECT d.id as penerimaan_id,d.kelas,d.keterangan,d.IDARTBDT,
				e.Nama_Pengurus,e.BANK,e.NOKARTU,e.NOREKENING,e.Alamat_Pengurus,
				p.label as periodeLabel
			FROM bpnt_penerimaan d 
				LEFT JOIN bpnt_pengurus e ON d.pengurus_id=e.ID_Pengurus
				LEFT JOIN bpnt_periodes p ON d.periode_bpnt=p.nama 
			WHERE d.IDBDT='".fixSQL($varRts)."'";
			// echo $strSQL;
			$query = $this->db->query($strSQL);
			if($query){
				$hasil = array();
				foreach ($query->result_array() as $key => $value) {
					$hasil[] = $value;
				}
			}
		}
		return $hasil;
	}

	function load_pengurus($varKode,$varStat='',$varPeriode=''){

		switch ($varStat) {
			case 'ada':
				$stat = " AND (d.kelas <> '')";
				break;
			case 'tidakada':
				$stat = " AND (d.kelas='')";
				break;
			case 'all':
				$stat = "";
				break;
			default:
				# code...
				$stat = " AND (d.kelas='".$varStat."')";
				break;
		}
		// $varPeriode = ($varPeriode =='')? "": " AND () ";
		if($varPeriode != ''){
			$strSQL = "SELECT DISTINCT(d.id) as penerimaan_id,p.* 
			FROM bpnt_penerimaan d 
				LEFT JOIN bpnt_pengurus p ON d.pengurus_id=p.ID_Pengurus 
			WHERE ((d.periode_bpnt='".fixSQL($varPeriode)."') AND (d.kode_desa LIKE '".$varKode."%') ".$stat.") GROUP BY d.id";
			// echo $strSQL;
			$query = $this->db->query($strSQL);
			if($query){
				$hasil = $query->result_array();
			}

		}else{
			$strSQL = "SELECT `id`,`kode_desa`,`ID_Pengurus`,`KDPROP`, `NMPROP`, `KDKAB`, `NMKAB`, `KDKEC`, `NMKEC`, `KDDESA`, `NMDESA`, 
			`Nama_Pengurus`, `NIK_Pengurus`, `Alamat_Pengurus`, 
			`BANK`, `NOKARTU`, `NOREKENING`, 
			`NoArtPBDTKemsos`, `NoPBDTKemsos`, `NoKK`, `TglLahir_Pengurus`, `TmpLhr_Pengurus`, `keterangan` 
			FROM `bpnt_pengurus` WHERE (`kode_desa` LIKE '".$varKode."%') ";
			$query = $this->db->query($strSQL);
			if($query){
				$hasil = $query->result_array();
			}
		}
		return $hasil;

	}
}