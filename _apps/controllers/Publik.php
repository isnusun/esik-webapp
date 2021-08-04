<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publik extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('siteman_model');
		$this->load->model('wilayah_model');
		$this->load->model('bdt_model');
		// $this->load->driver('cache');
	}

	public function index(){
		$app = $this->siteman_model->config_site();
		$data['app'] = $app;
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$indikator_rts = $this->bdt_model->bdt_indikator('rts');
		$indikator_art = $this->bdt_model->bdt_indikator('art');
		$data['indikator'] = array('art'=>$indikator_art,
			'rts'=>$indikator_rts);
		
		$data['breadcrumb'] = false;
		
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$data['desa'] = $this->wilayah_model->list_desa($app['kode_wilayah']);
		$ndesa = 0;
		foreach($data['desa'] as $kec){
			$ndesa += count($kec);
		}
		$data['num_desa'] = $ndesa;

		$data['angka'] = $this->bdt_model->angka($app['kode_wilayah']);

		$this->load->view('pubs/index.php',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function pbdt(){
		$app = $this->siteman_model->config_site();
		$data['app'] = $app;
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$indikator_rts = $this->bdt_model->bdt_indikator('rts');
		$indikator_art = $this->bdt_model->bdt_indikator('art');
		$data['indikator'] = array('art'=>$indikator_art,
			'rts'=>$indikator_rts);

		$data['breadcrumb'] = false;
		$this->load->view('pubs/pbdt/index',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function pbdt_indikator($varRts='rts'){
		$app = $this->siteman_model->config_site();
		$data['app'] = $app;
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$data['breadcrumb'] = false;
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode']:$app['kode_wilayah'];
		$data['varKode'] = $varKode;
		$indikator_rts = $this->bdt_model->bdt_indikator('rts');
		$indikator_art = $this->bdt_model->bdt_indikator('art');
		$data['indikator'] = array('art'=>$indikator_art,
			'rts'=>$indikator_rts);


		if($varRts){
			$varRts = ($varRts == '')? 'rts':$varRts;

			if(trim($varRts)=='rts'){
				$data['responden'] = 'rts';
				$data['pageDescription']= 'Indikator Rumah Tangga';
				$data['bdt_indikator'] = $indikator_rts;
				// echo var_dump($data['bdt_indikator']);
				$i=0;
				$data['num_responden'] = false;
				foreach ($data['periodes']['periode'] as $key => $value) {
					# code...
					if($i < 3){
						foreach ($data['bdt_indikator'] as $indi => $rs) {
							if($rs['jenis']=='pilihan'){
								$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_rts_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
							}
						}
					}
					$i++;
				}				
			}elseif($varRts == 'art'){
				$data['responden'] = 'art';
				$data['bdt_indikator'] = $indikator_art;
				$data['pageDescription']= 'Indikator Anggota Rumah Tangga';
				$i=0;
				$data['num_responden'] = false;
				foreach ($data['periodes']['periode'] as $key => $value) {
					# code...
					if($i < 3){
						foreach ($data['bdt_indikator'] as $indi => $rs) {
							if($rs['jenis']=='pilihan'){
								$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_art_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
							}
						}
					}
					$i++;
				}
		
			}
		}

		$this->load->view('pubs/pbdt/indikator',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function indikator_detail($varRts='rts',$varIndikator=''){
		if($varIndikator != ''){
			$app = $this->siteman_model->config_site();
			$data['app'] = $app;
			$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
			$data['breadcrumb'] = false;
			$data['periodes'] = $this->bdt_model->periodes(0);
			if(@$_REQUEST['periode']){
				$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
				$cookie = array(
					'name'   => 'pbdt_periode',
					'value'  => $_REQUEST['periode'],
					'expire' => time()+86500,
					'domain' => $_SERVER['SERVER_NAME'],
					'path'   => '/',
					'prefix' => 'siteman_',
					);
				set_cookie($cookie);
				$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
			}else{
				$periode_by_cookie = get_cookie('siteman_pbdt_periode');
				$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
			}
	
			$kode_wilayah = $data['app']['kode_wilayah'];
			$varKode = (@$varKode==0) ? $kode_wilayah:$varKode;
			$data['varKode'] = $varKode;
			$indikator_rts = $this->bdt_model->bdt_indikator('rts');
			$indikator_art = $this->bdt_model->bdt_indikator('art');
			$data['indikator'] = array('art'=>$indikator_art,
				'rts'=>$indikator_rts);	
			$data['bdt_indikator'] = $data['indikator'][$varRts];
			$data['indikator_one'] = $data['bdt_indikator'][$varIndikator];
			$data['num_responden'] = false;
			$data['responden'] = $varRts;
			$i=0;
			foreach ($data['periodes']['periode'] as $key => $value) {
				# code...
				if($i < 3){
					foreach ($data['bdt_indikator'] as $indi => $rs) {
						if($rs['jenis']=='pilihan'){
							if($varRts=='rts'){
								$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_rts_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
							}else{
								$data['num_responden'][$value['id']][$rs['nama']] = $this->bdt_model->data_art_by_indikator_by_periode_by_area($varKode,$value['id'],$rs['nama']);
							}
						}
					}
				}
				$i++;
			}
		

			$this->load->view('pubs/pbdt/indikator_detail',$data);
			if(ENVIRONMENT=='development'){
				$this->output->enable_profiler(TRUE);
			}
			
		}else{
			redirect('publik/pbdt_wilayah/');
		}
	}

	function pbdt_wilayah($varKode='0'){
		$app = $this->siteman_model->config_site();
		$data['app'] = $app;
		$data['kecamatan'] = $this->wilayah_model->list_subwilayah($app['kode_wilayah'],3);
		$data['breadcrumb'] = false;
		$data['periodes'] = $this->bdt_model->periodes(0);
		if(@$_REQUEST['periode']){
			$this->input->set_cookie('pbdt_periode',$_REQUEST['periode']);
			$cookie = array(
				'name'   => 'pbdt_periode',
				'value'  => $_REQUEST['periode'],
				'expire' => time()+86500,
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
				'prefix' => 'siteman_',
				);
			set_cookie($cookie);
			$data['periode'] = (@$_REQUEST['periode']) ? $_REQUEST['periode']:$data['periodes']['periode_aktif'];
		}else{
			$periode_by_cookie = get_cookie('siteman_pbdt_periode');
			$data['periode'] = ($periode_by_cookie) ? $periode_by_cookie:$data['periodes']['periode_aktif'];
		}

		$kode_wilayah = $data['app']['kode_wilayah'];
		$varKode = ($varKode==0) ? $kode_wilayah:$varKode;

		$indikator_rts = $this->bdt_model->bdt_indikator('rts');
		$indikator_art = $this->bdt_model->bdt_indikator('art');
		$data['indikator'] = array('art'=>$indikator_art,
			'rts'=>$indikator_rts);

		$data['tingkat_wilayah'] = _tingkat_wilayah();
		$data['varKode'] = $varKode;
		$data['sub_wilayah'] = $this->wilayah_model->subwilayah($varKode);

		$data['alamat_bc'] = $this->wilayah_model->alamat_bc($varKode);

		$data['desil'] = $this->bdt_model->desil();
		$data['data_rts'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'rts');
		$data['data_art'] = $this->bdt_model->data_desil_by_wilayah_by_periode($varKode,$data['periode'],'art');

		$data['bdt'] = $this->bdt_model->data_bdt_by_wilayah($varKode);

		$this->load->view('pubs/pbdt/index',$data);	
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
