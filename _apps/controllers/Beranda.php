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

		$app = $this->siteman_model->config_site();
		// echo var_dump($app);
		$data['app'] = $app;
		
		$data['breadcrumb'] = false;
		
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$data['desa'] = $this->wilayah_model->list_desa($app['kode_wilayah']);
		$data['varKode'] = $app['kode_wilayah'];

		$ndesa = 0;
		foreach($data['desa'] as $kec){
			$ndesa += count($kec);
		}
		$data['num_desa'] = $ndesa;
		$indikator_rts = $this->bdt_model->bdt_indikator('rts');
		$indikator_art = $this->bdt_model->bdt_indikator('art');
		$data['periodes'] = $this->bdt_model->periodes();

		$data['periode'] = $data['periodes']['periode_aktif'];
		$data['indikator'] = array('art'=>$indikator_art,
			'rts'=>$indikator_rts);

		$data['angka'] = $this->bdt_model->angka($app['kode_wilayah']);

		$this->load->view('pubs/index.php',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function hubungikami(){
		$app = $this->siteman_model->config_site();
		$data['app'] = $app;
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$indikator_rts = $this->bdt_model->bdt_indikator('rts');
		$indikator_art = $this->bdt_model->bdt_indikator('art');
		$data['indikator'] = array('art'=>$indikator_art,
			'rts'=>$indikator_rts);

		$data['breadcrumb'] = false;
		$this->load->view('pubs/kontak.php',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function hubungikami_do(){
		if($_POST){
			redirect('beranda/hubungikami');
		}
	}
}
