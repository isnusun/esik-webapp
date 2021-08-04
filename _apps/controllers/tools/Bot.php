<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {

	function auth(){

	}

	function sync_dukcapil(){
		
	}

	function sqlite(){
		$dblite = FCPATH."../src/WONOGIRI/33_12.db";
		if(file_exists($dblite)){
			echo "OK".$dblite;
		}else{
			echo "DBLITE ga ada";
		}
	}

	function index(){
		// echo var_dump(@$_REQUEST);
	}

	function update_password(){
		$strSQL = "UPDATE tweb_users SET passwt='".password_hash('Wonogiri2019',PASSWORD_DEFAULT)."' WHERE id=1 OR id=2";
		$query = $this->db->query($strSQL);
		if($query){
			echo "SUKSES UPDATE id1 dan is2";
		}
	}

	function generateUserDesa(){
		$base = 3311;
		$strPassword = 'Sukoharjo2019';
		$strSQL = "SELECT d.nama,d.kode, 
			kec.nama as kecamatan
		FROM tweb_wilayah d 
			LEFT JOIN tweb_wilayah kec ON SUBSTR(d.kode,1,7) = kec.kode
		WHERE d.tingkat=4 AND d.kode LIKE '".$base."%'";
		$query = $this->db->query($strSQL);
		$config = array();
		echo "<pre>INSERT INTO tweb_users(`situs_id`,`nama`,`userid`,`passwt`,`tingkat`,`email`,`status`,`wilayah`)  VALUES \n";
		foreach ($query->result_array() as $rs){
			// $config[$rs->label] = $rs->data;
			$strNama = str_replace('  ','',trim($rs['nama']));
			$kecamatan = trim($rs['kecamatan']);
			$nama = 'Petugas '. $rs['nama'];
			$userid = fixNamaUrl($strNama)."-".fixNamaUrl($kecamatan)."";
			$email = fixNamaUrl($strNama).".sukoharjo@sukoharjokab.go.id";
			// echo "<br />".fixNamaUrl($strNama).".wonogiri";
			$strSQL = "('886','".fixSQL($nama)."','".fixSQL($userid)."','".password_hash($strPassword,PASSWORD_DEFAULT)."',3,'".fixSQL($email)."',1,'".$rs['kode']."'), \n";
			echo $strSQL;
		}
		echo "</pre>";
		$this->output->enable_profiler(TRUE);
	}

	function generateWilayah(){
		// Semua Desa/Kelurahan
		$kode_base = '3311';
		$desa = array();
		$strSQL = "SELECT w.kode,w.nama,
			(SELECT nama FROM tweb_wilayah  WHERE tingkat=3 AND kode=SUBSTR(w.kode,1,6)) as kecamatan
		 FROM tweb_wilayah w WHERE w.kode LIKE '".$kode_base."%' AND w.tingkat=4";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		foreach ($query->result_array() as $rs){
			$desa[$rs['kode']] = array(
				'nama'=>$rs['nama'],
				'kecamatan'=>$rs['kecamatan'],
			);
		}
		$nomer = 0;
		
		foreach($desa as $d => $ds){
			$nomer++;
			$strSQL = "SELECT `KODE_DESA`,`Alamat`  FROM `TABLE39` WHERE KODE_DESA='".fixSQL($d)."'";
			$dusuns = array();
			$ds_key=1;
	
			$rws = array();
			$rts = array();
			$query = $this->db->query($strSQL);
			$sasaran = $query->num_rows();
			foreach ($query->result_array() as $rs){

				$alamat = strtolower($rs['Alamat']);
				// echo strpos($alamat,'dusun');
				if(strpos($alamat,'dusun ') !== false ){
					$alamat = str_replace('dusun ','',$alamat);
				}
				if(strpos($alamat,'dukuh ') !== false ){
					$alamat = str_replace('dukuh ','',$alamat);
				}
				if(strpos($alamat,'dk ') !== false ){
					$alamat = str_replace('dk ','',$alamat);
				}
				if(strpos($alamat,'dk. ') !== false ){
					$alamat = str_replace('dk. ','',$alamat);
				}
				if(strpos($alamat,'ds ') !== false ){
					$alamat = str_replace('ds ','',$alamat);
				}
				if(strpos($alamat,'kampung ') !== false ){
					$alamat = str_replace('kampung ','',$alamat);
				}
				$alamat =trim($alamat);
				$part = explode(' ',$alamat);
				// echo $alamat."<br />";
				$pos_rt = 0;
				$pos_rw = 0;
				foreach($part as $key=>$p){
					if($p=='rt'){
						$pos_rt = $key;
					}
					if($p=='rw'){
						$pos_rw = $key;
					}
				}
				$nama_dusun = "";
				if(($pos_rt > 0) && ($pos_rw > 0)){
					if($pos_rt < $pos_rw){
						$i=0;
						while($i < $pos_rt){
							$nama_dusun .= $part[$i]." ";
							$i++;
						}
					}else{
						$i=0;
						while($i < $pos_rw){
							$nama_dusun .= $part[$i]." ";
							$i++;
						}
					}
				}
				$nama_dusun = trim($nama_dusun);
				if(strlen($nama_dusun) > 0){
					if(!in_array($nama_dusun,$dusuns)){
						$key_dusun = $d.str_pad($ds_key,2,0,STR_PAD_LEFT);
						// echo $key_dusun;
						$dusuns[$key_dusun] = $nama_dusun;
						$ds_key++;
					}
				}else{
					if(!in_array($nama_dusun,$dusuns)){
						$key_dusun = $d.'00';
						// echo $key_dusun;
						$dusuns[$key_dusun] = $nama_dusun;
					}
				}
				// $strSQL = "UPDATE bdt_rts SET kode_wilayah='".$key_dusun."' WHERE lead_id=".$rs['lead_id'].";\n";
				// echo $strSQL;
			} // end foreach
			echo "<h1>".$nomer." Desa ".$d." ".$ds['nama']." - ".$ds['kecamatan']." (".$sasaran.")</h1>";
			
			ksort($dusuns);
			// echo var_dump($dusuns);
			$strSQL = "INSERT INTO tweb_wilayah(`nama`,`tingkat`,`kode`) VALUES";
			$m = 1;
			$n = count($dusuns);
			foreach($dusuns as $key=>$rows){
				$strKoma = ($m < $n) ? ",":";";
				$strSQL .= "('".fixSQL(strtoupper($rows))."',5,'".fixSQL($key)."')".$strKoma."\n";
				$m++;
			}
			if($this->db->query($strSQL)){
				echo "<h2 style=\"color:green\">".$rs['KODE_DESA']." : ".$ds['nama']." OK </h1>";
			}
			// echo $strSQL;
		}

	}

	function generate_rw(){
		$strSQL = "SELECT DISTINCT(SUBSTRING(r.kode_wilayah,1,15)) as kode_rw,COUNT(r.id),
			w.nama as desa,
			d.nama as dusun 
		FROM `tweb_rumahtangga` r
			LEFT JOIN tweb_wilayah w ON SUBSTRING(r.kode_wilayah,1,10) = w.kode
			LEFT JOIN tweb_wilayah d ON SUBSTRING(r.kode_wilayah,1,12) = d.kode
		GROUP BY kode_rw";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			$strSQL = "INSERT INTO tweb_wilayah(`nama`,`kode`,`tingkat`) VALUES\n";
			echo $strSQL;
			foreach ($query->result_array() as $rs){
				if(strlen($rs['kode_rw']) == 15){
					echo "('".substr($rs['kode_rw'],-3)."','".substr($rs['kode_rw'],0,15)."',6),\n";
					// echo "<br />".$rs['kode_rw']." RW ".substr($rs['kode_rw'],-3) ." DESA ".$rs['desa'] ." Dusun ".$rs['dusun'];
				}else{
					// echo "<br />RW KUrang Data";
				}
			}
		}

	}

	function generate_rt(){
		$strSQL = "SELECT DISTINCT(r.kode_wilayah) as kode_rw,COUNT(r.id)
		FROM `tweb_rumahtangga` r
		GROUP BY kode_rw";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			$strSQL = "INSERT INTO tweb_wilayah(`nama`,`kode`,`tingkat`) VALUES\n";
			echo $strSQL;
			$nomer =1;
			foreach ($query->result_array() as $rs){
				if(fmod($nomer,1000)==0){
					echo "('".substr($rs['kode_rw'],-3)."','".$rs['kode_rw']."',7);\n";
					echo $strSQL;
				}else{
					echo "('".substr($rs['kode_rw'],-3)."','".$rs['kode_rw']."',7),\n";
				}
				$nomer++;
			}
		}

	}

	
	function generateIndikator(){
		$file_name = FCPATH."/assets/uploads/OPTION_2018.xls";
		$this->load->library('PHPExcel');

		$read   = PHPExcel_IOFactory::createReaderForFile($file_name);

		$read->setReadDataOnly(true);
		$excel  = $read->load($file_name);
		PHPExcel_Calculation::getInstance($excel)->cyclicFormulaCount = 1;
		$sheets = $read->listWorksheetNames($file_name);//baca semua sheet yang ada
		$i=1;
		foreach($sheets as $sheet){
			//$_sheet = $excel->setActiveSheetIndexByName($sheet);
			$tps_no = intval(preg_replace('/[^0-9]+/', '', $sheet), 10);
			if(!is_numeric($tps_no)){
				$str_tps = strtolower($sheet);
				$str_tps = str_replace('tps','',$str_tps);
				$str_tps = str_replace('(','',$str_tps);
				$str_tps = str_replace(')','',$str_tps);
				$str_tps = str_replace(' ','',$str_tps);
				$tps_no = _roman2int($str_tps);
			}
			$_sheet = $excel->setActiveSheetIndexByName($sheet);
			$maxRow = $_sheet->getHighestRow();
			$maxCol = $_sheet->getHighestColumn();
			$sheetData = $_sheet->toArray(null, true, true, true);
			$nama = "";
			echo var_dump($sheet);
			if($sheet == 'Sheet1'){
				$strSQLIndikator = "INSERT INTO bdt_indikator(`responden`,`nourut`,`nama`,`label`,`jenis`) VALUES \n";
				$strSQLOpsi = "INSERT INTO bdt_indikator_opsi(`nourut`,`indikator_nama`,`nama`,`label`) VALUES\n";
				$nourut = 0;
				foreach($sheetData as $key=>$rs){
					if(@$rs['B'] !== null){
						$nama = trim(strtolower($rs['B'])); 
						$nourut++;
					}
					if(@$rs['B'] !== null){
						// echo "<br />".$i."=".$nama." label =>".$rs['C'] ." ->";
						$jenis = (@$rs['D'] !== null) ? "pilihan":"isian";
						$responden = ($nourut < 55) ? "rts":"art";
						$strSQLIndikator .="('".$responden."','".$nourut."','".fixSQL($nama)."','".fixSQL($rs['C'])."','".$jenis."'),\n"; 
					}
					if(@$rs['D'] !== null){
						$label = (@$rs['E'] !== null) ? $rs['E']:$rs['D'];
						$strSQLOpsi .= "('".$rs['D']."','".fixSQL($nama)."','".trim($rs['D'])."','".fixSQL($label)."'),\n";
						// echo "<br />Opsi nya".$rs['D']." : ".$rs['E'];
					}else{
						// echo "<br />Isian";
					}

					$i++;
				}
	
			}
		}
		echo "<pre>".$strSQLIndikator."</pre>";
		echo "<pre>".$strSQLOpsi."</pre>";
	}

	function import_bdt_rts(){
		$kode_base = '3311';
		
		$sumber = "SKH_RTS";
		// echo var_dump($dusuns);
		$desas = array();
		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=4 AND kode LIKE '".$kode_base."%' AND proses=1 LIMIT 1";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$desa = $query->result_array()[0];
				$kode_desa = $desa['kode'];
				$dusuns = array();
				$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=5 AND kode LIKE '".$desa['kode']."%'";
				$query = $this->db->query($strSQL);
				if($query){
					foreach ($query->result_array() as $rs){
						$dusuns[trim(strtolower($rs['nama']))] = $rs['kode'];
					}
				}
				// echo var_dump($dusuns);
				$rws = array();
				$rts = array();
		
				// $strSQL = "SELECT * FROM ".$sumber." WHERE kdgabungan4='".$desa['kode']."'";
				$kd_prop = substr($desa['kode'],0,2);
				$kd_kab = substr($desa['kode'],2,2);
				$kd_kec = substr($desa['kode'],4,3);
				$kd_desa = substr($desa['kode'],7);
				// kdprop='".$kd_prop."' AND
				// kdkab='".$kd_kab."' AND
				// kdkec='".$kd_kec."' AND
				// kddesa='".$kd_desa."' 

				$strSQL = "SELECT * FROM ".$sumber." WHERE DESA='".fixSQL($desa['nama'])."'";
				// echo $strSQL;
				$query = $this->db->query($strSQL);
				if($query){
					// $strSQL = "INSERT INTO bdt_rts(`kode_wilayah`,`idbdt`, `ruta6`, `nopesertapbdt`, `nopbdtkemsos`, `vector1`, `vector2`, `vector3`, `vector4`, `kdgabungan4`, `kdprop`, `kdkab`, `kdkec`, `kddesa`, `alamat`, `adapkh`, `adapbdt`, `adakks2016`, `adakks2017`, `adapbi`, `adadapodik`, `adabpnt`, `nopesertapkh`, `nopesertakks2016`, `nopesertapbi`, `pesertakip`, `nokartudebit`, `nama_sls`, `nama_krt`, `jumlah_art`, `jumlah_keluarga`, `sta_bangunan`, `sta_lahan`, `luas_lantai`, `lantai`, `dinding`, `kondisi_dinding`, `atap`, `kondisi_atap`, `jumlah_kamar`, `sumber_airminum`, `nomor_meter_air`, `cara_peroleh_airminum`, `sumber_penerangan`, `daya`, `nomor_pln`, `bb_masak`, `nomor_gas`, `fasbab`, `kloset`, `buang_tinja`, `ada_tabung_gas`, `ada_lemari_es`, `ada_ac`, `ada_pemanas`, `ada_telepon`, `ada_tv`, `ada_emas`, `ada_laptop`, `ada_sepeda`, `ada_motor`, `ada_mobil`, `ada_perahu`, `ada_motor_tempel`, `ada_perahu_motor`, `ada_kapal`, `aset_tak_bergerak`, `luas_atb`, `rumah_lain`, `jumlah_sapi`, `jumlah_kerbau`, `jumlah_kuda`, `jumlah_babi`, `jumlah_kambing`, `sta_art_usaha`, `sta_kks`, `sta_kip`, `sta_kis`, `sta_bpjs_mandiri`, `sta_jamsostek`, `sta_asuransi`, `sta_pkh`, `sta_rastra`, `sta_kur`, `sta_keberadaan_rt`, `percentile`) VALUES\n";
					$strSQLBDT = "INSERT INTO bdt_rts(`periode_id`,`kode_wilayah`,`kdgabungan4`, `kdprop`, `kdkab`, `kdkec`, `kddesa`,`IDBDT`, `Alamat`, `NoPesertaPKH`, `Nama_SLS`, `Nama_KRT`, `Jumlah_ART`, `Jumlah_Keluarga`, `sta_bangunan`, `sta_lahan`, `luas_lantai`, `lantai`, `dinding`, `kondisi_dinding`, `atap`, `kondisi_atap`, `jumlah_kamar`, `sumber_airminum`, `nomor_meter_air`, `cara_peroleh_airminum`, `sumber_penerangan`, `daya`, `nomor_pln`, `bb_masak`, `nomor_gas`, `fasbab`, `kloset`, `buang_tinja`, `ada_tabung_gas`, `ada_lemari_es`, `ada_ac`, `ada_pemanas`, `ada_telepon`, `ada_tv`, `ada_emas`, `ada_laptop`, `ada_sepeda`, `ada_motor`, `ada_mobil`, `ada_perahu`, `ada_motor_tempel`, `ada_perahu_motor`, `ada_kapal`, `aset_tak_bergerak`, `luas_atb`, `rumah_lain`, `jumlah_sapi`, `jumlah_kerbau`, `jumlah_kuda`, `jumlah_babi`, `jumlah_kambing`, `sta_art_usaha`, `sta_kks`, `sta_kip`, `sta_kis`, `sta_bpjs_mandiri`, `sta_jamsostek`, `sta_asuransi`, `sta_pkh`, `sta_rastra`, `sta_kur`, `sta_keberadaan_RT`, `percentile`) VALUES\n";
					$strSQLRTS = "INSERT INTO tweb_rumahtangga(`kode_wilayah`, `idbdt`, `nama_kepala_rumah_tangga`) VALUES \n";
					$nomer=1;
					$numrows = $query->num_rows();
					if($numrows > 0){
						foreach ($query->result_array() as $rs){
							$kode_wilayah = $kd_desa;
							$alamat = $rs['Alamat'];
							$alamat = trim(strtolower($alamat));
							$alamat = str_replace('dukuh ','',$alamat);
							$alamat = str_replace('dusun ','',$alamat);
							$alamat = str_replace('dsn. ','',$alamat);
							$alamat = str_replace('dk. ','',$alamat);
							$alamat = str_replace('dk ','',$alamat);
							$alamat = str_replace('rt.','rt ',$alamat);
							$alamat = str_replace('rw.','rw ',$alamat);
							$alamat = str_replace('lingkungan ','',$alamat);
							$alamat = str_replace('  ',' ',$alamat);
			
							$part = explode(' ',$alamat);
							// echo $alamat."<br />";
							$pos_rt = 0;
							$pos_rw = 0;
							$nama_rw = '000';
							$nama_rt = '000';
							foreach($part as $key=>$p){
								if($p=='rt'){
									$pos_rt = $key;
								}
								if($p=='rw'){
									$pos_rw = $key;
								}
							}
							$nama_dusun = "";
							// echo "\n pos_rt = ".$pos_rt." pos_rw = ".$pos_rw." ";
							if(($pos_rt > 0) && ($pos_rw > 0)){
								$pos_nama_rt = $pos_rt+1;
								if(array_key_exists($pos_nama_rt,$part)){
									$nama_rt = $part[$pos_nama_rt];
								}
			
								$pos_nama_rw = $pos_rw+1;
								if(array_key_exists($pos_nama_rw,$part)){
									$nama_rw = $part[$pos_nama_rw];
								}
			
								if($pos_rt < $pos_rw){
									$i=0;
									while($i < $pos_rt){
										$nama_dusun .= $part[$i]." ";
										$i++;
									}
								}else{
									$i=0;
									while($i < $pos_rw){
										$nama_dusun .= $part[$i]." ";
										$i++;
									}
								}
							}
							$nama_dusun = (strlen($nama_dusun) == 0)? "-":$nama_dusun;
							// echo var_dump($dusuns);
							// $dusun = (array_key_exists($nama_dusun,$dusuns[$kode_desa])) ? "ada di array dusuns": "takada";
							$nama_rt = str_pad($nama_rt,3,'0',STR_PAD_LEFT);
							$nama_rw = str_pad($nama_rw,3,'0',STR_PAD_LEFT);
							$nama_dusun = trim($nama_dusun);
							if(array_key_exists($nama_dusun,$dusuns)){
								$kode_dusun = $dusuns[$nama_dusun];
							}else{
								$kode_dusun = $dusuns['-'];
							}
							$kode_wilayah = $kode_dusun.$nama_rw.$nama_rt;
							$kode_wilayah = preg_replace( '/[^0-9]/', '', $kode_wilayah);

							$warna = (strlen($kode_wilayah) != 18) ? "style='color:#c00'":"";
		
							$strKoma = ($nomer < $numrows) ? ",\n":";";
							// echo "<pre><strong>".$nomer."</strong>
							// 	ALAMAT : ".$alamat."
							// 	DESA : ".$kode_desa." - ".$desa['kode']." -- ".$desa['nama']."
							// 	DUSUN : ".$nama_dusun."
							// 	KODE DUSUN : ".$kode_dusun."
							// 	RW : ".$nama_rw."
							// 	RT : ".$nama_rt."
							// 	<span ".$warna.">KODE WILAYAH : ".$kode_wilayah."</span>
							// </pre>";
							// echo "('".fixSQL($kode_wilayah)."','".fixSQL($rs['idbdt'])."', '".fixSQL($rs['ruta6'])."', '".fixSQL($rs['nopesertapbdt'])."', '".fixSQL($rs['kdprop'])."', '".fixSQL($rs['kdkab'])."', '".fixSQL($rs['kdkec'])."', '".fixSQL($rs['kddesa'])."', '".fixSQL($rs['alamat'])."', '".fixSQL($rs['nopesertapkh'])."', '".fixSQL($rs['nopesertakks2016'])."', '".fixSQL($rs['nopesertapbi'])."', '".fixSQL($rs['pesertakip'])."', '".fixSQL($rs['nama_sls'])."', '".fixSQL($rs['nama_krt'])."', '".fixSQL($rs['jumlah_art'])."', '".fixSQL($rs['jumlah_keluarga'])."', '".fixSQL($rs['sta_bangunan'])."', '".fixSQL($rs['sta_lahan'])."', '".fixSQL($rs['luas_lantai'])."', '".fixSQL($rs['lantai'])."', '".fixSQL($rs['dinding'])."', '".fixSQL($rs['kondisi_dinding'])."', '".fixSQL($rs['atap'])."', '".fixSQL($rs['kondisi_atap'])."', '".fixSQL($rs['jumlah_kamar'])."', '".fixSQL($rs['sumber_airminum'])."', '".fixSQL($rs['nomor_meter_air'])."', '".fixSQL($rs['cara_peroleh_airminum'])."', '".fixSQL($rs['sumber_penerangan'])."', '".fixSQL($rs['daya'])."', '".fixSQL($rs['nomor_pln'])."', '".fixSQL($rs['bb_masak'])."', '".fixSQL($rs['nomor_gas'])."', '".fixSQL($rs['fasbab'])."', '".fixSQL($rs['kloset'])."', '".fixSQL($rs['buang_tinja'])."', '".fixSQL($rs['ada_tabung_gas'])."', '".fixSQL($rs['ada_lemari_es'])."', '".fixSQL($rs['ada_ac'])."', '".fixSQL($rs['ada_pemanas'])."', '".fixSQL($rs['ada_telepon'])."', '".fixSQL($rs['ada_tv'])."', '".fixSQL($rs['ada_emas'])."', '".fixSQL($rs['ada_laptop'])."', '".fixSQL($rs['ada_sepeda'])."', '".fixSQL($rs['ada_motor'])."', '".fixSQL($rs['ada_mobil'])."', '".fixSQL($rs['ada_perahu'])."', '".fixSQL($rs['ada_motor_tempel'])."', '".fixSQL($rs['ada_perahu_motor'])."', '".fixSQL($rs['ada_kapal'])."', '".fixSQL($rs['aset_tak_bergerak'])."', '".fixSQL($rs['luas_atb'])."', '".fixSQL($rs['rumah_lain'])."', '".fixSQL($rs['jumlah_sapi'])."', '".fixSQL($rs['jumlah_kerbau'])."', '".fixSQL($rs['jumlah_kuda'])."', '".fixSQL($rs['jumlah_babi'])."', '".fixSQL($rs['jumlah_kambing'])."', '".fixSQL($rs['sta_art_usaha'])."', '".fixSQL($rs['sta_kks'])."', '".fixSQL($rs['sta_kip'])."', '".fixSQL($rs['sta_kis'])."', '".fixSQL($rs['sta_bpjs_mandiri'])."', '".fixSQL($rs['sta_jamsostek'])."', '".fixSQL($rs['sta_asuransi'])."', '".fixSQL($rs['sta_pkh'])."', '".fixSQL($rs['sta_rastra'])."', '".fixSQL($rs['sta_kur'])."', '".fixSQL($rs['sta_keberadaan_rt'])."', '".fixSQL($rs['percentile'])."'),\n";
							
							// $strSQLBDT .="('4','".fixSQL($kode_wilayah)."','".fixSQL($rs['IDBDT'])."', '".fixSQL($rs['RUTA6'])."', '".fixSQL($rs['NoPesertaPBDT'])."', '".fixSQL($kd_prop)."', '".fixSQL($kd_kab)."', '".fixSQL($kd_kec)."', '".fixSQL($rs['KODE_DESA'])."', '".fixSQL($rs['Alamat'])."', '".fixSQL($rs['NoPesertaPKH'])."', '".fixSQL($rs['NoPesertaKKS2016'])."', '".fixSQL($rs['NoPesertaPBI'])."', '".fixSQL($rs['PesertaKIP'])."', '".fixSQL($rs['Nama_SLS'])."', '".fixSQL($rs['Nama_KRT'])."', '".fixSQL($rs['Jumlah_ART'])."', '".fixSQL($rs['Jumlah_Keluarga'])."', '".fixSQL($rs['sta_bangunan'])."', '".fixSQL($rs['sta_lahan'])."', '".fixSQL($rs['luas_lantai'])."', '".fixSQL($rs['lantai'])."', '".fixSQL($rs['dinding'])."', '".fixSQL($rs['kondisi_dinding'])."', '".fixSQL($rs['atap'])."', '".fixSQL($rs['kondisi_atap'])."', '".fixSQL($rs['jumlah_kamar'])."', '".fixSQL($rs['sumber_airminum'])."', '".fixSQL($rs['nomor_meter_air'])."', '".fixSQL($rs['cara_peroleh_airminum'])."', '".fixSQL($rs['sumber_penerangan'])."', '".fixSQL($rs['daya'])."', '".fixSQL($rs['nomor_pln'])."', '".fixSQL($rs['bb_masak'])."', '".fixSQL($rs['nomor_gas'])."', '".fixSQL($rs['fasbab'])."', '".fixSQL($rs['kloset'])."', '".fixSQL($rs['buang_tinja'])."', '".fixSQL($rs['ada_tabung_gas'])."', '".fixSQL($rs['ada_lemari_es'])."', '".fixSQL($rs['ada_ac'])."', '".fixSQL($rs['ada_pemanas'])."', '".fixSQL($rs['ada_telepon'])."', '".fixSQL($rs['ada_tv'])."', '".fixSQL($rs['ada_emas'])."', '".fixSQL($rs['ada_laptop'])."', '".fixSQL($rs['ada_sepeda'])."', '".fixSQL($rs['ada_motor'])."', '".fixSQL($rs['ada_mobil'])."', '".fixSQL($rs['ada_perahu'])."', '".fixSQL($rs['ada_motor_tempel'])."', '".fixSQL($rs['ada_perahu_motor'])."', '".fixSQL($rs['ada_kapal'])."', '".fixSQL($rs['aset_tak_bergerak'])."', '".fixSQL($rs['luas_atb'])."', '".fixSQL($rs['rumah_lain'])."', '".fixSQL($rs['jumlah_sapi'])."', '".fixSQL($rs['jumlah_kerbau'])."', '".fixSQL($rs['jumlah_kuda'])."', '".fixSQL($rs['jumlah_babi'])."', '".fixSQL($rs['jumlah_kambing'])."', '".fixSQL($rs['sta_art_usaha'])."', '".fixSQL($rs['sta_kks'])."', '".fixSQL($rs['sta_kip'])."', '".fixSQL($rs['sta_kis'])."', '".fixSQL($rs['sta_bpjs_mandiri'])."', '".fixSQL($rs['sta_jamsostek'])."', '".fixSQL($rs['sta_asuransi'])."', '".fixSQL($rs['sta_pkh'])."', '".fixSQL($rs['sta_rastra'])."', '".fixSQL($rs['sta_kur'])."', '".fixSQL($rs['sta_keberadaan_rt'])."', '".fixSQL($rs['percentile'])."')".$strKoma;

							$strSQLBDT .="('4','".fixSQL($kode_wilayah)."','".fixSQL($kd_desa)."','".fixSQL($kd_prop)."','".fixSQL($kd_kab)."','".fixSQL($kd_kec)."','".fixSQL($kd_desa)."','".fixSQL($rs['IDBDT'])."', '".fixSQL($rs['Alamat'])."', '".fixSQL($rs['NoPesertaPKH'])."', '".fixSQL($rs['Nama_SLS'])."', '".fixSQL($rs['Nama_KRT'])."', '".fixSQL($rs['Jumlah_ART'])."', '".fixSQL($rs['Jumlah_Keluarga'])."', '".fixSQL($rs['sta_bangunan'])."', '".fixSQL($rs['sta_lahan'])."', '".fixSQL($rs['luas_lantai'])."', '".fixSQL($rs['lantai'])."', '".fixSQL($rs['dinding'])."', '".fixSQL($rs['kondisi_dinding'])."', '".fixSQL($rs['atap'])."', '".fixSQL($rs['kondisi_atap'])."', '".fixSQL($rs['jumlah_kamar'])."', '".fixSQL($rs['sumber_airminum'])."', '".fixSQL($rs['nomor_meter_air'])."', '".fixSQL($rs['cara_peroleh_airminum'])."', '".fixSQL($rs['sumber_penerangan'])."', '".fixSQL($rs['daya'])."', '".fixSQL($rs['nomor_pln'])."', '".fixSQL($rs['bb_masak'])."', '".fixSQL($rs['nomor_gas'])."', '".fixSQL($rs['fasbab'])."', '".fixSQL($rs['kloset'])."', '".fixSQL($rs['buang_tinja'])."', '".fixSQL($rs['ada_tabung_gas'])."', '".fixSQL($rs['ada_lemari_es'])."', '".fixSQL($rs['ada_ac'])."', '".fixSQL($rs['ada_pemanas'])."', '".fixSQL($rs['ada_telepon'])."', '".fixSQL($rs['ada_tv'])."', '".fixSQL($rs['ada_emas'])."', '".fixSQL($rs['ada_laptop'])."', '".fixSQL($rs['ada_sepeda'])."', '".fixSQL($rs['ada_motor'])."', '".fixSQL($rs['ada_mobil'])."', '".fixSQL($rs['ada_perahu'])."', '".fixSQL($rs['ada_motor_tempel'])."', '".fixSQL($rs['ada_perahu_motor'])."', '".fixSQL($rs['ada_kapal'])."', '".fixSQL($rs['aset_tak_bergerak'])."', '".fixSQL($rs['luas_atb'])."', '".fixSQL($rs['rumah_lain'])."', '".fixSQL($rs['jumlah_sapi'])."', '".fixSQL($rs['jumlah_kerbau'])."', '".fixSQL($rs['jumlah_kuda'])."', '".fixSQL($rs['jumlah_babi'])."', '".fixSQL($rs['jumlah_kambing'])."', '".fixSQL($rs['sta_art_usaha'])."', '".fixSQL($rs['sta_kks'])."', '".fixSQL($rs['sta_kip'])."', '".fixSQL($rs['sta_kis'])."', '".fixSQL($rs['sta_bpjs_mandiri'])."', '".fixSQL($rs['sta_jamsostek'])."', '".fixSQL($rs['sta_asuransi'])."', '".fixSQL($rs['sta_pkh'])."', '".fixSQL($rs['sta_rastra'])."', '".fixSQL($rs['sta_kur'])."', '".fixSQL($rs['sta_keberadaan_RT'])."', '".fixSQL($rs['percentile'])."')".$strKoma;
							// `kode_wilayah`, `idbdt`, `nopesertapbdt`, `ruta6`, `nama_kepala_rumah_tangga`
							$strSQL = "SELECT id FROM tweb_rumahtangga WHERE idbdt='".fixSQL($rs['IDBDT'])."' AND kode_wilayah LIKE '".$kode_wilayah."%'";
							// echo $strSQL;
							$check = $this->db->query($strSQL);
							if($check->num_rows() > 0){
								// echo fixSQL($rs['idbdt'])."', '".fixSQL($rs['ruta6']) ." SUDAH TERDAFTAR \n";
							}else{
								$strSQLRTS .="('".fixSQL($kode_wilayah)."','".fixSQL($rs['IDBDT'])."', '".fixSQL($rs['Nama_KRT'])."')".$strKoma;
							}
							// $strSQLData .= "('".fixSQL($kode_wilayah)."','".fixSQL($rs['idbdt'])."', '".fixSQL($rs['ruta6'])."', '".fixSQL($rs['nopesertapbdt'])."', '".fixSQL($rs['kdprop'])."', '".fixSQL($rs['kdkab'])."', '".fixSQL($rs['kdkec'])."', '".fixSQL($rs['kddesa'])."', '".fixSQL($rs['alamat'])."', '".fixSQL($rs['nopesertapkh'])."', '".fixSQL($rs['nopesertakks2016'])."', '".fixSQL($rs['nopesertapbi'])."', '".fixSQL($rs['pesertakip'])."', '".fixSQL($rs['nama_sls'])."', '".fixSQL($rs['nama_krt'])."', '".fixSQL($rs['jumlah_art'])."', '".fixSQL($rs['jumlah_keluarga'])."', '".fixSQL($rs['sta_bangunan'])."', '".fixSQL($rs['sta_lahan'])."', '".fixSQL($rs['luas_lantai'])."', '".fixSQL($rs['lantai'])."', '".fixSQL($rs['dinding'])."', '".fixSQL($rs['kondisi_dinding'])."', '".fixSQL($rs['atap'])."', '".fixSQL($rs['kondisi_atap'])."', '".fixSQL($rs['jumlah_kamar'])."', '".fixSQL($rs['sumber_airminum'])."', '".fixSQL($rs['nomor_meter_air'])."', '".fixSQL($rs['cara_peroleh_airminum'])."', '".fixSQL($rs['sumber_penerangan'])."', '".fixSQL($rs['daya'])."', '".fixSQL($rs['nomor_pln'])."', '".fixSQL($rs['bb_masak'])."', '".fixSQL($rs['nomor_gas'])."', '".fixSQL($rs['fasbab'])."', '".fixSQL($rs['kloset'])."', '".fixSQL($rs['buang_tinja'])."', '".fixSQL($rs['ada_tabung_gas'])."', '".fixSQL($rs['ada_lemari_es'])."', '".fixSQL($rs['ada_ac'])."', '".fixSQL($rs['ada_pemanas'])."', '".fixSQL($rs['ada_telepon'])."', '".fixSQL($rs['ada_tv'])."', '".fixSQL($rs['ada_emas'])."', '".fixSQL($rs['ada_laptop'])."', '".fixSQL($rs['ada_sepeda'])."', '".fixSQL($rs['ada_motor'])."', '".fixSQL($rs['ada_mobil'])."', '".fixSQL($rs['ada_perahu'])."', '".fixSQL($rs['ada_motor_tempel'])."', '".fixSQL($rs['ada_perahu_motor'])."', '".fixSQL($rs['ada_kapal'])."', '".fixSQL($rs['aset_tak_bergerak'])."', '".fixSQL($rs['luas_atb'])."', '".fixSQL($rs['rumah_lain'])."', '".fixSQL($rs['jumlah_sapi'])."', '".fixSQL($rs['jumlah_kerbau'])."', '".fixSQL($rs['jumlah_kuda'])."', '".fixSQL($rs['jumlah_babi'])."', '".fixSQL($rs['jumlah_kambing'])."', '".fixSQL($rs['sta_art_usaha'])."', '".fixSQL($rs['sta_kks'])."', '".fixSQL($rs['sta_kip'])."', '".fixSQL($rs['sta_kis'])."', '".fixSQL($rs['sta_bpjs_mandiri'])."', '".fixSQL($rs['sta_jamsostek'])."', '".fixSQL($rs['sta_asuransi'])."', '".fixSQL($rs['sta_pkh'])."', '".fixSQL($rs['sta_rastra'])."', '".fixSQL($rs['sta_kur'])."', '".fixSQL($rs['sta_keberadaan_rt'])."', '".fixSQL($rs['percentile'])."'),\n";
							// if(!in_array($nama_rw, $rws[$kode_dusun])){
							// 	$rws[$kode_dusun][]=$nama_rw;
							// }
							// if(!in_array($nama_rt,$rts[$kode_dusun][$nama_rw])){
							// 	$rts[$kode_dusun][$nama_rw][] = $nama_rt;
							// }
							// $desa ." - ".$alamat." : nama dusun : ".$nama_dusun."\n";
							// $dusuns[trim($t[8])][] = trim($nama_dusun);
							// $rws[trim($t[8])][trim($nama_dusun)][] = trim($nama_rw);
							// $rts[trim($t[8])][trim($nama_dusun)][$nama_rw][] = trim($nama_rt);
		
							// echo var_dump($rs);
							$nomer++;
						}
					}else{
						echo "LHO KOK NOL?".$strSQL;
					}
					// $rws = array_unique($rws);
					// echo "<h1>RW</h1>";
					// echo var_dump($rws);
					// echo "<h1>RT</h1>";
					// echo var_dump($rts);
					$file_sql_bdt = FCPATH ."assets/uploads/skh2019/bdt_".$sumber."-".$kode_desa.".sql";
					$file_sql_rts = FCPATH ."assets/uploads/skh2019/rts_".$sumber."-".$kode_desa.".sql";
					// echo "<pre>".$file_sql_bdt."\n".$strSQLBDT."</pre>";
					if(is_file($file_sql_bdt)){
					}else{
						$newfile = fopen($file_sql_bdt,"w");
						fclose($newfile);
					}
					$fp = fopen($file_sql_bdt, "a") or exit("Unable to open file!");
					$strSQLBDT = trim($strSQLBDT);
					// $strSQLBDT = (substr($strSQLBDT,-1)==",") ? substr($strSQLBDT,0,-1).";":$strSQLBDT;
					fwrite($fp,$strSQLBDT);
				
					// echo "<pre>".$file_sql_rts."\n".$strSQLRTS."</pre>";
					if(is_file($file_sql_rts)){
					}else{
						$newfile = fopen($file_sql_rts,"w");
						fclose($newfile);
					}
					$fp = fopen($file_sql_rts, "a") or exit("Unable to open file!");
					$strSQLBDT = trim($strSQLBDT);
					// $strSQLBDT = (substr($strSQLRTS,-1)==",") ? substr($strSQLRTS,0,-1).";":$strSQLBDT;
					fwrite($fp,$strSQLRTS);
					$strSQL = "UPDATE tweb_wilayah SET proses=0 WHERE kode='".$kode_desa."'";
					if($this->db->query($strSQL)){
						echo $strSQL;
					}
	
				}
					
			}else{
				echo "Semua Desa sdh di proses";
			}
	
		}
		// echo var_dump($desas);
		

	}


	function import_art_bdt_2019(){
		$kode_base = "3311";
		$sumber = "SKH_ART";

		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=4 AND kode LIKE '".$kode_base."%' AND proses=0 LIMIT 1";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$desa = $query->result_array()[0];
				$kode_desa = $desa['kode'];

				$kd_prop = substr($desa['kode'],0,2);
				$kd_kab = substr($desa['kode'],2,2);
				$kd_kec = substr($desa['kode'],4,3);
				$kd_desa = substr($desa['kode'],7);

				$kode_wilayah = array();
				$strSQL = "SELECT DISTINCT(idbdt),kode_wilayah FROM bdt_rts GROUP BY idbdt";
				$query = $this->db->query($strSQL);
				if($query){
					foreach($query->result() as $rs){
						$output = preg_replace( '/[^0-9]/', '', $rs->kode_wilayah);
						$kode_wilayah[$rs->idbdt] = $output;
					}
				}

				$file_sql_rts = FCPATH ."assets/uploads/art_skh_2019/pdd_".$sumber."_".$kode_desa.".sql";
				if(is_file($file_sql_rts)){
				}else{
					$newfile = fopen($file_sql_rts,"w");
					fclose($newfile);
				}
				$fp = fopen($file_sql_rts, "a") or exit("Unable to open file!");
		
				$strSQL = "SELECT `NO`, `IDARTBDT`, `IDBDT`, `KECAMATAN`, `DESA`, `NoPesertaPKH`, `NoPesertaPBI`, `NoArtPBI`, `Nama`, `Alamat`, `SLS`, `JnsKel`, `TmpLahir`, `TglLahir`, `NIK`, `NoKK`, `Hub_KRT`, `NUK`, `Hubkel`, `Umur`, `Sta_kawin`, `Ada_akta_nikah`, `Ada_diKK`, `Ada_kartu_identitas`, `Ada_kks`, `Sta_hamil`, `Jenis_cacat`, `Penyakit_kronis`, `Partisipasi_sekolah`, `Pendidikan_tertinggi`, `Kelas_tertinggi`, `Ijazah_tertinggi`, `Sta_Bekerja`, `Jumlah_jamkerja`, `Lapangan_usaha`, `Status_pekerjaan`, `Percentile` FROM ".$sumber." WHERE DESA='".fixSQL($desa['nama'])."' ORDER BY `NO`";
				// echo $strSQL;
				$query = $this->db->query($strSQL);
				if($query){
					$strSQLART = "INSERT INTO bdt_idv(`periode_id`, `kode_wilayah`, `idartbdt`, `idbdt`, `nopesertapbdtart`,`kdprop`, `kdkab`, `kdkec`, `kddesa`, `nopesertapkh`,  `nopesertapbi`, `noartpbdt`,`noartpbi`, `nama`, `jnskel`, `tmplahir`, `tgllahir`, `hubkrt`, `nik`, `nokk`, `hub_krt`, `nuk`, `hubkel`, `umur`, `sta_kawin`, `ada_akta_nikah`, `ada_dikk`, `ada_kartu_identitas`, `sta_hamil`, `jenis_cacat`, `penyakit_kronis`, `partisipasi_sekolah`, `pendidikan_tertinggi`, `kelas_tertinggi`, `ijazah_tertinggi`, `sta_bekerja`, `jumlah_jamkerja`, `lapangan_usaha`, `status_pekerjaan`,`percentile`) VALUES \n";
					// fwrite($fp,$strSQLART);
					$sqlSave = $strSQLART;
		
					$nomer=1;
					$insert = 1;
					$numrows = $query->num_rows();
					if($numrows > 0){
						foreach ($query->result_array() as $rs){
							if(fmod($nomer,300)==0){
								$strSQLData = "(4, '".fixSQL($kode_wilayah[$rs['IDBDT']])."', '".fixSQL($rs['IDARTBDT'])."', '".fixSQL($rs['IDBDT'])."', '".fixSQL($rs['IDARTBDT'])."', '".fixSQL($kd_prop)."', '".fixSQL($kd_kab)."', '".fixSQL($kd_kec)."', '".fixSQL($kd_desa)."', '".fixSQL($rs['NoPesertaPKH'])."', '".fixSQL($rs['NoPesertaPBI'])."', '".fixSQL($rs['IDARTBDT'])."', '".fixSQL($rs['NoPesertaPBI'])."', '".fixSQL($rs['Nama'])."', '".fixSQL($rs['JnsKel'])."', '".fixSQL($rs['TmpLahir'])."', '".fixSQL($rs['TglLahir'])."', '".fixSQL($rs['Hub_KRT'])."', '".fixSQL($rs['NIK'])."', '".fixSQL($rs['NoKK'])."', '".fixSQL($rs['Hub_KRT'])."', '".fixSQL($rs['NUK'])."', '".fixSQL($rs['Hubkel'])."', '".fixSQL($rs['Umur'])."', '".fixSQL($rs['Sta_kawin'])."', '".fixSQL($rs['Ada_akta_nikah'])."', '".fixSQL($rs['Ada_diKK'])."', '".fixSQL($rs['Ada_kartu_identitas'])."', '".fixSQL($rs['Sta_hamil'])."', '".fixSQL($rs['Jenis_cacat'])."', '".fixSQL($rs['Penyakit_kronis'])."', '".fixSQL($rs['Partisipasi_sekolah'])."', '".fixSQL($rs['Pendidikan_tertinggi'])."', '".fixSQL($rs['Kelas_tertinggi'])."', '".fixSQL($rs['Ijazah_tertinggi'])."', '".fixSQL($rs['Sta_Bekerja'])."', '".fixSQL($rs['Jumlah_jamkerja'])."', '".fixSQL($rs['Lapangan_usaha'])."', '".fixSQL($rs['Status_pekerjaan'])."', '".fixSQL($rs['Percentile'])."');\n";
								$sqlSave .=$strSQLData;
								$sqlSave .= $strSQLART;
								// fwrite($fp,$strSQLData);
								// fwrite($fp,$strSQLART);
								$insert++;
							}else{
								$strSQLData = "(4, '".fixSQL($kode_wilayah[$rs['IDBDT']])."', '".fixSQL($rs['IDARTBDT'])."', '".fixSQL($rs['IDBDT'])."', '".fixSQL($rs['IDARTBDT'])."', '".fixSQL($kd_prop)."', '".fixSQL($kd_kab)."', '".fixSQL($kd_kec)."', '".fixSQL($kd_desa)."', '".fixSQL($rs['NoPesertaPKH'])."', '".fixSQL($rs['NoPesertaPBI'])."', '".fixSQL($rs['IDARTBDT'])."', '".fixSQL($rs['NoPesertaPBI'])."', '".fixSQL($rs['Nama'])."', '".fixSQL($rs['JnsKel'])."', '".fixSQL($rs['TmpLahir'])."', '".fixSQL($rs['TglLahir'])."', '".fixSQL($rs['Hub_KRT'])."', '".fixSQL($rs['NIK'])."', '".fixSQL($rs['NoKK'])."', '".fixSQL($rs['Hub_KRT'])."', '".fixSQL($rs['NUK'])."', '".fixSQL($rs['Hubkel'])."', '".fixSQL($rs['Umur'])."', '".fixSQL($rs['Sta_kawin'])."', '".fixSQL($rs['Ada_akta_nikah'])."', '".fixSQL($rs['Ada_diKK'])."', '".fixSQL($rs['Ada_kartu_identitas'])."', '".fixSQL($rs['Sta_hamil'])."', '".fixSQL($rs['Jenis_cacat'])."', '".fixSQL($rs['Penyakit_kronis'])."', '".fixSQL($rs['Partisipasi_sekolah'])."', '".fixSQL($rs['Pendidikan_tertinggi'])."', '".fixSQL($rs['Kelas_tertinggi'])."', '".fixSQL($rs['Ijazah_tertinggi'])."', '".fixSQL($rs['Sta_Bekerja'])."', '".fixSQL($rs['Jumlah_jamkerja'])."', '".fixSQL($rs['Lapangan_usaha'])."', '".fixSQL($rs['Status_pekerjaan'])."', '".fixSQL($rs['Percentile'])."'),\n";
								$sqlSave .=$strSQLData;
								// fwrite($fp,$strSQLData);
							}
							$nomer++;
						}
					}
		
					$sqlSave = trim($sqlSave);
					$sqlSave = (substr($sqlSave,-1)==",") ? substr($sqlSave,0,-1).";":$sqlSave;
					fwrite($fp,$sqlSave);
		
					$strSQL = "UPDATE tweb_wilayah SET proses=1 WHERE kode=".$desa['kode'];
					$this->db->query($strSQL);
		
					echo date("Y-m-d H:i:s")." Ada $nomer data dlm ".$insert." Query\n";
		
				}
						


			}
		}


		$file_counter = FCPATH ."assets/uploads/art_bdt/pdd_nomer.txt";
		if(is_file($file_counter)){
		}else{
			$newfile = fopen($file_counter,"w");
			fclose($newfile);
		}
		$fp = fopen($file_counter, "a") or exit("Unable to open file!");

		$counter = (string)file_get_contents($file_counter);
		$counter = trim(preg_replace('/\s+/', ' ', $counter));
		$counter = ($counter=='')? 0:$counter;
		$limit = 20000;
		$offset = $counter * $limit;

		// echo var_dump($kode_wilayah);

	}

	function migrasi_bdt_art(){
		$sumber = "bdt_idv_2018";
		$file_counter = FCPATH ."assets/uploads/art_bdt/pdd_nomer.txt";
		if(is_file($file_counter)){
		}else{
			$newfile = fopen($file_counter,"w");
			fclose($newfile);
		}
		$fp = fopen($file_counter, "a") or exit("Unable to open file!");

		$counter = (string)file_get_contents($file_counter);
		$counter = trim(preg_replace('/\s+/', ' ', $counter));
		$counter = ($counter=='')? 0:$counter;
		$limit = 20000;
		$offset = $counter * $limit;

		$kode_wilayah = array();
		$strSQL = "SELECT DISTINCT(idbdt),kode_wilayah FROM bdt_rts GROUP BY idbdt";
		$query = $this->db->query($strSQL);
		if($query){
			foreach($query->result() as $rs){
				$output = preg_replace( '/[^0-9]/', '', $rs->kode_wilayah);
				$kode_wilayah[$rs->idbdt] = $output;
			}
		}
		// echo var_dump($kode_wilayah);

		$file_sql_rts = FCPATH ."assets/uploads/art_bdt/pdd_".$sumber."_".$counter.".sql";
		if(is_file($file_sql_rts)){
		}else{
			$newfile = fopen($file_sql_rts,"w");
			fclose($newfile);
		}
		$fp = fopen($file_sql_rts, "a") or exit("Unable to open file!");

		$strSQL = "SELECT `periode_id`, `kode_wilayah`, `idartbdt`, `idbdt`, `ruta6`, `nopesertapbdt`, `nopesertapbdtart`, `nopbdtkemsos`, `noartpbdtkemsos`, `vector1`, `vector2`, `vector3`, `vector4`, `kdgabungan4`, `kdprop`, `kdkab`, `kdkec`, `kddesa`, `nopesertapkh`, `nopesertakks2016`, `nopesertapbi`, `noartpkh`, `noartpbdt`, `noartkks2016`, `noartpbi`, `nama`, `jnskel`, `tmplahir`, `tgllahir`, `hubkrt`, `nik`, `nokk`, `hub_krt`, `nuk`, `hubkel`, `umur`, `sta_kawin`, `ada_akta_nikah`, `ada_dikk`, `ada_kartu_identitas`, `sta_hamil`, `jenis_cacat`, `penyakit_kronis`, `partisipasi_sekolah`, `pendidikan_tertinggi`, `kelas_tertinggi`, `ijazah_tertinggi`, `sta_bekerja`, `jumlah_jamkerja`, `lapangan_usaha`, `status_pekerjaan`, `sta_keberadaan_art`, `sta_kepesertaan_pbi`, `ada_kks`, `ada_pbi`, `ada_kip`, `ada_pkh`, `ada_rastra`, `anak_diluar_rt`, `namagadis_ibukandung`, `sta_keberadaan_kks`, `initdata`, `lastupdatedata`, `kodewilayah`, `idver`, `rid_rumahtangga`, `rid_individu`, `lapangan_usahaart`, `jumlah_pekerja`, `lokasi_usaha`, `omset_usaha` FROM ".$sumber." LIMIT ".$offset.",".$limit;
		echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			$strSQLART = "INSERT INTO bdt_idv(`periode_id`, `kode_wilayah`, `idartbdt`, `idbdt`, `ruta6`, `nopesertapbdt`, `nopesertapbdtart`, `nopbdtkemsos`, `noartpbdtkemsos`, `vector1`, `vector2`, `vector3`, `vector4`, `kdgabungan4`, `kdprop`, `kdkab`, `kdkec`, `kddesa`, `nopesertapkh`, `nopesertakks2016`, `nopesertapbi`, `noartpkh`, `noartpbdt`, `noartkks2016`, `noartpbi`, `nama`, `jnskel`, `tmplahir`, `tgllahir`, `hubkrt`, `nik`, `nokk`, `hub_krt`, `nuk`, `hubkel`, `umur`, `sta_kawin`, `ada_akta_nikah`, `ada_dikk`, `ada_kartu_identitas`, `sta_hamil`, `jenis_cacat`, `penyakit_kronis`, `partisipasi_sekolah`, `pendidikan_tertinggi`, `kelas_tertinggi`, `ijazah_tertinggi`, `sta_bekerja`, `jumlah_jamkerja`, `lapangan_usaha`, `status_pekerjaan`, `sta_keberadaan_art`, `sta_kepesertaan_pbi`, `ada_kks`, `ada_pbi`, `ada_kip`, `ada_pkh`, `ada_rastra`, `anak_diluar_rt`, `namagadis_ibukandung`, `sta_keberadaan_kks`, `initdata`, `lastupdatedata`, `kodewilayah`, `idver`, `rid_rumahtangga`, `rid_individu`, `lapangan_usahaart`, `jumlah_pekerja`, `lokasi_usaha`, `omset_usaha`) VALUES \n";
			// fwrite($fp,$strSQLART);
			$sqlSave = $strSQLART;

			$nomer=1;
			$insert = 1;
			$numrows = $query->num_rows();
			if($numrows > 0){
				foreach ($query->result_array() as $rs){
					if(fmod($nomer,300)==0){
						$strSQLData = "('".fixSQL($rs['periode_id'])."', '".fixSQL($kode_wilayah[$rs['idbdt']])."', '".fixSQL($rs['idartbdt'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['ruta6'])."', '".fixSQL($rs['nopesertapbdt'])."', '".fixSQL($rs['nopesertapbdtart'])."', '".fixSQL($rs['nopbdtkemsos'])."', '".fixSQL($rs['noartpbdtkemsos'])."', '".fixSQL($rs['vector1'])."', '".fixSQL($rs['vector2'])."', '".fixSQL($rs['vector3'])."', '".fixSQL($rs['vector4'])."', '".fixSQL($rs['kdgabungan4'])."', '".fixSQL($rs['kdprop'])."', '".fixSQL($rs['kdkab'])."', '".fixSQL($rs['kdkec'])."', '".fixSQL($rs['kddesa'])."', '".fixSQL($rs['nopesertapkh'])."', '".fixSQL($rs['nopesertakks2016'])."', '".fixSQL($rs['nopesertapbi'])."', '".fixSQL($rs['noartpkh'])."', '".fixSQL($rs['noartpbdt'])."', '".fixSQL($rs['noartkks2016'])."', '".fixSQL($rs['noartpbi'])."', '".fixSQL($rs['nama'])."', '".fixSQL($rs['jnskel'])."', '".fixSQL($rs['tmplahir'])."', '".fixSQL($rs['tgllahir'])."', '".fixSQL($rs['hubkrt'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['nokk'])."', '".fixSQL($rs['hub_krt'])."', '".fixSQL($rs['nuk'])."', '".fixSQL($rs['hubkel'])."', '".fixSQL($rs['umur'])."', '".fixSQL($rs['sta_kawin'])."', '".fixSQL($rs['ada_akta_nikah'])."', '".fixSQL($rs['ada_dikk'])."', '".fixSQL($rs['ada_kartu_identitas'])."', '".fixSQL($rs['sta_hamil'])."', '".fixSQL($rs['jenis_cacat'])."', '".fixSQL($rs['penyakit_kronis'])."', '".fixSQL($rs['partisipasi_sekolah'])."', '".fixSQL($rs['pendidikan_tertinggi'])."', '".fixSQL($rs['kelas_tertinggi'])."', '".fixSQL($rs['ijazah_tertinggi'])."', '".fixSQL($rs['sta_bekerja'])."', '".fixSQL($rs['jumlah_jamkerja'])."', '".fixSQL($rs['lapangan_usaha'])."', '".fixSQL($rs['status_pekerjaan'])."', '".fixSQL($rs['sta_keberadaan_art'])."', '".fixSQL($rs['sta_kepesertaan_pbi'])."', '".fixSQL($rs['ada_kks'])."', '".fixSQL($rs['ada_pbi'])."', '".fixSQL($rs['ada_kip'])."', '".fixSQL($rs['ada_pkh'])."', '".fixSQL($rs['ada_rastra'])."', '".fixSQL($rs['anak_diluar_rt'])."', '".fixSQL($rs['namagadis_ibukandung'])."', '".fixSQL($rs['sta_keberadaan_kks'])."', '".fixSQL($rs['initdata'])."', '".fixSQL($rs['lastupdatedata'])."', '".fixSQL($rs['kodewilayah'])."', '".fixSQL($rs['idver'])."', '".fixSQL($rs['rid_rumahtangga'])."', '".fixSQL($rs['rid_individu'])."', '".fixSQL($rs['lapangan_usahaart'])."', '".fixSQL($rs['jumlah_pekerja'])."', '".fixSQL($rs['lokasi_usaha'])."', '".fixSQL($rs['omset_usaha'])."');\n";
						$sqlSave .=$strSQLData;
						$sqlSave .= $strSQLART;
						// fwrite($fp,$strSQLData);
						// fwrite($fp,$strSQLART);
						$insert++;
					}else{
						$strSQLData = "('".fixSQL($rs['periode_id'])."', '".fixSQL($kode_wilayah[$rs['idbdt']])."', '".fixSQL($rs['idartbdt'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['ruta6'])."', '".fixSQL($rs['nopesertapbdt'])."', '".fixSQL($rs['nopesertapbdtart'])."', '".fixSQL($rs['nopbdtkemsos'])."', '".fixSQL($rs['noartpbdtkemsos'])."', '".fixSQL($rs['vector1'])."', '".fixSQL($rs['vector2'])."', '".fixSQL($rs['vector3'])."', '".fixSQL($rs['vector4'])."', '".fixSQL($rs['kdgabungan4'])."', '".fixSQL($rs['kdprop'])."', '".fixSQL($rs['kdkab'])."', '".fixSQL($rs['kdkec'])."', '".fixSQL($rs['kddesa'])."', '".fixSQL($rs['nopesertapkh'])."', '".fixSQL($rs['nopesertakks2016'])."', '".fixSQL($rs['nopesertapbi'])."', '".fixSQL($rs['noartpkh'])."', '".fixSQL($rs['noartpbdt'])."', '".fixSQL($rs['noartkks2016'])."', '".fixSQL($rs['noartpbi'])."', '".fixSQL($rs['nama'])."', '".fixSQL($rs['jnskel'])."', '".fixSQL($rs['tmplahir'])."', '".fixSQL($rs['tgllahir'])."', '".fixSQL($rs['hubkrt'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['nokk'])."', '".fixSQL($rs['hub_krt'])."', '".fixSQL($rs['nuk'])."', '".fixSQL($rs['hubkel'])."', '".fixSQL($rs['umur'])."', '".fixSQL($rs['sta_kawin'])."', '".fixSQL($rs['ada_akta_nikah'])."', '".fixSQL($rs['ada_dikk'])."', '".fixSQL($rs['ada_kartu_identitas'])."', '".fixSQL($rs['sta_hamil'])."', '".fixSQL($rs['jenis_cacat'])."', '".fixSQL($rs['penyakit_kronis'])."', '".fixSQL($rs['partisipasi_sekolah'])."', '".fixSQL($rs['pendidikan_tertinggi'])."', '".fixSQL($rs['kelas_tertinggi'])."', '".fixSQL($rs['ijazah_tertinggi'])."', '".fixSQL($rs['sta_bekerja'])."', '".fixSQL($rs['jumlah_jamkerja'])."', '".fixSQL($rs['lapangan_usaha'])."', '".fixSQL($rs['status_pekerjaan'])."', '".fixSQL($rs['sta_keberadaan_art'])."', '".fixSQL($rs['sta_kepesertaan_pbi'])."', '".fixSQL($rs['ada_kks'])."', '".fixSQL($rs['ada_pbi'])."', '".fixSQL($rs['ada_kip'])."', '".fixSQL($rs['ada_pkh'])."', '".fixSQL($rs['ada_rastra'])."', '".fixSQL($rs['anak_diluar_rt'])."', '".fixSQL($rs['namagadis_ibukandung'])."', '".fixSQL($rs['sta_keberadaan_kks'])."', '".fixSQL($rs['initdata'])."', '".fixSQL($rs['lastupdatedata'])."', '".fixSQL($rs['kodewilayah'])."', '".fixSQL($rs['idver'])."', '".fixSQL($rs['rid_rumahtangga'])."', '".fixSQL($rs['rid_individu'])."', '".fixSQL($rs['lapangan_usahaart'])."', '".fixSQL($rs['jumlah_pekerja'])."', '".fixSQL($rs['lokasi_usaha'])."', '".fixSQL($rs['omset_usaha'])."'),\n";
						$sqlSave .=$strSQLData;
						// fwrite($fp,$strSQLData);
					}
					$nomer++;
				}
			}

			$sqlSave = trim($sqlSave);
			$sqlSave = (substr($sqlSave,-1)==",") ? substr($sqlSave,0,-1).";":$sqlSave;
			fwrite($fp,$sqlSave);
			$new_number = (int)$counter+1;
			file_put_contents($file_counter,$new_number);

			// $strSQL = "UPDATE tweb_wilayah SET proses=1 WHERE kode=".$desa['kode'];
			// $this->db->query($strSQL);

			echo date("Y-m-d H:i:s")." Ada $nomer data dlm ".$insert." Query\n";

		}
	}

	function generate_user_opd(){
		$file_csv = FCPATH."/assets/uploads/e-rpjmd.csv";
		$file = fopen($file_csv, 'r');
		$strSQL = "INSERT INTO tweb_users(`situs_id`, `nama`, `userid`, `passwt`, `tingkat`, `status`, `email`, `updated_by`, `created_by`, `wilayah`) VALUES \n";
		while (($line = fgetcsv($file)) !== FALSE) {
		  //$line is an array of the csv elements
		  $strSQL .= "(886,'".fixSQL($line[1])."','".fixSQL($line[0])."','".password_hash($line['2'],PASSWORD_DEFAULT)."',2,1,'".fixNamaUrl($line[1])."@sukoharjokab.go.id',886,886,'3311'),\n";
		//   print_r($line);
		}
		echo $strSQL;
		fclose($file);
	}

	function import_bdt_art(){
		$sumber = "bdt_idv";

		$file_counter = FCPATH ."assets/uploads/skh/pdd_nomer.txt";
		if(is_file($file_counter)){
		}else{
			$newfile = fopen($file_counter,"w");
			fclose($newfile);
		}
		$fp = fopen($file_counter, "a") or exit("Unable to open file!");

		$counter = (string)file_get_contents($file_counter);
		$counter = trim(preg_replace('/\s+/', ' ', $counter));
		$counter = ($counter=='')? 0:$counter;
		$limit = 50000;
		$offset = $counter * $limit;

		$kode_wilayah = array();
		$strSQL = "SELECT DISTINCT(idbdt),kode_wilayah FROM bdt_rts WHERE (`kode_wilayah` LIKE '3311%') AND (`periode_id`=4)GROUP BY idbdt";
		$query = $this->db->query($strSQL);
		if($query){
			foreach($query->result() as $rs){
				$output = preg_replace( '/[^0-9]/', '', $rs->kode_wilayah);
				$kode_wilayah[$rs->idbdt] = $output;
			}
		}
		// echo var_dump($kode_wilayah);

		$file_sql_rts = FCPATH ."assets/uploads/skh/penduduk/".$sumber."_".$counter.".sql";
		if(is_file($file_sql_rts)){
		}else{
			$newfile = fopen($file_sql_rts,"w");
			fclose($newfile);
		}
		$fp = fopen($file_sql_rts, "a") or exit("Unable to open file!");

		$strSQL = "SELECT a.* 
			FROM ".$sumber." a
			WHERE (a.`kode_wilayah` LIKE '3311%') 
			AND a.periode_id=4 
			AND a.idartbdt NOT IN (SELECT idartbdt b FROM tweb_penduduk b WHERE (b.`kode_wilayah` LIKE '3311%')) 
			LIMIT ".$offset.",".$limit;
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			// $strSQLBDT = "INSERT INTO tweb_keluarga(`kode_wilayah`,) VALUES\n";
			$strSQLART = "INSERT INTO tweb_penduduk(
				`kode_wilayah`, `idartbdt`, `idbdt`, `ruta6`, 
				`nopesertapbdt`, `nopesertapbdtart`, `nik`, 
				`nama`, `kk_nomor`, `rt_hubungan`, 
				`kk_hubungan`, `tlahir`, 
				`dtlahir`, `jnskel`) VALUES \n";
			// fwrite($fp,$strSQLART);
			$sqlSave = $strSQLART;

			$nomer=1;
			$insert = 1;
			$numrows = $query->num_rows();
			if($numrows > 0){
				foreach ($query->result_array() as $rs){
					if(fmod($nomer,500)==0){
						$strSQLData = "(
							'".fixSQL($kode_wilayah[$rs['idbdt']])."', '".fixSQL($rs['idartbdt'])."', '".fixSQL($rs['idbdt'])."', 
							'".fixSQL($rs['ruta6'])."', '".fixSQL($rs['nopesertapbdt'])."', '".fixSQL($rs['nopesertapbdtart'])."', 
							'".fixSQL($rs['nik'])."', '".fixSQL($rs['nama'])."', '".fixSQL($rs['nokk'])."', 
							'".fixSQL($rs['hub_krt'])."', '".fixSQL($rs['hubkel'])."', '".fixSQL($rs['tmplahir'])."', 
							'".fixSQL($rs['tgllahir'])."', '".fixSQL($rs['jnskel'])."');\n";
						$sqlSave .=$strSQLData;
						$sqlSave .= $strSQLART;
						// fwrite($fp,$strSQLData);
						// fwrite($fp,$strSQLART);
						$insert++;
					}else{
						$strSQLData = "('".fixSQL($kode_wilayah[$rs['idbdt']])."', '".fixSQL($rs['idartbdt'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['ruta6'])."', '".fixSQL($rs['nopesertapbdt'])."', '".fixSQL($rs['nopesertapbdtart'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['nama'])."', '".fixSQL($rs['nokk'])."', '".fixSQL($rs['hub_krt'])."', '".fixSQL($rs['hubkel'])."', '".fixSQL($rs['tmplahir'])."', '".fixSQL($rs['tgllahir'])."', '".fixSQL($rs['jnskel'])."'),\n";
						$sqlSave .=$strSQLData;
						// fwrite($fp,$strSQLData);
					}
					$nomer++;
				}
			}

			$sqlSave = trim($sqlSave);
			$sqlSave = (substr($sqlSave,-1)==",") ? substr($sqlSave,0,-1).";":$sqlSave;
			fwrite($fp,$sqlSave);
			$new_number = (int)$counter+1;
			file_put_contents($file_counter,$new_number);

			// $strSQL = "UPDATE tweb_wilayah SET proses=1 WHERE kode=".$desa['kode'];
			// $this->db->query($strSQL);

			echo date("Y-m-d H:i:s")." Ada $nomer data dlm ".$insert."6 Query\n";
		}
	}



	function generate_kk(){

		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=4 AND kode LIKE '3311%' AND proses=1 LIMIT 1 \n";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$desa = $query->result_array()[0];

				$file_sql_rts = FCPATH ."assets/uploads/skh/kk/".$desa['kode'].".sql";
				if(is_file($file_sql_rts)){
				}else{
					$newfile = fopen($file_sql_rts,"w");
					fclose($newfile);
				}
				$fp = fopen($file_sql_rts, "a") or exit("Unable to open file!");

				$strSQL = "SELECT DISTINCT(p.`kk_nomor`),p.`idbdt`,COUNT(p.`id`) as numakk,p.`kode_wilayah`,
					(SELECT nama FROM tweb_penduduk WHERE idbdt=p.idbdt AND kk_hubungan=1 LIMIT 1) as kepala,
					(SELECT nik FROM tweb_penduduk WHERE idbdt=p.idbdt AND kk_hubungan=1 LIMIT 1) as nik
				 FROM `tweb_penduduk` p WHERE p.`kode_wilayah` LIKE '".fixSQL($desa['kode'])."%' GROUP BY p.`kk_nomor` \n";
				// echo $strSQL;

				$query = $this->db->query($strSQL);
				if($query){
					$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`, `idbdt`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES \n";
					// fwrite($fp,$strSQLART);
					$sqlSave = $strSQLKK;

					$nomer=1;
					$insert = 1;
					$numrows = $query->num_rows();
					if($numrows > 0){
						foreach ($query->result_array() as $rs){
							if(fmod($nomer,300)==0){
								$strSQLData = "('".fixSQL($rs['kode_wilayah'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['kk_nomor'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['kepala'])."', '".date('Y-m-d H:i:s')."', '0', '".date('Y-m-d H:i:s')."', '0');\n";
								$sqlSave .=$strSQLData;
								$sqlSave .= $strSQLKK;
								$insert++;
							}else{
								$strSQLData = "('".fixSQL($rs['kode_wilayah'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['kk_nomor'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['kepala'])."', '".date('Y-m-d H:i:s')."', '0', '".date('Y-m-d H:i:s')."', '0'),\n";
								$sqlSave .=$strSQLData;
								// fwrite($fp,$strSQLData);
							}
							$nomer++;
						}
					}

					$sqlSave = trim($sqlSave);
					$sqlSave = (substr($sqlSave,-1)==",") ? substr($sqlSave,0,-1).";":$sqlSave;
					fwrite($fp,$sqlSave);

					$strSQL = "UPDATE tweb_wilayah SET proses=0 WHERE kode=".$desa['kode'];
					$this->db->query($strSQL);

					echo date("Y-m-d H:i:s")." Ada $nomer data dlm ".$insert."6 Query\n";
				}
				
			}
		}else{
			echo $query->error();
		}
	}

	function generate_penduduk(){

		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=4 AND kode LIKE '3311%' AND proses=1 LIMIT 1 \n";
		// echo $strSQL;
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				$desa = $query->result_array()[0];

				$file_sql_rts = FCPATH ."assets/uploads/skh/penduduk/".$desa['kode'].".sql";
				if(is_file($file_sql_rts)){
				}else{
					$newfile = fopen($file_sql_rts,"w");
					fclose($newfile);
				}
				$fp = fopen($file_sql_rts, "a") or exit("Unable to open file!");

				$strSQL = "SELECT DISTINCT(p.kk_nomor),p.idbdt,COUNT(p.id),p.kode_wilayah,
				(SELECT nama FROM tweb_penduduk WHERE idbdt=p.idbdt AND kk_hubungan=1 LIMIT 1) as kepala,
				(SELECT nik FROM tweb_penduduk WHERE idbdt=p.idbdt AND kk_hubungan=1 LIMIT 1) as nik
				 FROM `tweb_penduduk` p WHERE kode_wilayah LIKE '".fixSQL($desa['kode'])."%' GROUP BY idbdt \n";
				// echo $strSQL;
				$query = $this->db->query($strSQL);
				if($query){
					$strSQLKK = "INSERT INTO tweb_keluarga(`kode_wilayah`, `idbdt`, `kk_nomor`, `kk_nik`, `kk_nama_kepala`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES \n";
					// fwrite($fp,$strSQLART);
					$sqlSave = $strSQLKK;

					$nomer=1;
					$insert = 1;
					$numrows = $query->num_rows();
					if($numrows > 0){
						foreach ($query->result_array() as $rs){
							if(fmod($nomer,300)==0){
								$strSQLData = "('".fixSQL($rs['kode_wilayah'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['kk_nomor'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['kepala'])."', '".date('Y-m-d H:i:s')."', '0', '".date('Y-m-d H:i:s')."', '0');\n";
								$sqlSave .=$strSQLData;
								$sqlSave .= $strSQLKK;
								// fwrite($fp,$strSQLData);
								// fwrite($fp,$strSQLART);
								$insert++;
							}else{
								$strSQLData = "('".fixSQL($rs['kode_wilayah'])."', '".fixSQL($rs['idbdt'])."', '".fixSQL($rs['kk_nomor'])."', '".fixSQL($rs['nik'])."', '".fixSQL($rs['kepala'])."', '".date('Y-m-d H:i:s')."', '0', '".date('Y-m-d H:i:s')."', '0'),\n";
								$sqlSave .=$strSQLData;
								// fwrite($fp,$strSQLData);
							}
							$nomer++;
						}
					}

					$sqlSave = trim($sqlSave);
					$sqlSave = (substr($sqlSave,-1)==",") ? substr($sqlSave,0,-1).";":$sqlSave;
					fwrite($fp,$sqlSave);

					$strSQL = "UPDATE tweb_wilayah SET proses=0 WHERE kode=".$desa['kode'];
					$this->db->query($strSQL);

					echo date("Y-m-d H:i:s")." Ada $nomer data dlm ".$insert."6 Query\n";
				}
				
			}
		}else{
			echo $query->error();
		}
	}	

	function generate_situs(){
		$strSQL = "SELECT id,nama,userid,wilayah FROM tweb_users WHERE id > 2";
		$query = $this->db->query($strSQL);
		if($query){
			// $strSQL = "INSERT INTO tweb_situs(`id`,`nama`,`ndesc`,`base_url`,`base_domain`,`kode_base`) VALUES \n";
			// echo $strSQL;
			foreach($query->result() as $rs){
				$domain = $rs->userid.".pbdt.web.id";
				$nama = str_replace('Petugas ','',$rs->nama);
				$nama = "SIK ".str_replace('Carik ','',$nama);
				// $strSQL = "('".fixSQL($rs->id)."','".fixSQL($nama)."','','".fixSQL($domain)."','".fixSQL($domain)."','".fixSQL($rs->wilayah)."'), \n";
				// $strSQL = $domain.".	1	IN	CNAME	pbdt.web.id.\n";
				$strSQL = $domain ." ";
				echo $strSQL;
			}
		}

	}

	function generate_situs_config(){
		$param = array();
		$strSQL = "SELECT label,nama FROM tweb_config_param WHERE 1 ORDER BY label";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result() as $rs){
					$param[$rs->nama] = $rs->label;
				}
			}
		}


		$strSQL = "SELECT id as situs_id,nama,base_url,kode_base FROM tweb_situs WHERE id between 2 and 884";
		$query = $this->db->query($strSQL);
		if($query){
			foreach($query->result() as $rs){
				// echo $rs->id." -> ".$rs->base_url."\n";
				$nama = str_replace('SIK ','',$rs->nama);
				$strSQL = "INSERT INTO tweb_config(`situs_id`,`label`,`data`) VALUES \n
				(".$rs->situs_id.",'title', '".fixSQL($nama)."'),
				(".$rs->situs_id.", 'limit_tampil', '25'),
				(".$rs->situs_id.", 'lat', '-7.8138024'),
				(".$rs->situs_id.", 'lng', '110.9223669'),
				(".$rs->situs_id.", 'alamat_kantor', 'Balai Desa ".fixSQL($nama)."'),
				(".$rs->situs_id.", 'telp', '+62 0271 123 456'),
				(".$rs->situs_id.", 'facebook', ''),
				(".$rs->situs_id.", 'kode_wilayah', '".fixSQL($rs->kode_base)."'),
				(".$rs->situs_id.", 'owner', '".fixSQL($nama)."'),
				(".$rs->situs_id.", 'app_title', 'Pemutakhiran Basis Data Terpadu ".fixSQL($nama)."'); \n";
				echo $strSQL;
			}
		}
	}

	function import_geojson(){
		$file_json =  FCPATH."assets/uploads/PETA_GeoJSON/wonogiri_kecamatan.geojson";
		$content = file_get_contents($file_json);
		$strData = json_decode($content,true);

		// echo var_dump($strData);
		foreach($strData['features'] as $item ){
			// echo print_r($item);
			echo "
			<hr />
			<ol>";
			foreach($item as $key=>$rs){
				// echo print_r($rs);
				$kode_kec = $rs['properties']['N1D2012'];
				echo "<li>".$key."</li>";

				if($key == 'geometry'){
					// $data = print_r($rs);
					echo "<pre>".$kode_kec;
					echo json_encode($rs);
					echo "</pre>";
				}
				// if(is_array($rs)){
				// 	echo "<li><ol>";
				// 	foreach($rs as $k=>$r){
				// 		echo "<li>".$k." : ".var_dump($r)."</li>";
				// 	}
				// 	echo "</ol></li>";
				// }
			}
			echo "</ol>";
		}
	}

	function list_indikator($varRTS){
		$this->load->model('apibdt_model');
		if($varRTS){
			$indikator = $this->apibdt_model->bdt_indikator($varRTS);
			// $opsi = $this->apibdt_model->bdt_opsi($varRTS);
			echo "<table>
			<thead><tr><th>#</th><th>Indikator</th><th>Opsi</th></tr></thead>
			<tbody>";
			foreach ($indikator as $key => $value) {
				# code...
				echo "<tr style=\"vertical-align:top;\">
					<td>".$value['nourut']."</td>
					<td>".$value['label']."</td>
					<td>".$value['jenis']."</td>
					</tr>";
				if($value['jenis']=='pilihan'){
					foreach ($value['opsi'] as $k => $o) {
						echo "<tr><td></td>
						<td></td>
						<td>".$o['nama'].". ".$o['label']." </td>
						</tr>";
					}
				}
			}
			echo "</tbody>
			</table>";
		}
	}
}
