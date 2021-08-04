<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

	function __construct(){
		parent::__construct();
		// session_start();
		$this->load->library('form_validation');
		$this->load->model('skgakin_model');
		$this->load->model('wilayah_model');
		
	}
	   
  public function index($varKode=0,$varPeriode=1){
		$varKode = ($varKode== 0)? KODE_BASE : $varKode;
		$tk = _tingkat_by_len_kode($varKode) + 1;
		// $this->output->enable_profiler(TRUE);
		$data['user'] = $this->session->userdata;
		
		$data["boxTitle_1"] = "Penyaringan Data";
		$data["varKode"] = $varKode;
		$data["boxTitle"] = "Distribusi SK Gakin dlm Peta";
		$data["pageTitle"] = "Visualisasi Data Capaian Kesejahteraan";
		
		$periode = $this->skgakin_model->skgakin_periode();
		$data["periode_aktif"] = $periode["aktif"];
		$data["periode"] = $periode["list"];
		$data["periode_arsip"] = $varPeriode;

		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["kode"] = $varKode;
		
		$data['skgakin'] = $this->skgakin_model->skgakin_index($varKode);
		$data['toMap'] = $this->wilayah_model->list_subwilayah($varKode,$tk);
		$this->load->view('siteman/tkpk_laporan',$data);
  }
	
}
