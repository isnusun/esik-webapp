<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Wscapil extends CI_Controller {

	function index(){
		echo "Hello World";
	}

	function dukcapil_by_nik(){
		$wscfg = array(
			'protocol'=>'http',
			'host'=>'103.70.79.66',
			'port'=>8282,
			'endpoint'=>'index.html'
		);
		
	}

	function dukcapil_by_nokk(){
		$wscfg = array(
			'protocol'=>'http',
			'host'=>'103.70.79.66',
			'port'=>8282,
			'endpoint'=>'index.html'
		);
	}

}
