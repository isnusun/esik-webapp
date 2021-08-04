<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('siteman_model');
		$this->load->model('wilayah_model');
		$this->load->model('bdt_model');
		$this->load->driver('cache');
	}

	public function index(){

	}

	function indikator(){
		
	}
}
