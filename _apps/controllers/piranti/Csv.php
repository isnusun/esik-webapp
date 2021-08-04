<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Csv extends CI_Controller {

	function index(){
		echo "Hello World";
	}

	function import_rts(){
		$file_csv = FCPATH."";
		echo $file_csv;
	}
	function import_path_kecamatan(){
		$kecamatans = array();
		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=3 AND kode LIKE '3313%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$kecamatans[$rs['nama']] = array(
						'nama'=>$rs['nama'],
						'kode'=>$rs['kode'],
					);
				}
			}
		}
		$file_geojson = FCPATH."src/peta_geojson/batas_kecamatan/kecamatan.geojson";
		// echo $file_geojson;
		$data = file_get_contents($file_geojson);
		$geojson = json_decode($data);
		// echo var_dump($geojson);
		$nomer = 1;
		foreach ($geojson->features as $key => $value) {
			// echo var_dump($value);
			# code...
			$kecamatan = strtoupper(trim($value->properties->KECAMATAN));
			$kecamatan = str_replace(" ","",$kecamatan);

			// $kecamatan = ($kecamatan == "JUMANTORO") ? "JUMANTONO":$kecamatan;
			$kecamatan_kode = strtoupper(trim($value->properties->KODE_KEC));

			// echo "<h2>".$nomer.". ".$kecamatans[$kecamatan]['kode']." == ".$kecamatan_kode." / Kec. ".$kecamatan."</h2>";
			$paths = $value->geometry->coordinates;
			$path_desa = [];
			foreach($paths[0] as $path){
				// echo var_dump($path);
				$lon = $path[0];
				$lon = str_replace('float(','',$lon);
				$lon = str_replace(')','',$lon);

				$lat = $path[1];
				$lat = str_replace('float(','',$lat);
				$lat = str_replace(')','',$lat);

				$path_item = array($lon,$lat);
				array_push($path_desa,$path_item);
			}
			// // ".fixSQL(json_encode($path_desa,JSON_NUMERIC_CHECK))."
			$map_path = fixSQL(json_encode($path_desa,JSON_NUMERIC_CHECK));
			// echo var_dump($map_path);
			$strSQL = "UPDATE tweb_wilayah tw SET tw.`path`='".$map_path."' WHERE kode='".$kecamatan_kode."'; \n";
			echo $strSQL;
			// echo "<pre>".json_encode($strSQL)."</pre>";
			$nomer++;
		}		
	}

	function import_path_desa(){
		$kecamatans = array();
		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=3 AND kode LIKE '3313%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$kecamatans[$rs['kode']] = array(
						'nama'=>$rs['nama'],
						'kode'=>$rs['kode'],
					);
				}
			}
		}
		// echo var_dump($kecamatans);
		$desas = array();
		$strSQL = "SELECT kode,nama FROM tweb_wilayah WHERE tingkat=4 AND kode LIKE '3313%'";
		$query = $this->db->query($strSQL);
		if($query){
			if($query->num_rows() > 0){
				foreach($query->result_array() as $rs){
					$kode_kecamatan = substr($rs['kode'],0,7);
					// echo $kode_kecamatan;
					$desas[$kecamatans[$kode_kecamatan]['nama']][$rs['nama']] = $rs['kode'];
				}
			}
		}
		// echo var_dump($desas);

		$file_geojson = FCPATH."src/peta_geojson/batas_desa/desa_karanganyar.geojson";
		// echo $file_geojson;
		$data = file_get_contents($file_geojson);
		$geojson = json_decode($data);
		// echo var_dump($geojson);
		$nomer = 1;
		foreach ($geojson->features as $key => $value) {
			# code...
			$desa = strtoupper(trim($value->properties->DESA));
			$desa = str_replace(" ","",$desa);
			$desa = ($desa == "PADEYAN") ? "PANDEYAN":$desa;
			$desa = ($desa == "GAWAHAN") ? "GAJAHAN":$desa;
			$desa = ($desa == "GEBYOK") ? "GEBYOG":$desa;
			$desa = ($desa == "KUTHO") ? "KUTO":$desa;
			$desa = ($desa == "GANTHEN") ? "GANTEN":$desa;
			$desa = ($desa == "BOTHOK") ? "BOTOK":$desa;
			$desa = ($desa == "SELOMORO") ? "SELOROMO":$desa;
			$desa = ($desa == "GARDU") ? $desa="GERDU":$desa;
			

			$kecamatan = strtoupper(trim($value->properties->KECAMATAN));
			$kecamatan = str_replace(" ","",$kecamatan);
			$kecamatan = ($kecamatan == "JUMANTORO") ? "JUMANTONO":$kecamatan;
			// echo "<h2>".$nomer.". ".$desa." / Kec. ".$kecamatan."</h2>";
			$paths = $value->geometry->coordinates;
			$path_desa = [];
			foreach($paths[0][0] as $path){
				// echo var_dump($path);
				$lon = $path[0];
				$lon = str_replace('float(','',$lon);
				$lon = str_replace(')','',$lon);

				$lat = $path[1];
				$lat = str_replace('float(','',$lat);
				$lat = str_replace(')','',$lat);

				$path_item = array($lon,$lat);
				array_push($path_desa,$path_item);
			}
			// ".fixSQL(json_encode($path_desa,JSON_NUMERIC_CHECK))."
			$map_path = fixSQL(json_encode($path_desa,JSON_NUMERIC_CHECK));
			// echo var_dump($desas[$kecamatan][$desa]);
			$strSQL = "UPDATE tweb_wilayah tw SET tw.`path`='".$map_path."' WHERE kode='".$desas[$kecamatan][$desa]."'; \n";
			echo $strSQL;
			// echo "<pre>".json_encode($strSQL)."</pre>";
			$nomer++;
		}
	}
}
