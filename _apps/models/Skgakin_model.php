<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skgakin_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	function sumberdata_skgakin(){
		$data = array(
			"1"=>"PBDT 2015",
			"2"=>"Data Sisiran",
			"3"=>"Pengajuan Masyarakat",
		);
		return $data;
	}
	
	function query_individu(){
		$list_query_individu = array();
		
		$list_query_individu['krtperempuan'] = array("nama"=>"Kepala Rumah Tangga Perempuan","data"=>"dimana");
		$list_query_individu['usia'] = array("nama"=>"Klasifikasi Usia","data"=>"list_individu_by_usia","data"=>"dimana");
		$list_query_individu['anaksekolah'] = array("nama"=>"Pendidikan (Jumlah Anak Bersekolah)","data"=>"dimana");
		$list_query_individu['anaktaksekolah'] = array("nama"=>"Pendidikan (Jumlah Anak TIDAK Bersekolah)","data"=>"dimana");
		$list_query_individu['difabilitas'] = array("nama"=>"Kecacatan","data"=>"dimana");
		$list_query_individu['sakitkronis'] = array("nama"=>"Penyakit Kronis","data"=>"dimana");
		$list_query_individu['statuskerja'] = array("nama"=>"Individu Tidak Bekerja dlm Kelompok Usia","data"=>"dimana");
		$list_query_individu['lapangankerja'] = array("nama"=>"Lapangan/Bidang Kerja Individu","data"=>"disini");
		return $list_query_individu;
	}
	
	function query_individu_data($varQ='',$varKode,$varPeriode=1){
		
		$periode = $this->skgakin_periode();
		$periode_ini = $periode['list'][$varPeriode];
		//echo $periode_ini['sdate'];
		$this->load->model('wilayah_model');
		$varKode = (strlen($varKode)==10)? $varKode."00":$varKode;
		$wilayah = $this->wilayah_model->wilayah($varKode);
		$tingkat=$wilayah['tingkat']+1;
		$data = false;

		switch ($varQ)
		{
			case "krtperempuan":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"KRT Perempuan",
					)
				);
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (p.sex=2) AND (p.rt_hubungan=1))) as kol0 
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
			
				break;
			case "usia":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"di bawah 6 thn",
						'kol1'=>"6 - 14 thn",
						'kol2'=>"15 - 44 thn",
						'kol3'=>"45 - 59 thn",
						'kol4'=>"60 thn ke atas")
				);
					
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < 6))) as kol0, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 6) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < 15))) as kol1, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 16) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < 45))) as kol2, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 45) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < 60))) as kol3, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 60))) as kol4 
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
				
				break;
			case "anaksekolah":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"Usia 7-12 thn",
						'kol1'=>"Usia 13-15 thn",
						'kol2'=>"Usia 16-18 thn",
					)
				);
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 7) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 12)) AND (p.sekolah=1)) as kol0, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 13) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 15)) AND (p.sekolah=1)) as kol1, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 16) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 18)) AND (p.sekolah=1)) as kol2  
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
				break;

			case "anaktaksekolah":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"Usia 7-12 thn",
						'kol1'=>"Usia 13-15 thn",
						'kol2'=>"Usia 16-18 thn",
					)
				);
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 7) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 12)) AND (p.sekolah<>1)) as kol0, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 13) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 15)) AND (p.sekolah<>1)) as kol1, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 16) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 18)) AND (p.sekolah<>1)) as kol2  
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
				break;

			case "difabilitas":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"Usia < 15 thn",
						'kol1'=>"Usia 15 - 44 thn",
						'kol2'=>"Usia 44 - 59 thn",
						'kol3'=>"Usia diatas 60 thn",
					)
				);
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 15)) AND (p.cacat_jenis > 0)) as kol0, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 16) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 44)) AND (p.cacat_jenis > 0)) as kol1, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 45) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 59)) AND (p.cacat_jenis > 0)) as kol2,  
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 60)) AND (p.cacat_jenis > 0)) as kol3  
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
				break;

			case "sakitkronis":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"Usia < 15 thn",
						'kol1'=>"Usia 15 - 44 thn",
						'kol2'=>"Usia 44 - 59 thn",
						'kol3'=>"Usia diatas 60 thn",
					)
				);
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 15)) AND (p.sakit_kronis > 0)) as kol0, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 16) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 44)) AND (p.sakit_kronis > 0)) as kol1, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 45) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 59)) AND (p.sakit_kronis > 0)) as kol2,  
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 60)) AND (p.sakit_kronis > 0)) as kol3  
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
				break;
				

			case "statuskerja":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"Usia < 15 thn",
						'kol1'=>"Usia 15 - 59 thn",
						'kol2'=>"Usia diatas 60 thn",
					)
				);
				$strSQL = "
					SELECT w.nama,w.tingkat,w.kode,
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 15)) AND (p.kerja=2)) as kol0, 
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 16) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) <= 59)) AND (p.kerja=2)) as kol1,  
						(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= 60)) AND (p.kerja=2)) as kol2  
					FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
				";
				break;
			
			case "lapangankerja":
				$strSQL = "";
				break;
				
			default:
				
		}
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$data = $query->result_array();
			}
		}
			
		$hasil = array('cols'=>$cols,'data'=>$data,'sql'=>$strSQL);
		return $hasil;
		
		
	}
	
	function query_individu_data_lapangankerja($varKode,$varPeriode){
		$this->load->model('wilayah_model');
		$varKode = (strlen($varKode)==10)? $varKode."00":$varKode;
		$wilayah = $this->wilayah_model->wilayah($varKode);
		$tingkat=$wilayah['tingkat']+1;
		$data = false;

		$strSQL = "SELECT * FROM `tweb_penduduk_bekerja_bidang`";
		$kolom = array();
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$kolom[$rs['id']] = $rs['nama'];
				}
			}
		}
		
		$cols = array(
			'nama'=>"Wilayah",
			'kode'=>"Kode Wilayah",
			'kolom'=>$kolom
		);		
		
		$strSQL = "
			SELECT w.nama,w.tingkat,w.kode,";
			$i=1;
			foreach($kolom as $key=>$item){
				$strKoma = ($i < count($kolom)) ? ", ":"";
				$i++;
				$strSQL .="(SELECT COUNT(s.id) FROM skgakin s LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  WHERE ((s.periode_id='".$varPeriode."') AND (s.kode_wilayah LIKE CONCAT(w.kode,'%')) AND (p.kerja_bidang=".$key.")) ) as kol".$key.$strKoma;
			}
		$strSQL .= "		
			FROM tweb_wilayah w WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$tingkat."
		";

		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$data = $query->result_array();
			}
		}		
		$hasil = array('cols'=>$cols,'data'=>$data,'sql'=>$strSQL);
		return $hasil;
	}

	function query_individu_data_siapa_lapangankerja($varKode,$varKol='',$varPeriode){
		$this->load->model('wilayah_model');
		$varKode = (strlen($varKode)==10)? $varKode."00":$varKode;
		$wilayah = $this->wilayah_model->wilayah($varKode);
		$tingkat=$wilayah['tingkat']+1;
		
		$strSQL = "SELECT * FROM `tweb_penduduk_bekerja_bidang`";
		$kolom = array();
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$kolom[$rs['id']] = $rs['nama'];
				}
			}
		}
		
		$data = false;
		$cols = array(
			'nama'=>"Wilayah",
			'kode'=>"Kode Wilayah",
			'kolom'=>array(
				'kol0'=>$kolom[$varKol],
			)
		);
		$strSQL = "
		SELECT p.id,p.nama as pnama,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, p.rtm_no,
			FLOOR(DATEDIFF (NOW(), p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin
		FROM skgakin s 
			LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
			LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
		WHERE (
			(s.periode_id='".$varPeriode."') AND 
			(s.kode_wilayah LIKE '".$varKode."%') AND 
			(p.kerja_bidang ='".$varKol."') 
			)
		";
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$data = $query->result_array();
			}
		}
			
		$hasil = array('cols'=>$cols,'data'=>$data,'sql'=>$strSQL);
		return $hasil;
		
		
	}
	function query_individu_data_siapa($varQ='',$varKode,$varKol='',$varPeriode){

		$periode = $this->skgakin_periode();
		$periode_ini = $periode['list'][$varPeriode];

		$this->load->model('wilayah_model');
		$varKode = (strlen($varKode)==10)? $varKode."00":$varKode;
		$wilayah = $this->wilayah_model->wilayah($varKode);
		$tingkat=$wilayah['tingkat']+1;
		$data = false;

		switch ($varQ)
		{
			case "krtperempuan":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>"KRT Perempuan",
					)
				);
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					(p.sex=2) AND 
					(p.rt_hubungan=1) 
					)
				";
			
				break;
			case "usia":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>array(0,6,"di bawah 6 thn"),
						'kol1'=>array(7,14,"6 - 14 thn"),
						'kol2'=>array(15,44,"15 - 44 thn"),
						'kol3'=>array(45,59,"45 - 59 thn"),
						'kol4'=>array(60,200,"60 thn ke atas")
					)
				);
				$limitUsia = $cols['kolom'][$varKol];
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.rtm_no,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					((FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= ".$limitUsia[0].") AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < ".$limitUsia[1].")) 
					)
				";
				
				break;
			case "anaksekolah":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>array(7,12,"Usia 7-12 thn"),
						'kol1'=>array(13,15,"Usia 13-15 thn"),
						'kol2'=>array(16,18,"Usia 16-18 thn"),
					)
				);
				$limitUsia = $cols['kolom'][$varKol];
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.rtm_no,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					(p.sekolah=1) AND 
					((FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= ".$limitUsia[0].") AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < ".$limitUsia[1].")) 
					)
				";

				break;

			case "anaktaksekolah":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>array(7,12,"Usia 7-12 thn"),
						'kol1'=>array(13,15,"Usia 13-15 thn"),
						'kol2'=>array(16,18,"Usia 16-18 thn"),
					)
				);
				$limitUsia = $cols['kolom'][$varKol];
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.rtm_no,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					(p.sekolah<>1) AND 
					((FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= ".$limitUsia[0].") AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < ".$limitUsia[1].")) 
					)
				";

				break;

			case "difabilitas":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>array(0,15,"Usia < 15 thn"),
						'kol1'=>array(15,44,"Usia 15 - 44 thn"),
						'kol2'=>array(45,59,"Usia 44 - 59 thn"),
						'kol3'=>array(60,200,"Usia diatas 60 thn"),
					),
					'pspesifik'=>'Kecacatan',
				);

				$limitUsia = $cols['kolom'][$varKol];
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.rtm_no,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin,
					kr.nama as pspesifik 
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
					LEFT JOIN tweb_cacat kr ON p.cacat_jenis=kr.id
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					(p.cacat_jenis > 0) AND 
					((FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= ".$limitUsia[0].") AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < ".$limitUsia[1].")) 
					)
				";

				break;

			case "sakitkronis":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>array(0,15,"Usia < 15 thn"),
						'kol1'=>array(16,44,"Usia 15 - 45 thn"),
						'kol2'=>array(45,59,"Usia 44 - 60 thn"),
						'kol3'=>array(60,200,"Usia diatas 60 thn"),
					),
					'pspesifik'=>'Sakit Kronis',
				);

				$limitUsia = $cols['kolom'][$varKol];
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.rtm_no,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin,
					kr.nama as pspesifik 
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
					LEFT JOIN tweb_sakit_menahun kr ON p.sakit_kronis=kr.id 
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					(p.sakit_kronis > 0) AND 
					((FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= ".$limitUsia[0].") AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < ".$limitUsia[1].")) 
					)
				";


				break;
				

			case "statuskerja":
				$cols = array(
					'nama'=>"Wilayah",
					'kode'=>"Kode Wilayah",
					'kolom'=>array(
						'kol0'=>array(0,15,"Usia < 15 thn"),
						'kol1'=>array(16,44,"Usia 15 - 45 thn"),
						'kol2'=>array(45,59,"Usia 44 - 60 thn"),
						'kol3'=>array(60,200,"Usia diatas 60 thn"),
					)
				);

				$limitUsia = $cols['kolom'][$varKol];
				$strSQL = "
				SELECT p.id,p.nama as pnama,p.rtm_no,p.dtlahir,p.nik as pnik,p.kk_nomor as pnokk,p.sex,p.alamat,p.dtlahir, 
					FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,k.nama as kawin
				FROM skgakin s 
					LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id  
					LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				WHERE (
					(s.periode_id='".$varPeriode."') AND 
					(s.kode_wilayah LIKE '".$varKode."%') AND 
					(p.kerja=2) AND 
					((FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) >= ".$limitUsia[0].") AND (FLOOR(DATEDIFF ('".$periode_ini['sdate']."', p.dtlahir)/365) < ".$limitUsia[1].")) 
					)
				";

				break;
			default:
				
		}
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$data = $query->result_array();
			}
		}
			
		$hasil = array('cols'=>$cols,'data'=>$data,'sql'=>$strSQL);
		return $hasil;
		
		
	}	

	function list_individu_by_usia($varKode){
		/*
		 * usia < 6 thn, 6- 14, 15-44, 45 -59, 60th ke atas)
		 * 
		 * */
		
		$cols = array('nama','kode','produktif0','produktif1','produktif2','produktif3','produktif4');
		$strSQL = "
			SELECT w.nama,w.kode,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((kode_wilayah LIKE CONCAT(c.kode,'%')) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) < 6))) as produktif0, 
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((kode_wilayah LIKE CONCAT(c.kode,'%')) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) >= 6) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) <=14))) as produktif1,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((kode_wilayah LIKE CONCAT(c.kode,'%')) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) >= 15) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) <=44))) as produktif2,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((kode_wilayah LIKE CONCAT(c.kode,'%')) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) >= 45) AND (FLOOR(DATEDIFF (NOW(), tanggallahir)/365) <=59))) as produktif3,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE ((kode_wilayah LIKE CONCAT(c.kode,'%')) AND FLOOR(DATEDIFF (NOW(), tanggallahir)/365) > 60)) as produktif4 
			FROM tweb_wilayah w WHERE w.tingkat=".$tingkat."
		";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $strSQL;
	}	
	
	function skgakin_periode(){
		$hasil = array();
		$strSQL = "SELECT id,nama,sdate,edate,status FROM skgakin_periode ORDER BY sdate DESC";
		$query = $this->db->query($strSQL);
		$aktif = 0;
		foreach ($query->result() as $rs){
			$data[$rs->id] = array("nama"=>$rs->nama,"sdate"=>$rs->sdate,"edate"=>$rs->edate);
			if($rs->status == 1){
				$aktif = $rs->id;
			}
		}
		$strSQL = "SELECT id FROM skgakin_periode WHERE status=0 ORDER BY sdate DESC LIMIT 1";
		$query = $this->db->query($strSQL);
		if($query){
			$row = $query->row();
			$terakhir =$row->id;
		}
		
		$hasil = array(
			"list"=>$data,
			"aktif"=>$aktif,
			"terakhir"=>$terakhir,
			);
		return $hasil;
	}
	
	function skgakin_index($varKode,$varPage=1){
		$tingkatan = _tingkat_wilayah();
		$sumber_data = $this->sumberdata_skgakin();
		$offset = ($varPage - 1) * LIMIT_TAMPIL;
		$periode = $this->skgakin_periode();
		$hasil = false;
		$strSQL = "SELECT id,nama,tingkat,path FROM tweb_wilayah WHERE kode='".fixSQL($varKode)."' LIMIT 1";
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->row();
				$tingkat = $rs->tingkat;
				$stingkat = ($tingkat == 4) ? $tingkat+2 : $tingkat+1;
				$nama = $rs->nama;
				$peta = $rs->path;
				$result = ""; $rs= "";
				$subWilayah = array();
				$strSQL = "SELECT ";
				$i=1;
				foreach($periode['list'] as $key=>$item){
					if($i < 3){
						$strSQL .="
						(SELECT COUNT(id) FROM skgakin WHERE periode_id='".$key."' AND kode_wilayah LIKE CONCAT(w.kode,'%') ) as art".$i.",
						(SELECT COUNT(id) FROM skgakin WHERE periode_id='".$key."' AND shdk=1 AND  kode_wilayah LIKE CONCAT(w.kode,'%')) as rt".$i.",
						";
					}
					$i++;
				}
				
				$strSQL .= "
						w.kode,w.nama as wnama,w.tingkat,w.path 
					FROM tweb_wilayah w 
					WHERE ((w.kode LIKE '".fixSQL($varKode)."%') AND (w.tingkat='".$stingkat."')) 
					ORDER BY w.nama 
				";
				$query = $this->db->query($strSQL);
				foreach ($query->result() as $rs){
					$subWilayah[$rs->kode] = array(
						"nama"=>$rs->wnama,
						"tingkat"=>$rs->tingkat,
						"art1"=>$rs->art1,
						"rt1"=>$rs->rt1,
						"art2"=>$rs->art2,
						"rt2"=>$rs->rt2,
						);
				}

				$hasil = array(
					"nama"=>$nama, 
					"path"=>$peta, 
					"tingkat"=>$tingkat, 
					"tingkatan"=>$tingkatan[$tingkat], 
					"stingkat"=>$stingkat, 
					"stingkatan"=>$tingkatan[$stingkat], 
					"sub"=>$subWilayah,
					"sql"=>$strSQL
					);
			}
		}
		return $hasil;		
	}
	
	function skgakin($varPeriode,$varKode,$varSumber,$varPage=1){
		$sumber_data = $this->sumberdata_skgakin();
		$offset = ($varPage - 1) * LIMIT_TAMPIL;
		/*
		 * termasuk data wilayah
		 * */
		$strSQL = "
		SELECT s.id as id,s.nik,s.penduduk_id,s.sumber,s.kode_wilayah,
		p.nama as pnama,p.kk_nomor,p.rtm_no,p.alamat,RIGHT(p.kode_wilayah,2) as rt,SUBSTRING(p.kode_wilayah,13,2) as rw,p.sex,p.dtlahir,(YEAR(CURRENT_TIMESTAMP) - YEAR(p.dtlahir) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(p.dtlahir, 5))) as umur, 
		w.nama as kelurahan,k.nama as kecamatan   
		FROM skgakin s
		LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id 
		LEFT JOIN tweb_wilayah w ON SUBSTRING(s.kode_wilayah,1,10)=w.kode  
		LEFT JOIN tweb_wilayah k ON SUBSTRING(s.kode_wilayah,1,7)=k.kode ";
		 
		$strClausul = " WHERE s.periode_id='".$varPeriode."' AND s.kode_wilayah LIKE '".$varKode."%' ";
		if($varSumber > 0){
			$strClausul .=" AND s.sumber='".$varSumber."'";
		}
		$strLIMIT =" LIMIT ".$offset.",".LIMIT_TAMPIL;
		$nSemua = 0;
		$sqlSemua = "SELECT COUNT(s.id) as numrows FROM skgakin s ".$strClausul;
		
		$result = $this->db->query($sqlSemua);
		if($result){
			$rs = $result->result_array()[0];
			$nSemua = $rs['numrows'];
		}
		
		unset($result);
		
		$sqlData = $strSQL.$strClausul.$strLIMIT;
		$record = array();
		$query = $this->db->query($sqlData);
		foreach ($query->result() as $rs){
			$sex = ($rs->sex==1)? "L":"P";
			$record[$rs->id] = array(
				"src"=>$sumber_data[$rs->sumber],
				"nama"=>$rs->pnama,
				"nik"=>$rs->nik,
				"penduduk_id"=>$rs->penduduk_id,
				"nokk"=>$rs->kk_nomor,
				"no_rtm"=>$rs->rtm_no,
				"sex"=>$sex,
				"dtlahir"=>$rs->dtlahir,
				"umur"=>$rs->umur,
				"kode_wilayah"=>$rs->kode_wilayah,
				"alamat"=>$rs->alamat,
				"rt"=>$rs->rt,
				"rw"=>$rs->rw,
				"kelurahan"=>$rs->kelurahan,
				"kecamatan"=>$rs->kecamatan,
				);
		}
		
		unset($query);
		$hasil = array(
			"n"=>$nSemua,
			"data"=>$record,
		);
		return $hasil;
		unset($hasil);
	}
	
	function skgakin_by_wilayah($varKode,$varPeriode){
		$tingkatan = _tingkat_wilayah();
		$hasil = false;
		$strSQL = "SELECT id,nama,tingkat,path FROM tweb_wilayah WHERE kode='".fixSQL($varKode)."' LIMIT 1";
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->row();
				$tingkat = $rs->tingkat;
				$stingkat = ($tingkat == 4) ? $tingkat+2 : $tingkat+1;
				$nama = $rs->nama;
				$peta = $rs->path;
				$result = ""; $rs= "";
				$subWilayah = array();
				$strSQL = "
					SELECT w.kode,w.nama as wnama,w.tingkat,w.path,
						(SELECT COUNT(id) FROM skgakin WHERE periode_id='".$varPeriode."' AND kode_wilayah LIKE CONCAT(w.kode,'%') ) as art,
						(SELECT COUNT(id) FROM skgakin WHERE periode_id='".$varPeriode."' AND shdk=1 AND  kode_wilayah LIKE CONCAT(w.kode,'%')) as rt
					FROM tweb_wilayah w 
					WHERE ((w.kode LIKE '".fixSQL($varKode)."%') AND (w.tingkat='".$stingkat."')) 
					ORDER BY w.nama 
				";
				$query = $this->db->query($strSQL);

				foreach ($query->result() as $rs){
					$subWilayah[$rs->kode] = array(
						"nama"=>$rs->wnama,
						"tingkat"=>$rs->tingkat,
						"art"=>$rs->art,
						"rt"=>$rs->rt,
						"path"=>$rs->path,
						);
				}

				$hasil = array(
					"nama"=>$nama, 
					"path"=>$peta, 
					"tingkat"=>$tingkat, 
					"tingkatan"=>$tingkatan[$tingkat], 
					"stingkat"=>$stingkat, 
					"stingkatan"=>$tingkatan[$stingkat], 
					"sub"=>$subWilayah,
					"sql"=>$strSQL
					);
			}
		}
		return $hasil;
		
	}

	function skgakin_by_rtm($varPeriode,$varKode,$varPage){
		$sumber_data = $this->sumberdata_skgakin();
		$offset = ($varPage - 1) * LIMIT_TAMPIL;
		
		$strSQL = "
			SELECT sk.*,
				(SELECT COUNT(id) FROM tweb_penduduk WHERE rtm_no=p.rtm_no) as nanggota,
				p.nama as pnama, p.id as penduduk_id,p.rtm_no,p.sex,
				w.nama as kelurahan, 
				k.nama as kecamatan   
			FROM skgakin sk 
				LEFT JOIN tweb_penduduk p ON sk.penduduk_id=p.id 
				LEFT JOIN tweb_wilayah w ON w.tingkat=4 AND w.kode=SUBSTRING(sk.kode_wilayah,0,10) 
				LEFT JOIN tweb_wilayah k ON k.tingkat=3 AND k.kode=SUBSTRING(sk.kode_wilayah,0,7) ";
		$strClausul = "		
			WHERE ((sk.periode_id='".$varPeriode."') AND (sk.shdk=1) AND (sk.kode_wilayah LIKE '".$varKode."%')) ";
		$strLimit ="	LIMIT ".$offset.",".LIMIT_TAMPIL;
		
		$sqlSemua = "SELECT COUNT(sk.id) as numrows FROM skgakin sk ".$strClausul;
		$result = $this->db->query($sqlSemua);
		if($result){
			$rs = $result->result_array()[0];
			$nSemua = $rs['numrows'];
		}
		
		unset($result);
				
		$sqlData = $strSQL . $strClausul.$strLimit;
		$query = $this->db->query($sqlData);

		$data = array();
		foreach ($query->result() as $rs){
			$data[$rs->id] = array(
				"rtm_no"=>$rs->rtm_no,
				"penduduk_id"=>$rs->penduduk_id,
				"pnama"=>$rs->pnama,
				"sex"=>$rs->sex,
				"nanggota"=>$rs->nanggota,
				"kelurahan"=>$rs->kelurahan,
				"kecamatan"=>$rs->kecamatan,
				"rt"=>$rs->sumber,
				);
		}
		$hasil = array("n"=>$nSemua, "data"=>$data);
		return $hasil;
	}
		
	function skgakin_by_idv($varPeriode,$varKode,$varPage=1){
		$sumber_data = $this->sumberdata_skgakin();
		$offset = ($varPage - 1) * LIMIT_TAMPIL;
		
		$strSQL = "
			SELECT sk.*,
				p.nama as pnama, p.id as penduduk_id,p.nik as pnik,p.rtm_no,p.sex,p.dtlahir,
				w.nama as kelurahan, 
				k.nama as kecamatan   
			FROM skgakin sk 
				LEFT JOIN tweb_penduduk p ON sk.penduduk_id=p.id 
				LEFT JOIN tweb_wilayah w ON w.tingkat=4 AND w.kode=SUBSTRING(sk.kode_wilayah,0,10) 
				LEFT JOIN tweb_wilayah k ON k.tingkat=3 AND k.kode=SUBSTRING(sk.kode_wilayah,0,7) ";
		$strClausul = "		
			WHERE ((sk.periode_id='".$varPeriode."') AND (sk.kode_wilayah LIKE '".$varKode."%')) ";
		$strLimit ="	LIMIT ".$offset.",".LIMIT_TAMPIL;
		
		$sqlSemua = "SELECT COUNT(sk.id) as numrows FROM skgakin sk ".$strClausul;
		$result = $this->db->query($sqlSemua);
		if($result){
			$rs = $result->result_array()[0];
			$nSemua = $rs['numrows'];
		}
		
		unset($result);
				
		$sqlData = $strSQL . $strClausul.$strLimit;
		$query = $this->db->query($sqlData);

		$data = array();
		foreach ($query->result() as $rs){
			$sex = ($rs->sex==1) ? "L":"P";
			$data[$rs->id] = array(
				"id"=>$rs->id,
				"rtm_no"=>$rs->rtm_no,
				"penduduk_id"=>$rs->penduduk_id,
				"pnama"=>$rs->pnama,
				"pnik"=>$rs->pnik,
				"sex"=>$sex,
				"dtlahir"=>indonesian_date(strtotime($rs->dtlahir),"j F Y"),
				"sumber"=>$sumber_data[$rs->sumber],
				"kelurahan"=>$rs->kelurahan,
				"kecamatan"=>$rs->kecamatan,
				"rt"=>$rs->sumber,
				);
		}
		$hasil = array("n"=>$nSemua, "data"=>$data);
		return $hasil;
	}
	
	function skgakin_by_sumber($varPeriode,$varKode){
		$hasil = false;
		$strSQL = "SELECT sumber,COUNT(id) as nrs FROM skgakin 
			WHERE kode_wilayah LIKE '".$varKode."%' AND periode_id='".$varPeriode."'
		GROUP BY sumber";
		$query = $this->db->query($strSQL);
		if($query){
			$hasil = array();
			foreach ($query->result() as $rs){
				$hasil[$rs->sumber] = $rs->nrs;
			}
		}
		return $hasil;
	}
	
	function agregat_skgakin_idv_by_wilayah_by_periode($varKode=0,$varPeriode=0){
		$tingkatan = _tingkat_wilayah();
		$sumber_data = $this->sumberdata_skgakin();
		$offset = ($varPage - 1) * LIMIT_TAMPIL;
		$periode = $this->skgakin_periode();
		$varPeriode = ($varPeriode == 0) ? $periode['aktif'] : $varPeriode;
		$hasil = false;
		$strSQL = "SELECT id,nama,tingkat,path FROM tweb_wilayah WHERE kode='".fixSQL($varKode)."' LIMIT 1";
		$result = $this->db->query($strSQL);
		if($result){
			if($result->num_rows() > 0){
				$rs = $result->row();
				$tingkat = $rs->tingkat;
				$stingkat = ($tingkat == 4) ? $tingkat+2 : $tingkat+1;
				$nama = $rs->nama;
				$peta = $rs->path;
				$result = ""; $rs= "";
				$subWilayah = array();
				$strSQL = "SELECT 
						(SELECT COUNT(id) FROM skgakin WHERE periode_id='".$key."' AND kode_wilayah LIKE CONCAT(w.kode,'%') ) as art,
						w.kode,w.nama as wnama,w.tingkat,w.path 
					FROM tweb_wilayah w 
					WHERE ((w.kode LIKE '".fixSQL($varKode)."%') AND (w.tingkat='".$varPeriode."')) 
					ORDER BY w.nama 
				";
				$query = $this->db->query($strSQL);
				foreach ($query->result() as $rs){
					$subWilayah[$rs->kode] = array(
						"nama"=>$rs->wnama,
						"tingkat"=>$rs->tingkat,
						"art"=>$rs->art,
						);
				}

				$hasil = array(
					"nama"=>$nama, 
					"path"=>$peta, 
					"tingkat"=>$tingkat, 
					"tingkatan"=>$tingkatan[$tingkat], 
					"stingkat"=>$stingkat, 
					"stingkatan"=>$tingkatan[$stingkat], 
					"sub"=>$subWilayah,
					"sql"=>$strSQL
					);
			}
		}
		return $hasil;				
	}
	
	function do_hapus_idv($varID){
		$strSQL = "DELETE FROM skgakin WHERE id=".$varID;
		return false;
	}
}
