<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bdt extends CI_Controller {

	function index(){
		echo "Hello World";
	}
	function csv_to_array($filename='', $delimiter=','){
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;

		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			{
				if(!$header)
					$header = $row;
				else
					$data[] = array_combine($header, $row);
			}
			fclose($handle);
		}
		return $data;
	}	

	function import_rts(){
		$file_csv = FCPATH."/src/dtks/20191/DTKS_RT.csv";
		echo $file_csv;
		
	}


}
