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

class Petajson extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('siteman_model');
		$this->load->model('wilayah_model');
	}
	   
  public function index(){
		ae_nocache();
		echo "Hello World";
	}
	
	function data_wilayah($varKode){
		ae_nocache();

		$peta = $this->wilayah_model->data_wilayah($varKode);
		echo var_dump($peta);
		
	}
	
	function bansos($varID,$varKode){
		ae_nocache();
		header('Content-Type: application/json');
		
		$this->load->model('program_model');
		$data['program'] = $this->program_model->program_load($varID);
		$data['program_peserta']= $this->program_model->program_peserta_bywilayah($varID,$data['program']['jenis'],$varKode);
		$data_peta = '{
			"type": "FeatureCollection",
			"features": [
				';
		$i=1;
		$n = count($data['program_peserta']);
		$garis = "white";
		$warna = Gradient('#eeeeff','0033ff',$n);
		$angkaSum  = 0;
		foreach($data['program_peserta'] as $key=>$item){
			$strKoma = ($i < $n) ? ",":"";
			$angkaSum = $item['angkace'] + $item['angkaco'];
			$data_peta .= 
			"{
				\"type\":\"Feature\",
				\"properties\":{
					\"nama\": \"".$item['nama']."\",
					\"info_title\": \"".$item['nama']."\",
					\"colorStroke\": \"".$garis."\",
					\"colorFill\": \"#".$warna[$i]."\",
					\"info\": \"<h2>".$item['nama']."</h2><div><table class='table'><tr><td>Laki-laki</td><th class='angka'>".number_format($item['angkaco'],0)."</th></tr><tr><td>Perempuan</td><th class='angka'>".number_format($item['angkace'],0)."</th></tr></table></div>\",
					\"angkaCo\": \"".number_format($item['angkaco'],0)."\",
					\"angkaCe\": \"".number_format($item['angkace'],0)."\",
					\"angkaSum\": \"".number_format($angkaSum,0)."\"
					
					},
				\"geometry\": {
						\"type\": \"Polygon\",
						\"coordinates\": [".$item['path']."]
					}			
			}".$strKoma;
			$i++;
		}
		$data_peta .= "] \n } \n";
		echo $data_peta;
	}
	
	function data_skgakin($varKode){
		ae_nocache();
		header('Content-Type: application/json');
		
		$this->load->model('skgakin_model');
		$periode = $this->skgakin_model->skgakin_periode();
		// $data[] = $this->skgakin_model->skgakin_by_wilayah($varKode,$periode['aktif']);
		$data['skgakin'] = $this->skgakin_model->skgakin_index($varKode);

		$data["periode_aktif"] = $periode["aktif"];
		$data["periode"] = $periode["list"];

		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["kode"] = $varKode;

		$this->load->view('siteman/tkpk_skgakin_peta_ajax',$data);

	}
	
	function skgakin($varKode=0,$varPeriode=1){
		ae_nocache();
		header('Content-Type: application/json');
		
		$data_peta = '{
			"type": "FeatureCollection",
			"features": [
				';
		
		$this->load->model('skgakin_model');
		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["kode"] = $varKode;
		$data['skgakin'] = $this->skgakin_model->skgakin_by_wilayah($varKode,$varPeriode);
		$i= 1;
		$n = count($data['skgakin']['sub']);
		$wil = $data['skgakin']['stingkatan'];
		$garis = "white";
		$warna = Gradient('#eeeeff','0033ff',$n);
		
		foreach($data['skgakin']['sub'] as $key=>$item){
			//echo var_dump();
			$cf = $warna[$i];
			$strKoma = ($i < $n) ? ",":"";
			$i++;
			$data_peta .= 
			"{
				\"type\":\"Feature\",
				\"properties\":{
					\"nama\": \"".$wil." ".$item['nama']."\",
					\"info_title\": \"".$wil." ".$item['nama']."\",
					\"colorStroke\": \"".$garis."\",
					\"colorFill\": \"#".$cf."\",
					\"art\": \"".number_format($item['art'],0)."\",
					\"rtm\": \"".number_format($item['rt'],0)."\"
					},
				\"geometry\": {
						\"type\": \"Polygon\",
						\"coordinates\": [".$item['path']."]
					}			
			}".$strKoma;

		}

		$data_peta .= "] \n } \n";

		echo $data_peta;		
	}

	function data_json_gmap($varKode=0){
		/*
		 * Spt di Google 
		 * https://storage.googleapis.com/mapsdevsite/json/google.json
		 *
		 */ 
		//$varKode = ($varKode==0) ? KODE_BASE:$varKode;
		$this->load->model('skgakin_model');
		$periode = $this->skgakin_model->skgakin_periode();
		// $data[] = $this->skgakin_model->skgakin_by_wilayah($varKode,$periode['aktif']);
		$data['skgakin'] = $this->skgakin_model->skgakin_index($varKode);
		
		$data["periode_aktif"] = $periode["aktif"];
		$data["periode"] = $periode["list"];

		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["kode"] = $varKode;

		$this->load->view('siteman/tkpk_skgakin_peta_ajax',$data);
		if(ENVIRONMENT=='development'){
 			$this->output->enable_profiler(TRUE);
		}
	}

	function peta_json($varKode=0){
		/*
		 * Spt di Google 
		 * https://storage.googleapis.com/mapsdevsite/json/google.json
		 *
		 */ 
		$data_peta = '{
			"type": "FeatureCollection",
			"features": [
				';
		$varKode = ($varKode==0) ? KODE_BASE:$varKode;
		$this->load->model('skgakin_model');
		$periode = $this->skgakin_model->skgakin_periode();
		// $data[] = $this->skgakin_model->skgakin_by_wilayah($varKode,$periode['aktif']);
		$data['skgakin'] = $this->skgakin_model->skgakin_index($varKode);
		
		$data["periode_aktif"] = $periode["aktif"];
		$data["periode"] = $periode["list"];

		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["kode"] = $varKode;
		$peta = $this->wilayah_model->data_wilayah($varKode);
		$i=0;
		$n = count($data['skgakin']['sub']);

		$garis = "white";
		$warna = Gradient('#eeeeff','0033ff',$n);
		
		$wil = $data['skgakin']['stingkatan'];
		foreach($data['skgakin']['sub'] as $key=>$item){
			//echo var_dump();
			$cf = $warna[$i];
			$i++;
			$strKoma = ($i < $n) ? ",":"";
			$data_peta .= 
			"{
				\"type\":\"Feature\",
				\"properties\":{
					\"nama\": \"".$wil." ".$item['nama']."\",
					\"colorStroke\": \"".$garis."\",
					\"colorFill\": \"#".$cf."\",
					\"info\": \"<h2>".$wil." ".$item['nama']."</h2><div><table class='table'><tr><td>Rumah Tangga</td><th class='angka'>".$item['rt2']."</th></tr><tr><td>Individu</td><th class='angka'>".$item['art2']."</th></tr></table></div>\"
					},
				\"geometry\": {
						\"type\": \"Polygon\",
						\"coordinates\": [".$peta['sub'][$key]['path']."]
					}			
			}".$strKoma;

		}

		$data_peta .= "] \n } \n";

		echo $data_peta;

	}
	

	function pbdt($varKode,$varPeriode=1){
		ae_nocache();
		header('Content-Type: application/json');
		$data_peta = '{
			"type": "FeatureCollection",
			"features": [
				';
		$this->load->model('pbdt_model');
		$data = $this->pbdt_model->data_by_desil($varKode,$varPeriode);
		$tingkatan = _tingkat_wilayah();

		$n = count($data);
		$garis = "white";
		$warna = Gradient('#eeeeff','0033ff',$n);
		$i=0;
		foreach($data as $key=>$item){
			//echo var_dump();
			$cf = $warna[$i];
			$i++;
			$strKoma = ($i < $n) ? ",":"";
			$data_peta .= 
			"{
				\"type\":\"Feature\",
				\"properties\":{
					\"nama\": \"".$item['tingkat']." ".$item['nama']."\",
					\"colorStroke\": \"".$garis."\",
					\"colorFill\": \"#".$cf."\",
					\"info_title\": \"".$tingkatan[$item['tingkat']]." ".$item['nama']."\",
					\"rtm_ds1\": \"".$item['kat1']."\",
					\"rtm_ds2\": \"".$item['kat2']."\",
					\"rtm_ds3\": \"".$item['kat3']."\",
					\"rtm_ds4\": \"".$item['kat4']."\",
					\"idv_ds1\": \"".$item['pkat1']."\",
					\"idv_ds2\": \"".$item['pkat2']."\",
					\"idv_ds3\": \"".$item['pkat3']."\",
					\"idv_ds4\": \"".$item['pkat4']."\"
					},
				\"geometry\": {
						\"type\": \"Polygon\",
						\"coordinates\": [".$item['path']."]
					}			
			}".$strKoma;

		}

		$data_peta .= "] \n } \n";
		echo $data_peta;
	}


	function pbdt_indikator($varKode="",$varID=0,$varDs){
		$data_peta = '{
			"type": "FeatureCollection",
			"features": [
				';
		
		$this->load->model('pbdt_model');
		$varKode = ($varKode == "") ? KODE_BASE : $varKode;
		
		$data = $this->pbdt_model->data_by_indikator2map($varID,$varKode,$varDs);
		$tingkatan = _tingkat_wilayah();

		$n = count($data);
		$garis = "white";
		$warna = Gradient('#eeeeff','0033ff',$n);
		$i=0;
		
		foreach($data as $key=>$item){

			$cf = $warna[$i];
			$i++;
			$strKoma = ($i < $n) ? ",":"";
			$data_peta .= 
			"{
				\"type\":\"Feature\",
				\"properties\":{
					\"nama\": \"".$item['tingkat']." ".$item['nama']."\",
					\"colorStroke\": \"".$garis."\",
					\"colorFill\": \"#".$cf."\",";
					foreach($item['nilai'] as $ni=>$nil){
						$data_peta .= "\"".$ni."\": \"".$nil."\", \n";
					}
					$data_peta .= 
					"\"info_title\": \"".$tingkatan[$item['tingkat']]." ".$item['nama']."\"
					},
				\"geometry\": {
						\"type\": \"Polygon\",
						\"coordinates\": [".$item['path']."]
					}			
			}".$strKoma;

		}

		$data_peta .= "] \n } \n";
		echo $data_peta;
	}

	function json_osm($varR='rts',$varKode=0,$varPeriode=0){
		/*
		 * Spt di Google 
		 * https://leafletjs.com/examples/choropleth/example.html
		 *
		 */ 
		$this->load->model('bdt_model');
		$periode = $this->bdt_model->periodes(0);
		$periode_id = ($varPeriode == 0) ? $periode['periode_aktif']:$varPeriode;
		// $data_peta = '{
		// 	"type": "FeatureCollection",
		// 	"features": [';
		$varKode = ($varKode==0) ? KODE_BASE:$varKode;
		$lenKode = strlen($varKode);
		switch ($lenKode) {
			case 4:
				# code...
				$p = "SUBSTR(d.kode_wilayah,1,7)";
				break;
			case 7:
				# code.
				$p = "SUBSTR(d.kode_wilayah,1,10)";
				break;
			case 10:
				# code...
				$p = "SUBSTR(d.kode_wilayah,1,12)";
				break;
			case 12:
				# code...
				$p = "SUBSTR(d.kode_wilayah,1,15)";
				break;
			case 15:
				# code...
				$p = "SUBSTR(d.kode_wilayah,1,18)";
				break;
			case 18:
				# code...
				$p = "d.kode_wilayah";
				break;
			default:
				# code...2
				$p = "SUBSTR(d.kode_wilayah,1,4)";
				break;
		}
		$strSQL = "SELECT DISTINCT(".$p.") as dkode,COUNT(`lead_id`) as numrows,w.`nama` as wnama,w.`path` as wpath FROM bdt_".$varR." d 
		LEFT JOIN tweb_wilayah w ON ".$p."=w.kode
		WHERE (d.`kode_wilayah` LIKE '".$varKode."%') AND (d.`periode_id`='".$periode_id."') GROUP BY ".$p."
		ORDER BY COUNT(`lead_id`)";
		$features = array();
		$query = $this->db->query($strSQL);
		if($query->num_rows() > 0){
			$color_id = 1;
			foreach ($query->result_array() as $rs){
				if($rs['wpath']){

					$features[] = array(
						'type'=>'Feature',
						'id'=>$rs['dkode'],
						'properties'=>array(
							'name'=>$rs['wnama'],
							'density'=>$rs['numrows'],
							'color_id'=>$color_id
						),
						'geometry'=>array(
							'type'=>'Polygon',
							'coordinates'=>[json_decode($rs['wpath'],true)]
						)
					);
					$color_id++;
				}
			}
		}
		if(count($features) > 0){
			$data_peta = array(
				'type'=>'FeatureCollection',
				'features'=>$features
			);
			echo "var statesData_".$varR." =".json_encode($data_peta);
	
		}else{
			echo "FALSE";
		}

		// echo var_dump($data_peta);
	}	
}
