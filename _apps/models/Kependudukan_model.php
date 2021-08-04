<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kependudukan_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->model('wilayah_model');
	}
	
	function list_kawin_akta(){
		$sql   = "SELECT * FROM `tweb_penduduk_kawin_akta`";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_agama(){
		$sql   = "SELECT * FROM tweb_penduduk_agama WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_hubungan(){
		$sql   = "SELECT * FROM tweb_penduduk_hubungan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_hubunganrt(){
		$sql   = "SELECT * FROM tweb_penduduk_hubungan_rtm WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
				
	function list_pendidikan(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_pendidikan_telah(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,6)<> 'SEDANG' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_pendidikan_sedang(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan WHERE left(nama,5)<> 'TAMAT' ";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_pendidikan_kk(){
		$sql   = "SELECT * FROM tweb_penduduk_pendidikan_kk WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_partisipasi_sekolah(){
		$sql   = "SELECT * FROM `tweb_penduduk_partisipasi_sekolah`";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_pekerjaan(){
		$sql   = "SELECT * FROM tweb_penduduk_pekerjaan WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_bekerja(){
		$sql   = "SELECT * FROM tweb_penduduk_bekerja WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	function list_lapangankerja(){
		$sql   = "SELECT * FROM tweb_penduduk_lapangan_kerja WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_warganegara(){
		$sql   = "SELECT * FROM tweb_penduduk_warganegara WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_status_kawin(){
		$sql   = "SELECT * FROM tweb_penduduk_kawin WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_golongan_darah(){
		$sql   = "SELECT * FROM tweb_golongan_darah WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
	
	function list_cacat(){
		$sql   = "SELECT * FROM tweb_cacat WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function list_kronis(){
		$sql   = "SELECT * FROM tweb_sakit_menahun WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}
		
	function penduduk_sexes(){
		$sql   = "SELECT * FROM tweb_penduduk_sex WHERE 1";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		return $data;
	}

	function load_rtm($varRTM = 0){
		$hasil = false;
		if($varRTM > 0){
			$strSQL = "SELECT r.*,
				d.nama as desa,
				ds.nama as dusun
			FROM tweb_rumahtangga r 
				LEFT JOIN tweb_wilayah d ON SUBSTRING(r.kode_wilayah,1,10) = d.kode
				LEFT JOIN tweb_wilayah ds ON SUBSTRING(r.kode_wilayah,1,12) = ds.kode
			WHERE r.rtm_no='".$varRTM."'";
			$query = $this->db->query($strSQL);
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$hasil = array(
					'rtm_id'=>$rs['id'],
					'rtm_no'=>$rs['rtm_no'],
					'kode_wilayah'=>$rs['kode_wilayah'],
					'kepala_rumahtangga'=>$rs['kepala_rumahtangga'],
					'desa'=>$rs['desa'],
					'dusun'=>$rs['dusun'],
					'rw'=> substr($rs['kode_wilayah'],12,2),
					'rt'=>substr($rs['kode_wilayah'],14,2),
				);
			}
		}
		return $hasil;

	}
	
	function cari_rtm($strCari){
		$data = false;
		$strSQL = "
		SELECT p.nik as idnya, p.nama as pnama,p.rtm_no,p.kode_wilayah,p.kelas_pbdt,w.nama as desa,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE rtm_no=p.rtm_no) as anggota
		FROM tweb_penduduk p 
			LEFT JOIN tweb_wilayah w ON SUBSTRING(p.kode_wilayah,1,10)=w.kode 
		WHERE ((p.rt_hubungan=1) AND (p.nama LIKE '".$strCari."%') OR (p.rtm_no LIKE '".$strCari."%')) ORDER BY p.nama LIMIT 50";
		$data = array();
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows()>0){
				foreach ($query->result() as $row){
					$keterangan = $row->desa;
					if(strlen($row->kode_wilayah) > 10){
						if(strlen($row->kode_wilayah) == 16){
							$keterangan .= " RT ".substr($row->kode_wilayah,15,2) ."/ RW ".substr($row->kode_wilayah,13,2);
						}elseif(strlen($row->kode_wilayah) == 14){
							$keterangan .= "RW ".substr($row->kode_wilayah,-2);
						}
					}
					$indikator = ($row->kelas_pbdt > 0) ? "PBDT":"PBK/Lokal";
					$data[] = array(
						"id"=>$row->rtm_no,
						"nama"=>$row->pnama,
						"alamat"=>$keterangan,
						"indikator"=>$indikator,
						"nik"=>$row->idnya,
						"anggota"=>$row->anggota,
						);
				}
				
			}
		}
		return $data;		
	}
	
	function data_rtm($varNo = 0){
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
			$strSQL = "
			SELECT p.id,p.alamat,
				p.nama as pnama, p.nik as pnik,p.sex as sex,p.dtlahir, FLOOR(DATEDIFF (NOW(), p.dtlahir)/365) as umur, p.kawin_status as stat_kawin,
				s.nama as psex,k.nama as kawin,h.nama as hubungan   
			FROM tweb_penduduk p 
				LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				LEFT JOIN tweb_penduduk_sex s ON  p.sex=s.id
				LEFT JOIN tweb_penduduk_hubungan_rtm h ON  p.rt_hubungan=h.id
			WHERE (p.rtm_no='".fixSQL($varNo)."')";
			$query = $this->db->query($strSQL);
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
			SELECT r.*,p.alamat,
			p.nama as pnama,p.nik as pnik,p.dtlahir,FLOOR(DATEDIFF (NOW(), p.dtlahir)/365) as kepala_umur,
			s.nama as psex  
			FROM tweb_rumahtangga r 
				LEFT JOIN tweb_penduduk p ON r.rtm_no=p.rtm_no 
				LEFT JOIN tweb_penduduk_sex s ON  p.sex=s.id 
			WHERE r.rtm_no='".fixSQL($varNo)."' 
			AND p.rt_hubungan=1 LIMIT 1";

			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$rs = $query->result_array()[0];
					//$rtm_alamat = $this->wilayah_model->get_alamat($rs['kode_wilayah']);
					$hasil = array(
						"rtm_no"=>$varNo,
						"kepala"=>$rs['pnama'],
						"kepala_sex"=>$rs['psex'],
						"kepala_dtlahir"=>$rs['dtlahir'],
						"kepala_umur"=>$rs['kepala_umur'],
						"alamat"=>$rs['alamat'],
						"wilayah"=>$this->wilayah_model->get_alamat($rs['kode_wilayah']),
						"kode_wilayah"=>$rs['kode_wilayah'],
						"kelas_pbdt"=>$rs['kelas_pbdt'],
						"kelas_pbk"=>$rs['kelas_pbk'],
						"anggota"=>$anggota,
						);
				}
			}
		}
		
		return $hasil;  
		
	}
	
	
	function data_penduduk($varID){
		$hasil =false;
		if($varID > 0){
			$strSQL = "
			SELECT p.*,FLOOR(DATEDIFF (NOW(), p.dtlahir)/365) as umur, 
				s.nama as psex,
				k.nama as pkawin,
				hk.nama as hubungan_kk,  
				h.nama as phamil,  
				cc.nama as pcacat_jenis,  
				sk.nama as psakit_kronis,  
				hr.nama as hubungan_rtm,
				jpendidikan.nama as pjenjang_pendidikan, 
				sekolah.nama as psekolah,
				i.nama as pijazah,
				kerja.nama as pkerja, 
				kb.nama as pbidang_kerja,
				kj.nama as pkerja_kedudukan 
			FROM tweb_penduduk p 
				LEFT JOIN tweb_penduduk_kawin k ON  p.kawin_status=k.id
				LEFT JOIN tweb_penduduk_sex s ON  p.sex=s.id
				LEFT JOIN tweb_penduduk_hubungan_rtm hr ON  p.rt_hubungan=hr.id
				LEFT JOIN tweb_penduduk_hubungan hk ON  p.kk_hubungan=hk.id
				LEFT JOIN tweb_penduduk_hamil h ON  p.hamil=h.id
				LEFT JOIN tweb_sakit_menahun sk ON  p.sakit_kronis=sk.id
				LEFT JOIN tweb_cacat cc ON  p.cacat_jenis=cc.id
				LEFT JOIN tweb_penduduk_sekolah sekolah ON  p.sekolah=sekolah.id
				LEFT JOIN tweb_penduduk_jenjang_sekolah jpendidikan ON  p.jenjang_pendidikan=jpendidikan.id
				LEFT JOIN tweb_penduduk_ijazah i ON p.ijazah_tertinggi=i.id
				LEFT JOIN tweb_penduduk_bekerja kerja ON p.kerja=kerja.id
				LEFT JOIN tweb_penduduk_bekerja_bidang kb ON  p.kerja_bidang=kb.id
				LEFT JOIN tweb_penduduk_bekerja_jabatan kj ON  p.kerja_kedudukan=kj.id
			WHERE (p.id='".fixSQL($varID)."')";
			$query = $this->db->query($strSQL);
			if($query){
				if($query->num_rows() > 0){
					$rs = $query->result_array()[0];
					$hasil = $rs;
				}
			}
		}
		return $hasil;
	}
	
	function api_nik_sktm($varNik=0){
		$hasil = false;
		$strSQL = "
		SELECT s.id, p.* 
		FROM skgakin s 
			LEFT JOIN tweb_penduduk p ON s.penduduk_id=p.id 
		WHERE p.nik='".$varNik."' AND p.status=1";
		
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$rs = $query->result_array()[0];
				$hasil = array('status'=>True, 'pesan'=>'Data NIK DITEMUKAN dlm Basis Data SKGAKIN','detail'=>$rs);
			}else{
				$hasil = array('status'=>'Error', 'pesan'=>'Data NIK tidak ditemukan dlm Basis Data SKGAKIN');
			}
		}
		return $hasil;
		
	}
	
	function ekspor_rtm($varKode=0){
		$hasil = false;
		$strSQL = "SELECT r.* FROM tweb_rumahtangga r WHERE r.kode_wilayah LIKE '".fixSQL($varKode)."%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}
	
	function ekspor_idv($varKode=0){
		$hasil = false;
		$strSQL = "SELECT `nik`, 
		`rtm_no`, 
		`kode_wilayah`, 
		`alamat`, 
		`kk_nomor`, 
		`nama`, 
		`kk_urutan`, 
		`rt_hubungan`, 
		`kk_hubungan`, 
		`sex`, 
		`tlahir`, 
		`dtlahir`, 
		`kawin_status`, 
		`kawin_buku`, 
		`kk_terdaftar`, 
		`kartu_identitas`, 
		`hamil`, 
		`cacat_jenis`, 
		`sakit_kronis`, 
		`sekolah`, 
		`jenjang_pendidikan`, 
		`kelas_tertinggi`, 
		`ijazah_tertinggi`, 
		`kerja`, 
		`kerja_jam`, 
		`kerja_bidang`, 
		`kerja_kedudukan`, 
		`kelas_pbdt`, 
		`is_pbdt`, 
		`is_pbk`, 
		`kelas_pbk`, 
		`status` 
		 FROM tweb_penduduk WHERE kode_wilayah LIKE '".fixSQL($varKode)."%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}
	
	function ekspor_bdt($varKode=0){
		$hasil = false;
		$strSQL = "SELECT `periode_id`, `responden_id`, `kode_wilayah`, `kategori_id`, `param_id`, `param_kode`, `param_opsi_kode`, `param_opsi_value` 
		FROM bdt_data 
		WHERE  kode_wilayah LIKE '".fixSQL($varKode)."%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}
	
	function ekspor_periode(){
		$hasil = false;
		$strSQL = "SELECT *  FROM pbk_periode WHERE 1";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
		
	}
	
	function demografi_wilayah($varKode){
		$hasil = false;
		$varL = strlen($varKode);
		switch ($varL)
		{
			case 14:
				$param = array(0=>'rt_kode',1=>7);
				break;
			case 12:
				$param = array(0=>'rw_kode',1=>6);
				break;
			case 10:
				$param = array(0=>'dusun_kode',1=>5);
				break;
			case 7:
				$param = array(0=>'desa_kode',1=>4);
				break;
			default:
				$param = array(0=>'kecamatan_kode',1=>3);
		}
		// Rumah Tangga (".$param[0]."=w.kode)
		$strSQL = "
			SELECT w.kode,
			(SELECT COUNT(id) FROM tweb_rumahtangga WHERE (kode_wilayah LIKE CONCAT(w.kode,'%')) ) as `sumRTS`,
			(SELECT COUNT(id) FROM tweb_keluarga WHERE (kode_wilayah LIKE CONCAT(w.kode,'%')) ) as `sumKK`,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE (kode_wilayah LIKE CONCAT(w.kode,'%')) AND (sex=1))as `sumLk`,
			(SELECT COUNT(id) FROM tweb_penduduk WHERE (kode_wilayah LIKE CONCAT(w.kode,'%')) AND (sex=2)) as `sumPr`
			FROM tweb_wilayah w
			WHERE w.kode LIKE '".$varKode."%' AND w.tingkat=".$param[1]."";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$sumPddk = $rs['sumLk']+$rs['sumPr'];
					$hasil[$rs['kode']] = array(
						'rts'=>$rs['sumRTS'],
						'kk'=>$rs['sumKK'],
						'pendudukLk'=>$rs['sumLk'],
						'pendudukPr'=>$rs['sumPr'],
						'penduduk'=>$sumPddk,
					);
				}
			}
		}
		return $hasil;

	}

	function rumahtangga_by_wilayah($varKode){
		$hasil = false;
		$strSQL = "SELECT r.*,SUBSTRING(r.kode_wilayah,13,2) as `rw`,
			SUBSTRING(r.kode_wilayah,15,2) as `rt`,
			(SELECT COUNT(id) FROM tweb_keluarga WHERE rtm_no=r.rtm_no) as `sumKK`,
			dusun.nama as `dusun`
			FROM tweb_rumahtangga r
				LEFT JOIN tweb_wilayah dusun ON SUBSTRING(r.kode_wilayah,1,12)=dusun.kode
			WHERE (r.kode_wilayah LIKE '".$varKode."%' )";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}

	function penduduk_by_wilayah($varKode){
		$hasil = false;
		$strSQL = "SELECT r.*,SUBSTRING(r.kode_wilayah,13,2) as `rw`,
			SUBSTRING(r.kode_wilayah,15,2) as `rt`,
			(SELECT COUNT(id) FROM tweb_keluarga WHERE rtm_no=r.rtm_no) as `sumKK`,
			dusun.nama as `dusun`
			FROM tweb_penduduk r
				LEFT JOIN tweb_wilayah dusun ON SUBSTRING(r.kode_wilayah,1,12)=dusun.kode
			WHERE (r.kode_wilayah LIKE '".$varKode."%' )";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$hasil = $query->result_array();
			}
		}
		return $hasil;
	}

}
