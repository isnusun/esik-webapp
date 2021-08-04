<?php
/*
 * Ajax.php
 * 
 * Copyright 2016 Isnu Suntoro <isnusun@isnusun-X450LCP>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public function index(){
    $this->load->view('header');
    $this->load->view('api_indeks');
    $this->load->view('footer');
  }
  
  function data_wilayah(){
		$file_csv = FCPATH ."assets/uploads/WONOGORI_KRT.wilayah.csv";

		$file_sql = FCPATH ."assets/uploads/Wilayah_".time().".sql";
		
		if(is_file($file_sql)){
		}else{
			$newfile = fopen($file_sql,"w");
			fclose($newfile);
		}
		$fp = fopen($file_sql, "a") or exit("Unable to open file!");

		$file = fopen($file_csv, 'r');
		$prop = array(
			'kode'=>'33',
			'tingkat'=>1,
			'nama'=>'Jawa Tengah'
		);
		$kab = array(
			'kode'=>'3312',
			'tingkat'=>2,
			'nama'=>'Wonogiri');
		$kec = array();
		$desa = array();
		$dusuns = array();
		$rws = array();
		$rts = array();

		$i=0;
		while (($line = fgetcsv($file,10000,',','"')) !== FALSE) {
			if($i > 0){
				$kec[substr($line[0],0,7)] = array(
					'kode'=>substr($line[0],0,7),
					'tingkat'=>3,
					'nama'=>$line[3]);
				$desa[trim($line[0])] = array(
					'kode'=>$line[0],
					'tingkat'=>4,
					'nama'=>$line[4]);
				// echo "\n ASLI: ".$line[5];
				$alamat = extract_alamat($line[5]);
				
				$RT = "00";
				$RW = "00";
				$DUSUN = $line[4];
				$pos_rt = false;
				$pos_rw = false;
				$pos_dusun = false;

				foreach($alamat as $key=>$rs){
					$rs = "*".$rs."*";
					if(strtoupper(trim($rs)) == "*RT*"){
						$pos_rt = $key;
						$pos_rtne = (int)$key+1;
						$RT = substr(str_pad(trim($alamat[$pos_rtne]),2,"0",STR_PAD_LEFT),-2);
					}
					if(strtoupper(trim($rs)) == "*RW*"){
						$pos_rw = $key;
						$pos_rwne = (int)$key+1;
						if(array_key_exists($pos_rwne,$alamat)){
							$RW = substr(str_pad(trim($alamat[$pos_rwne]),2,"0",STR_PAD_LEFT),-2);
						}
					}
					if(trim($rs) == "*DUSUN*"){
						$pos_dusunne = (int)$key+1;
					}
				}
				// echo "PD ". $i;
				if($pos_dusunne > 0){
					$next_pos = ($pos_rt > $pos_rw) ? $pos_rw:$pos_rt;
					// echo "\n disini".$next_pos;
					if(($next_pos - $pos_dusunne) == 1){
						$DUSUN = $alamat[$pos_dusunne];
					}elseif(($next_pos - $pos_dusunne) > 1){
						$DUSUN = "";
						for($d=$pos_dusunne;$d < $next_pos;$d++){
							$DUSUN .= $alamat[$d]." ";
						}
						$DUSUN = trim($DUSUN);
					}else{
						$DUSUN = (array_key_exists($pos_dusunne,$alamat)) ? trim($alamat[$pos_dusunne]):trim($alamat[$pos_dusun]);
					}
					// echo " - " .$DUSUN."\n";
				}

				$dusuns[$line[0]][] = $DUSUN;
				$rws[$line[0]][$DUSUN][] = $RW;
				$rts[$line[0]][$DUSUN][$RW][] = $RT;

			}
			$i++;
		}
		$strSQL = "INSERT INTO tweb_wilayah_temp(`kode`,`tingkat`,`nama`) VALUES \n";
		fwrite($fp,$strSQL);
		$strSQLData = "('".$prop['kode']."',".$prop['tingkat'].",'".$prop['nama']."'),\n";
		fwrite($fp,$strSQLData);
		$strSQLData = "('".$kab['kode']."',".$kab['tingkat'].",'".$kab['nama']."'),\n";
		fwrite($fp,$strSQLData);
	
		foreach($kec as $kc){
			$strSQLData = "('".$kc['kode']."',".$kc['tingkat'].",'".$kc['nama']."'),\n";
			fwrite($fp,$strSQLData);
		}
		// $desa = array_unique($desa);
		foreach($desa as $kc){
			$strSQLData = "('".$kc['kode']."',".$kc['tingkat'].",'".$kc['nama']."'),\n";
			fwrite($fp,$strSQLData);
		}
		foreach($desa as $rs){
			fwrite($fp,$strSQL);
			// $strSQL .="('".fixSQL($rs['kode'])."',4,'".fixSQL($rs['nama'])."'),\n";
			$dusun = array_unique($dusuns[$rs['kode']]);
			$j=1;
			foreach($dusun as $kd=>$rd){
				echo "\n ".$rs['kode']." - ".$kd." - ".$rd." : ".str_pad($j,2,"0",STR_PAD_LEFT)."\n";
				$kode_dusun = $rs['kode'].str_pad($j,2,"0",STR_PAD_LEFT);
				// $strSQL .="('".fixSQL($kode_dusun)."',5,'".fixSQL($rd)."'),\n";
				$strSQLData ="('".fixSQL($kode_dusun)."',5,'".fixSQL($rd)."'),\n";
				fwrite($fp,$strSQLData);

				if(array_key_exists($rs['kode'],$rws)){
					// echo "Ada RW di ".$rs['kode']."\n ";
					$k=1;
					$new_rw = array_unique($rws[$rs['kode']][$rd]);
					foreach($new_rw as $RW){
						$kode_rw = $kode_dusun.str_pad($RW,2,"0",STR_PAD_LEFT);
						// $strSQL .="('".fixSQL($kode_rw)."',6,'".fixSQL($RW)."'),\n";
						$strSQLData ="('".fixSQL($kode_rw)."',6,'".fixSQL($RW)."'),\n";
						fwrite($fp,$strSQLData);
						$l = 1;
						$new_rt = array_unique($rts[$rs['kode']][$rd][$RW]);
						foreach($new_rt as $RT){
							$kode_rt = $kode_rw.str_pad($RT,2,"0",STR_PAD_LEFT);
							// $strSQL .="('".fixSQL($kode_rt)."',6,'".fixSQL($RT)."'),\n";
							$strSQLData ="('".fixSQL($kode_rt)."',7,'".fixSQL($RT)."'),\n";
							fwrite($fp,$strSQLData);
							$l++;
						}
						$k++;
					}
				}else{
					echo "Key ".$rs['kode']." tidak ada di rws->\n ";
				}
				// $rws = array_unique($rws[$rs['kode']][$rd]);
				// foreach($rws[$rs['kode']][$rd] as $rw){
				// 	echo "\n ".$rs['kode']." - ".$rd." RW ".$rw."\n";
				// 	foreach($rts[$rs['kode']][$rd][$rw] as $rt){
				// 		echo "\n ".$rs['kode']." - ".$rd." RW ".$rw." RT ".$rt."\n";
						
				// 	}
				// }
				$j++;
			}
			// echo var_dump($dusun);
		}

		// echo $strSQL;
		// echo "\n RW RW\n";
		// echo var_dump($rws);

		// echo "\n RT_RT\n";
		// echo var_dump($rts);
		fclose($file);


	}

	function impor_rts(){
		$file_csv = FCPATH ."assets/uploads/WONOGORI_KRT.csv";

		$dusun_array = array();
		$strSQL = "SELECT kode,nama,tingkat FROM tweb_wilayah WHERE tingkat=5 ORDER BY kode";
		$query = $this->db->query($strSQL);
		foreach ($query->result() as $rs){
			$dusun_array[substr($rs->kode,0,10)][$rs->nama] = array("nama"=>$rs->nama,
				"tingkat"=>$rs->tingkat,
				"kode"=>$rs->kode);
		}

		
		$file_sql = FCPATH ."assets/uploads/Data_KRT_".time().".sql";
		if(is_file($file_sql)){
		}else{
			$newfile = fopen($file_sql,"w");
			fclose($newfile);
		}
		$fp = fopen($file_sql, "a") or exit("Unable to open file!");

		$file_sql_rtm = FCPATH ."assets/uploads/RTM_".time().".sql";
		if(is_file($file_sql_rtm)){
		}else{
			$newfile = fopen($file_sql_rtm,"w");
			fclose($newfile);
		}
		$fp_rtm = fopen($file_sql_rtm, "a") or exit("Unable to open file!");
		
		$file = fopen($file_csv, 'r');
		$i=0;
		$RTM_SQL = "INSERT INTO tweb_rumahtangga 
		(`rtm_no`, `kepala_rumahtangga`, `kode_wilayah`, `alamat`, `tingkat_sejahtera`, `kelas_pbdt`) VALUES \n";
		fwrite($fp,$RTM_SQL);
		
		$strSQL = "INSERT INTO pbdt_rt(`Kode_Desa`, `Provinsi`, `Provinsi_kode`, `Kabupaten`, `Kabupaten_kode`, `Kecamatan`, `Kecamatan_kode`, `Desa`, `Desa_kode`, `Dusun`, `Dusun_kode`,`RW`, `RW_kode`, `RT`, `RT_kode`, `Alamat`, `Nama_KRT`, `RID_RumahTangga`, `col_1`, `col_2`, `col_3`, `col_4`, `col_5`, `col_6`, `col_7`, `col_8`, `col_9`, `col_10`, `col_11`, `col_12`, `col_13`, `col_14`, `col_15`, `col_16`, `col_17`, `col_18`, `col_19`, `col_20`, `col_21`, `col_22`, `col_23`, `col_24`, `col_25`, `col_26`, `col_27`, `col_28`, `col_29`, `col_30`, `col_31`, `col_32`, `col_33`, `col_34`, `col_35`, `col_36`, `col_37`, `col_38`, `col_39`, `col_40`, `col_41`, `col_42`, `col_43`, `col_44`, `col_45`, `col_46`, `col_47`, `col_48`, `col_49`, `col_50`, `col_51`, `col_52`, `col_53`, `col_54`, `col_55`, `col_56`, `col_57`, `col_58`, `col_59`, `col_60`, `col_61`, `col_62`, `col_63`, `col_64`, `col_65`, `status_kesejahteraan`,`periode_id`)
		VALUES\n";
		fwrite($fp_rtm,$strSQL);
		$SQL_Data = $strSQL;
		while (($rs = fgetcsv($file,10000,',','"')) !== FALSE) {
			if($i > 0){

				$alamat = extract_alamat($rs[5]);
				$RT = "00";
				$RW = "00";
				$DUSUN = $rs[4];
				$pos_rt = false;
				$pos_rw = false;
				$pos_dusun = false;
				$kode_wilayah = $rs[0]."00".$RW.$RT;

				foreach($alamat as $key=>$ra){
					$ra = "*".$ra."*";
					if(strtoupper(trim($ra)) == "*RT*"){
						$pos_rt = $key;
						$pos_rtne = (int)$key+1;
						$RT = substr(str_pad(trim($alamat[$pos_rtne]),2,"0",STR_PAD_LEFT),-2);
					}
					if(strtoupper(trim($ra)) == "*RW*"){
						$pos_rw = $key;
						$pos_rwne = (int)$key+1;
						if(array_key_exists($pos_rwne,$alamat)){
							$RW = substr(str_pad(trim($alamat[$pos_rwne]),2,"0",STR_PAD_LEFT),-2);
						}
					}
					if(trim($ra) == "*DUSUN*"){
						$pos_dusunne = (int)$key+1;
					}
				}

				if($pos_dusunne > 0){
					$next_pos = ($pos_rt > $pos_rw) ? $pos_rw:$pos_rt;
					if(($next_pos - $pos_dusunne) == 1){
						$DUSUN = $alamat[$pos_dusunne];
					}elseif(($next_pos - $pos_dusunne) > 1){
						$DUSUN = "";
						for($d=$pos_dusunne;$d < $next_pos;$d++){
							$DUSUN .= $alamat[$d]." ";
						}
						$DUSUN = trim($DUSUN);
					}else{
						$DUSUN = (array_key_exists($pos_dusunne,$alamat)) ? trim($alamat[$pos_dusunne]):trim($alamat[$pos_dusun]);
					}
				}
				if($DUSUN != ""){
					// echo "\n".$rs[0];
					if(is_array($dusun_array[$rs[0]])){
						if(array_key_exists($DUSUN,$dusun_array[$rs[0]])){
							$kode_wilayah = $dusun_array[$rs[0]][$DUSUN]['kode'].$RW.$RT;
						}
					}
				}

				if(fmod($i,50) == 0){
					$RTM_Data = "('".fixSQL($rs[7])."','".fixSQL($rs[6])."','".fixSQL($kode_wilayah)."','".fixSQL($rs[5])."','".fixSQL($rs[7])."','".fixSQL($rs[73])."'); \n";
					$strSQLData = "('".$rs[0]."', '".fixSQL($rs['1'])."', '".substr($rs['0'],0,2)."','".fixSQL($rs['2'])."', '".substr($rs['0'],0,4)."','".fixSQL($rs['3'])."', '".substr($rs['0'],0,7)."','".fixSQL($rs['4'])."', '".$rs['0']."','".fixSQL($DUSUN)."','".substr($kode_wilayah,0,12)."','".fixSQL($RW)."','".substr($kode_wilayah,0,14)."','".fixSQL($RT)."','".substr($kode_wilayah,0,16)."','".fixSQL($rs['5'])."','".fixSQL($rs['6'])."','".fixSQL($rs['7'])."','".fixSQL($rs['8'])."','".fixSQL($rs['9'])."','".fixSQL($rs['10'])."','".fixSQL($rs['11'])."','".fixSQL($rs['12'])."','".fixSQL($rs['13'])."','".fixSQL($rs['14'])."','".fixSQL($rs['15'])."','".fixSQL($rs['16'])."','".fixSQL($rs['17'])."','".fixSQL($rs['18'])."','".fixSQL($rs['19'])."','".fixSQL($rs['20'])."','".fixSQL($rs['21'])."','".fixSQL($rs['22'])."','".fixSQL($rs['23'])."','".fixSQL($rs['24'])."','".fixSQL($rs['15'])."','".fixSQL($rs['26'])."','".fixSQL($rs['27'])."','".fixSQL($rs['28'])."','".fixSQL($rs['29'])."','".fixSQL($rs['30'])."','".fixSQL($rs['31'])."','".fixSQL($rs['32'])."','".fixSQL($rs['33'])."','".fixSQL($rs['34'])."','".fixSQL($rs['35'])."','".fixSQL($rs['36'])."','".fixSQL($rs['37'])."','".fixSQL($rs['38'])."','".fixSQL($rs['39'])."','".fixSQL($rs['40'])."','".fixSQL($rs['41'])."','".fixSQL($rs['42'])."','".fixSQL($rs['43'])."','".fixSQL($rs['44'])."','".fixSQL($rs['45'])."','".fixSQL($rs['46'])."','".fixSQL($rs['47'])."','".fixSQL($rs['48'])."','".fixSQL($rs['49'])."','".fixSQL($rs['50'])."','".fixSQL($rs['51'])."','".fixSQL($rs['52'])."','".fixSQL($rs['53'])."','".fixSQL($rs['54'])."','".fixSQL($rs['55'])."','".fixSQL($rs['56'])."','".fixSQL($rs['57'])."','".fixSQL($rs['58'])."','".fixSQL($rs['59'])."','".fixSQL($rs['60'])."','".fixSQL($rs['61'])."','".fixSQL($rs['62'])."','".fixSQL($rs['63'])."','".fixSQL($rs['64'])."','".fixSQL($rs['65'])."','".fixSQL($rs['66'])."','".fixSQL($rs['67'])."','".fixSQL($rs['68'])."','".fixSQL($rs['69'])."','".fixSQL($rs['70'])."','".fixSQL($rs['71'])."','".fixSQL($rs['72'])."','".fixSQL($rs['73'])."',1);\n";
					fwrite($fp,$RTM_Data);
					fwrite($fp,$RTM_SQL);
					
					fwrite($fp_rtm,$strSQLData);
					fwrite($fp_rtm,$strSQL);
					// $SQL_Data .= $strSQLData.";\n".$strSQL;
				}else{
					$RTM_Data = "('".fixSQL($rs[7])."','".fixSQL($rs[6])."','".fixSQL($kode_wilayah)."','".fixSQL($rs[5])."','".fixSQL($rs[7])."','".fixSQL($rs[73])."'), \n";
					$strSQLData = "('".$rs[0]."', '".fixSQL($rs['1'])."', '".substr($rs['0'],0,2)."','".fixSQL($rs['2'])."', '".substr($rs['0'],0,4)."','".fixSQL($rs['3'])."', '".substr($rs['0'],0,7)."','".fixSQL($rs['4'])."', '".$rs['0']."','".fixSQL($DUSUN)."','".substr($kode_wilayah,0,12)."','".fixSQL($RW)."','".substr($kode_wilayah,0,14)."','".fixSQL($RT)."','".substr($kode_wilayah,0,16)."','".fixSQL($rs['5'])."','".fixSQL($rs['6'])."','".fixSQL($rs['7'])."','".fixSQL($rs['8'])."','".fixSQL($rs['9'])."','".fixSQL($rs['10'])."','".fixSQL($rs['11'])."','".fixSQL($rs['12'])."','".fixSQL($rs['13'])."','".fixSQL($rs['14'])."','".fixSQL($rs['15'])."','".fixSQL($rs['16'])."','".fixSQL($rs['17'])."','".fixSQL($rs['18'])."','".fixSQL($rs['19'])."','".fixSQL($rs['20'])."','".fixSQL($rs['21'])."','".fixSQL($rs['22'])."','".fixSQL($rs['23'])."','".fixSQL($rs['24'])."','".fixSQL($rs['15'])."','".fixSQL($rs['26'])."','".fixSQL($rs['27'])."','".fixSQL($rs['28'])."','".fixSQL($rs['29'])."','".fixSQL($rs['30'])."','".fixSQL($rs['31'])."','".fixSQL($rs['32'])."','".fixSQL($rs['33'])."','".fixSQL($rs['34'])."','".fixSQL($rs['35'])."','".fixSQL($rs['36'])."','".fixSQL($rs['37'])."','".fixSQL($rs['38'])."','".fixSQL($rs['39'])."','".fixSQL($rs['40'])."','".fixSQL($rs['41'])."','".fixSQL($rs['42'])."','".fixSQL($rs['43'])."','".fixSQL($rs['44'])."','".fixSQL($rs['45'])."','".fixSQL($rs['46'])."','".fixSQL($rs['47'])."','".fixSQL($rs['48'])."','".fixSQL($rs['49'])."','".fixSQL($rs['50'])."','".fixSQL($rs['51'])."','".fixSQL($rs['52'])."','".fixSQL($rs['53'])."','".fixSQL($rs['54'])."','".fixSQL($rs['55'])."','".fixSQL($rs['56'])."','".fixSQL($rs['57'])."','".fixSQL($rs['58'])."','".fixSQL($rs['59'])."','".fixSQL($rs['60'])."','".fixSQL($rs['61'])."','".fixSQL($rs['62'])."','".fixSQL($rs['63'])."','".fixSQL($rs['64'])."','".fixSQL($rs['65'])."','".fixSQL($rs['66'])."','".fixSQL($rs['67'])."','".fixSQL($rs['68'])."','".fixSQL($rs['69'])."','".fixSQL($rs['70'])."','".fixSQL($rs['71'])."','".fixSQL($rs['72'])."','".fixSQL($rs['73'])."',1),\n";
					fwrite($fp,$RTM_Data);
					fwrite($fp_rtm,$strSQLData);
					// $SQL_Data .= $strSQLData.",";
				}

				// echo $RTM_Data;
			}
			$i++;
		}
		// echo $SQL_Data;

	}


	function impor_art(){
		$file_csv = FCPATH ."assets/uploads/wonogiri_idv.csv";

		$dusun_array = array();
		$strSQL = "SELECT kode,nama,tingkat FROM tweb_wilayah WHERE tingkat=5 ORDER BY kode";
		$query = $this->db->query($strSQL);
		foreach ($query->result() as $rs){
			$dusun_array[substr($rs->kode,0,10)][$rs->nama] = array("nama"=>$rs->nama,
				"tingkat"=>$rs->tingkat,
				"kode"=>$rs->kode);
		}

		
		// $file_sql = FCPATH ."assets/uploads/Data_IDV_".time().".sql";
		// if(is_file($file_sql)){
		// }else{
		// 	$newfile = fopen($file_sql,"w");
		// 	fclose($newfile);
		// }
		// $fp = fopen($file_sql, "a") or exit("Unable to open file!");

		$file_sql_rtm = FCPATH ."assets/uploads/IDV_".time().".sql";
		if(is_file($file_sql_rtm)){
		}else{
			$newfile = fopen($file_sql_rtm,"w");
			fclose($newfile);
		}
		$fp_rtm = fopen($file_sql_rtm, "a") or exit("Unable to open file!");
		
		$file = fopen($file_csv, 'r');
		
		$i=0;
		$strSQL = "INSERT INTO pbdt_idv(`Kode_Desa`, `Provinsi`, `Provinsi_kode`, `Kabupaten`, `Kabupaten_kode`, `Kecamatan`, `Kecamatan_kode`, `Desa`, `Desa_kode`, `Dusun`, `Dusun_kode`, `RW`, `RW_kode`, `RT`, `RT_kode`, `Alamat`, `Kode_Rumah_Tangga`, `Kode_Individu`, `RID_RumahTangga`, `RID_Individu`, `nik`, `nama`, `col_1`, `col_2`, `col_3`, `col_4`, `col_5`, `col_6`, `col_7`, `col_8`, `col_9`, `col_10`, `col_11`, `col_12`, `col_13`, `col_14`, `col_15`, `col_16`, `col_17`, `col_18`, `col_19`, `col_20`, `Status_Kesejahteraan`,`periode_id`) VALUES\n";
		fwrite($fp_rtm,$strSQL);
		$SQL_Data = $strSQL;
		while (($rs = fgetcsv($file,10000,',','"')) !== FALSE) {
			if($i > 0){

				$alamat = extract_alamat($rs[5]);
				$RT = "00";
				$RW = "00";
				$DUSUN = $rs[4];
				$pos_rt = false;
				$pos_rw = false;
				$pos_dusun = false;
				$kode_wilayah = $rs[0]."00".$RW.$RT;

				foreach($alamat as $key=>$ra){
					$ra = "*".$ra."*";
					if(strtoupper(trim($ra)) == "*RT*"){
						$pos_rt = $key;
						$pos_rtne = (int)$key+1;
						$RT = substr(str_pad(trim($alamat[$pos_rtne]),2,"0",STR_PAD_LEFT),-2);
					}
					if(strtoupper(trim($ra)) == "*RW*"){
						$pos_rw = $key;
						$pos_rwne = (int)$key+1;
						if(array_key_exists($pos_rwne,$alamat)){
							$RW = substr(str_pad(trim($alamat[$pos_rwne]),2,"0",STR_PAD_LEFT),-2);
						}
					}
					if(trim($ra) == "*DUSUN*"){
						$pos_dusunne = (int)$key+1;
					}
				}

				if($pos_dusunne > 0){
					$next_pos = ($pos_rt > $pos_rw) ? $pos_rw:$pos_rt;
					if(($next_pos - $pos_dusunne) == 1){
						$DUSUN = $alamat[$pos_dusunne];
					}elseif(($next_pos - $pos_dusunne) > 1){
						$DUSUN = "";
						for($d=$pos_dusunne;$d < $next_pos;$d++){
							$DUSUN .= $alamat[$d]." ";
						}
						$DUSUN = trim($DUSUN);
					}else{
						$DUSUN = (array_key_exists($pos_dusunne,$alamat)) ? trim($alamat[$pos_dusunne]):trim($alamat[$pos_dusun]);
					}
				}
				if($DUSUN != ""){
					// echo "\n".$rs[0];
					if(is_array($dusun_array[$rs[0]])){
						if(array_key_exists($DUSUN,$dusun_array[$rs[0]])){
							$kode_wilayah = $dusun_array[$rs[0]][$DUSUN]['kode'].$RW.$RT;
						}
					}
				}


				if(fmod($i,250) == 0){
					$strSQLData = "('".$rs[0]."', '".fixSQL($rs['1'])."', '".substr($rs['0'],0,2)."','".fixSQL($rs['2'])."', '".substr($rs['0'],0,4)."','".fixSQL($rs['3'])."', '".substr($rs['0'],0,7)."','".fixSQL($rs['4'])."', '".$rs['0']."','".fixSQL($DUSUN)."','".substr($kode_wilayah,0,12)."','".fixSQL($RW)."','".substr($kode_wilayah,0,14)."','".fixSQL($RT)."','".substr($kode_wilayah,0,16)."','".fixSQL($rs['5'])."','".fixSQL($rs['6'])."','".fixSQL($rs['7'])."','".fixSQL($rs['8'])."','".fixSQL($rs['9'])."','".fixSQL($rs['10'])."','".fixSQL($rs['11'])."','".fixSQL($rs['12'])."','".fixSQL($rs['13'])."','".fixSQL($rs['14'])."','".fixSQL($rs['15'])."','".fixSQL($rs['16'])."','".fixSQL($rs['17'])."','".fixSQL($rs['18'])."','".fixSQL($rs['19'])."','".fixSQL($rs['20'])."','".fixSQL($rs['21'])."','".fixSQL($rs['22'])."','".fixSQL($rs['23'])."','".fixSQL($rs['24'])."','".fixSQL($rs['15'])."','".fixSQL($rs['26'])."','".fixSQL($rs['27'])."','".fixSQL($rs['28'])."','".fixSQL($rs['29'])."','".fixSQL($rs['30'])."','".fixSQL($rs['31'])."','".fixSQL($rs['32'])."',1);\n";
					fwrite($fp_rtm,$strSQLData);
					fwrite($fp_rtm,$strSQL);
				}else{
					$strSQLData = "('".$rs[0]."', '".fixSQL($rs['1'])."', '".substr($rs['0'],0,2)."','".fixSQL($rs['2'])."', '".substr($rs['0'],0,4)."','".fixSQL($rs['3'])."', '".substr($rs['0'],0,7)."','".fixSQL($rs['4'])."', '".$rs['0']."','".fixSQL($DUSUN)."','".substr($kode_wilayah,0,12)."','".fixSQL($RW)."','".substr($kode_wilayah,0,14)."','".fixSQL($RT)."','".substr($kode_wilayah,0,16)."','".fixSQL($rs['5'])."','".fixSQL($rs['6'])."','".fixSQL($rs['7'])."','".fixSQL($rs['8'])."','".fixSQL($rs['9'])."','".fixSQL($rs['10'])."','".fixSQL($rs['11'])."','".fixSQL($rs['12'])."','".fixSQL($rs['13'])."','".fixSQL($rs['14'])."','".fixSQL($rs['15'])."','".fixSQL($rs['16'])."','".fixSQL($rs['17'])."','".fixSQL($rs['18'])."','".fixSQL($rs['19'])."','".fixSQL($rs['20'])."','".fixSQL($rs['21'])."','".fixSQL($rs['22'])."','".fixSQL($rs['23'])."','".fixSQL($rs['24'])."','".fixSQL($rs['15'])."','".fixSQL($rs['26'])."','".fixSQL($rs['27'])."','".fixSQL($rs['28'])."','".fixSQL($rs['29'])."','".fixSQL($rs['30'])."','".fixSQL($rs['31'])."','".fixSQL($rs['32'])."',1),\n";
					fwrite($fp_rtm,$strSQLData);
					// $SQL_Data .= $strSQLData.",";
				}

				// echo $RTM_Data;
			}
			$i++;
		}
		// echo $SQL_Data;

	}	

	function infoUser(){
		echo var_dump($this->session->userdata);
	}
  function list_situs(){
		$base_domain = "bappedawng.id";
		$strSQL = "
		SELECT d.kode,d.nama,d.isDesa,
			kec.nama as kecamatan 
		FROM tweb_wilayah d 
			LEFT JOIN tweb_wilayah kec ON substring(d.kode,1,7)=kec.kode
		WHERE d.tingkat=4
		";
		$query = $this->db->query($strSQL);
		$i=1;
		echo "INSERT INTO tweb_situs(`nama`, `is_desa`, `ndesc`, `base_domain`, `base_url`, `kode_base`) VALUES\n";
		foreach ($query->result() as $rs){
			$i++;
			$domain = trim($rs->nama)."-".trim($rs->kecamatan).".".$base_domain;
			$domain = trim(strtolower($domain));
			$nama = ($rs->isDesa == 1) ? "Desa ".$rs->nama." Kec. ".$rs->kecamatan:"Kelurahan ".$rs->nama." Kec. ".$rs->kecamatan;
			echo "('".$nama."','".$rs->isDesa."','".$nama."','".$domain."','http://".$domain."','".$rs->kode."'),\n";
		}
	}

	function update_capil(){
		$file_sql_rtm = FCPATH ."assets/uploads/CAPIL.sql";
		if(is_file($file_sql_rtm)){
		}else{
			$newfile = fopen($file_sql_rtm,"w");
			fclose($newfile);
		}
		$fp_rtm = fopen($file_sql_rtm, "a") or exit("Unable to open file!");
		
		// file counter 
		$limit = 2000;
		$file_counter = FCPATH ."/assets/uploads/counter_capil.txt";
		$counter = file_get_contents($file_counter);
		$counter = ($counter == "")? 1:$counter;

		$start = ($counter -1) * $limit;
		$strSQL = "
		SELECT d.id,d.nik, 
			(SELECT id FROM tweb_penduduk WHERE nik=d.nik) as capil
		FROM 
		pbdt_idv d ORDER BY id ASC LIMIT ".$start.",".$limit;
		$query = $this->db->query($strSQL);
		foreach ($query->result() as $rs){
			if($rs->capil == 1){
				$strSQLData = "UPDATE pbdt_idv SET penduduk_id='".$rs->id."';";
				fwrite($fp_rtm,$strSQLData);
			}
		}
		echo $strSQL;

		$new_number = (int)$counter + 1;
		file_put_contents($file_counter,$new_number);

	}

	function activate_all_desa(){
		$this->load->model('tools_model');
		$data = $this->tools_model->get_all_sites();
		// echo var_dump($data);
		$sqlUser = "INSERT INTO tweb_users (`nama`,`userid`,`email`,`passwt`,`tingkat`,`status`,`wilayah`,`situs_id`) VALUES\n";
		$strSQL = "INSERT INTO `tweb_situs_configs`(`situs_id`, `nama_desa`, `kode_desa`, `desa_email`, `nama_kecamatan`, `kode_kecamatan`,`nama_kabupaten`, `kode_kabupaten`, `nama_propinsi`, `kode_propinsi`, `updated_by`) VALUES \n";
		echo $strSQL;
		if(is_array($data)){
			// echo var_dump($data);
			foreach($data as $rs){
				// echo var_dump($rs);
				if($rs['id'] > 1){
					$isdesa = ($rs['is_desa'] == 1) ? "desa":"kelurahan";

					$desa = explode(".",trim($rs['base_domain']));
					$desa = explode("-",$desa[0]);

					$desa_user = $isdesa."-".fixNamaUrl(trim($desa[0]))."-".fixNamaUrl(trim($desa[1]));
					$desa_email = $isdesa."-".fixNamaUrl(trim($desa[0]))."-".fixNamaUrl(trim($desa[1]))."@bappedawng.id";
		
					$strSQLx = "('".$rs['id']."','".strtoupper($desa[0])."','".$rs['kode_base']."','".$desa_email."','".fixSQL(strtoupper($desa[1]))."','".substr($rs['kode_base'],0,7)."','Wonogiri','".substr($rs['kode_base'],0,4)."','Jawa Tengah','33',1),\n";
					echo $strSQLx;
					$sqlUser .= "('".$isdesa." ".ucwords($desa[0])." - ".ucwords($desa[1])."','".$desa_user."','".$desa_email."','$2y$10$xZT0Z.CaaGqm6ceTXwSuXO4ir3wtxERm/yzgx2M1QYeod.mVDOC8i',3,1,'".$rs['kode_base']."','".$rs['id']."'),\n";
	
				}
			}
		}else{
			echo "data bukan array";
		}
		echo $sqlUser;
	}

}
