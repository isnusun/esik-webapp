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
}
