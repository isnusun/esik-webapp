<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verval_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	function responden_by_wilayah($varKode){
		$hasil = false;
		$strSQL = "SELECT idbdt,nopesertapbdt,ruta6,nama_krt,alamat,jumlah_keluarga,jumlah_art FROM bdt_rts WHERE kode_wilayah LIKE '".fixSQL($varKode)."%' AND periode_id=1";
		$query = $this->db->query($strSQL);
		if($query){
			$data = array();
			foreach ($query->result_array() as $rs){
				$data[$rs['idbdt']] = $rs;
			}
			$hasil = $data;
		}
		return $hasil;
	}

	function responden_statistik_by_wilayah($varKode,$periode_id=1){
		// $this->load->model('wilayah_model');
		$tingkat = _tingkat_by_len_kode($varKode);
		$list_tingkat = $tingkat+1;
		$strSQL = "SELECT w.kode as kode,w.nama as nama,
			(SELECT COUNT(id) FROM tweb_rumahtangga WHERE kode_wilayah LIKE CONCAT(w.kode,'%') ) as sum_rts
		FROM tweb_wilayah w 
		WHERE w.tingkat='".$list_tingkat."' AND kode LIKE '".$varKode."%'";
		$query = $this->db->query($strSQL);
		if($query){
			$data = array();
			foreach ($query->result_array() as $rs){
				$data[$rs['kode']] = $rs;
			}
			$hasil = $data;
		}
		return $hasil;
	}

	function rts_by_wilayah($varKode=0)
	{
		$hasil = false;
		$strSQL = "SELECT r.id,r.rtm_no,r.kode_wilayah,
			p.nama as pnama, p.nik as pnik,
			(SELECT COUNT(id) FROM tweb_keluarga WHERE rtm_no=r.rtm_no AND capil_stat=1) as nkks,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE rtm_no=r.rtm_no AND capil_stat=1) as narts
		FROM tweb_rumahtangga r 
			LEFT JOIN tweb_penduduk p ON r.rtm_no=p.rtm_no
		WHERE ((r.kode_wilayah LIKE '".$varKode."%') AND (p.rt_hubungan=1)) 
		ORDER BY p.nama ASC";
		$query = $this->db->query($strSQL);
		if($query){
			$data = array();
			foreach ($query->result_array() as $rs){
				$data[$rs['rtm_no']] = $rs;
			}
			$hasil = $data;
		}
		return $hasil;
	}

	function data_rts($varNo = 0,$varPeriode=1){
		/*
		 * $varNo = rtm_no
		 * 
		 * */
		$hasil =false;
		if($varNo > 0){
			/*
			 * Anggota RTM 
			 * 
			 * */
			$anggota = array();
			$strSQLU = "
			SELECT p.id,p.alamat,
				p.nama as pnama, p.nik as pnik,p.kk_nomor as nokk,p.sex as sex,p.dtlahir, FLOOR(DATEDIFF (NOW(), p.dtlahir)/365) as umur, 
				p.kk_hubungan as kk_hubungan,p.rt_hubungan as rt_hubungan,
				p.kawin_status as stat_kawin,p.capil_stat,p.capil_desc,p.capil_at,
				p.serumah,p.serumah_alamat,p.serumah_sekolah,p.serumah_nisn,
				SUBSTRING(p.kode_wilayah,15,2) as p_rt,SUBSTRING(p.kode_wilayah,13,2) as p_rw,
				wkec.nama as p_kecamatan, wkel.nama as p_kelurahan,
				s.nama as psex,k.nama as kawin,h.nama as hubungan,hk.nama as hubungankk,
				kk.id as kk_id,kk.rts_utama as rts_kk_utama,SUBSTRING(kk.kode_wilayah,15,2) as kk_rt,SUBSTRING(kk.kode_wilayah,13,2) as kk_rw,
				(SELECT COUNT(id) FROM gk_data WHERE responden_tipe=3 AND responden_id=p.id AND periode_id='".$varPeriode."') as idv_data,
				(SELECT COUNT(id) FROM gk_data WHERE responden_tipe<3 AND responden_id=p.kk_nomor AND periode_id='".$varPeriode."') as kk_data,
				kel.nama as kk_kelurahan,kec.nama as kk_kecamatan
			FROM tweb_penduduk p 
				LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				LEFT JOIN tweb_penduduk_sex s ON  p.sex=s.id
				LEFT JOIN tweb_penduduk_hubungan_rtm h ON  p.rt_hubungan=h.id
				LEFT JOIN tweb_penduduk_hubungan hk ON  p.kk_hubungan=hk.id
				LEFT JOIN tweb_keluarga kk ON p.kk_nomor=kk.kk_no 
				LEFT JOIN tweb_wilayah kel ON SUBSTRING(kk.kode_wilayah,1,10)=kel.kode 
				LEFT JOIN tweb_wilayah kec ON SUBSTRING(kk.kode_wilayah,1,7)=kec.kode 
				LEFT JOIN tweb_wilayah wkel ON SUBSTRING(p.kode_wilayah,1,10)=wkel.kode 
				LEFT JOIN tweb_wilayah wkec ON SUBSTRING(p.kode_wilayah,1,7)=wkec.kode 
			WHERE (p.rtm_no='".fixSQL($varNo)."') ORDER BY p.capil_stat DESC,rts_kk_utama DESC,  p.kk_nomor, p.kk_hubungan ASC";
			
			$query = $this->db->query($strSQLU);
			if($query){
				if($query->num_rows() > 0){
					foreach($query->result_array() as $rs){
						$anggota[$rs['id']] = $rs;
					}
				}
			}
			

			/*
			 * Detail RTM
			 * */
			$strSQL = "
			SELECT r.*,p.alamat as palamat,
				p.nama as pnama,p.nik as pnik,p.dtlahir,FLOOR(DATEDIFF (NOW(), p.dtlahir)/365) as kepala_umur,
				s.nama as psex,
				wkec.nama as kecamatan, wkel.nama as kelurahan,
				(SELECT COUNT(id) FROM gk_data WHERE responden_id=p.kk_nomor AND periode_id='".$varPeriode."') as rts_data    
			FROM tweb_rumahtangga r 
				LEFT JOIN tweb_penduduk p ON r.rtm_no=p.rtm_no 
				LEFT JOIN tweb_penduduk_sex s ON  p.sex=s.id 
				LEFT JOIN tweb_wilayah wkec ON SUBSTRING(r.kode_wilayah,1,7)=wkec.kode
				LEFT JOIN tweb_wilayah wkel ON SUBSTRING(r.kode_wilayah,1,10)=wkel.kode
			WHERE r.rtm_no='".fixSQL($varNo)."' 
			AND p.rt_hubungan=1  AND p.capil_stat=1 LIMIT 1";


			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$rs = $query->result_array()[0];
					$hasil = array(
						"rtm_id"=>$rs['id'],
						"rtm_no"=>$varNo,
						"kepala"=>$rs['pnama'],
						"kepala_sex"=>$rs['psex'],
						"kepala_dtlahir"=>$rs['dtlahir'],
						"kepala_umur"=>$rs['kepala_umur'],
						"alamat"=>$rs['alamat'],
						"varlat"=>$rs['varlat'],
						"varlon"=>$rs['varlon'],
						"zoom"=>$rs['zoom'],
						"kelurahan"=>$rs['kelurahan'],
						"kecamatan"=>$rs['kecamatan'],
						"wilayah"=>$this->wilayah_model->get_alamat($rs['kode_wilayah']),
						"kode_wilayah"=>$rs['kode_wilayah'],
						"kelas_pbdt"=>$rs['kelas_pbdt'],
						"kelas_pbk"=>$rs['kelas_pbk'],
						"rts_data"=>$rs['rts_data'],
						"cacah_status"=>$rs['cacah_status'],
						"cacah_oleh"=>$rs['cacah_oleh'],
						"cacah_pada"=>$rs['cacah_pada'],
						"anggota"=>$anggota,
						"sqlu"=>$strSQL,
						"baru"=>false
						);
				}
			}
		}
		return $hasil;  
	}	

	function kks_by_rts($varRTS=0){
		$data = false;
		$strSQL = "SELECT k.*,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE kk_nomor=k.kk_no AND rtm_no='".$varRTS."' AND capil_stat=1) as narts
		FROM tweb_keluarga k
		WHERE k.rtm_no='".$varRTS."' ORDER BY rts_utama DESC";
		$query = $this->db->query($strSQL);
		if($query){
			$data = array();
			foreach ($query->result_array() as $rs){
				$data[$rs['kk_no']] = $rs;
			}
		}
		return $data;
	}
	


	
}