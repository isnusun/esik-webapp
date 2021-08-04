<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dlmangka extends CI_Controller {

	function __construct(){
		parent::__construct();
		// session_start();
		$this->load->library('form_validation');
		$this->load->model('wilayah_model');
		
	}

  public function index($varKode=0){
		$this->output->cache(LCACHE);
		$this->load->model('skgakin_model');

		$varKode = ($varKode== 0)? KODE_BASE : $varKode;
		$tk = _tingkat_by_len_kode($varKode) + 1;
		
		
		$data["boxTitle_1"] = "Penyaringan Data";
		$data["varKode"] = $varKode;
		$data["boxTitle"] = "Sebaran Warga Gakin";
		$data["pageTitle"] = "Kesejahteraan dlm Angka";
		
		$periode = $this->skgakin_model->skgakin_periode();
		$data["periode_aktif"] = $periode["aktif"];
		$data["periode"] = $periode["list"];

		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["kode"] = $varKode;
		
		$data['skgakin'] = $this->skgakin_model->skgakin_index($varKode);
		$data['toMap'] = $this->wilayah_model->list_subwilayah($varKode,$tk);
		
    $this->load->view('publik_angka',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
  }
  
  public function pbdt($varApa='indeks',$varKode='',$varID=''){
		$this->output->cache(LCACHE);
		
		$this->load->model('pbk_model');
		$this->load->model('pbdt_model');
		
		$varKode = ($varKode == '') ? KODE_BASE : $varKode;
		$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
		/*
		if(KODE_BASE == 3372){
			if(strlen($varKode)==10){
				$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
				$varKode .= "00";
			}else{
				$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
			}
		}
		*/
		
		$data["sub_wilayah"] = $this->wilayah_model->subwilayah($varKode);
		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["tingkatan"] = _tingkat_wilayah();
		
		$data['pbk_periode'] = $this->pbk_model->pbk_periode();
		$data['pbk_kategori'] = $this->pbk_model->pbk_kategori();
		$data['pbk_param'] = $this->pbdt_model->pbdt_param();
		$data['desil'] = $this->pbdt_model->pbdt_kelas();
		$data['varKode'] = $varKode;

		
		if($varID == ''){$varID = 0;}
		
		switch ($varApa)
		{
			case "indikator":
				if($varID > 0){
					$data['indikator'] = $this->pbdt_model->pbdt_param_load($varID);
					$data['indikator_opsi'] = $this->pbdt_model->pbdt_param_opsi($varID);

					$data["pageTitle"] = "Basis Data Terpadu ".$data["wilayah"];
					$data["boxTitle"] = $data['indikator']['nama'];
					
					if($data['indikator']['jenis']==1){
						foreach($data['desil'] as $d=>$ds){
							$data["data_kelas"][$d] = $this->pbdt_model->data_by_indikator($varID,$varKode,$ds[3],$data['indikator']['jenis']);
							$data["warna"] = Gradient("000037", "0000FF", count($data["data_kelas"]));
						}
						$this->load->view('publik_pbdt_indikator',$data);
					}else{
						foreach($data['desil'] as $d=>$ds){
							$data["data_kelas"][$d] = $this->pbdt_model->data_by_indikator($varID,$varKode,$ds[3],$data['indikator']['jenis']);
							$data["warna"] = Gradient("000037", "0000FF", count($data["data_kelas"]));
						}
						$this->load->view('publik_pbdt_indikator_angka',$data);
					}					
				}
				
				break;
			default:

				$data["data_kelas"] = $this->pbdt_model->data_by_kelas($varKode);
				$data["warna"] = Gradient("000037", "0000FF", count($data["data_kelas"]));
			
				$data['pageTitle'] = "Data PBDT ".$data["wilayah"];
				$data['boxTitle'] = "Data Hasil PBDT";
				$this->load->view('publik_pbdt',$data);
		}
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
		
	}
	

  public function pbk($varApa='indeks',$varKode='',$varID=''){
		$this->output->cache(LCACHE);
		$this->load->model('pbk_model');
		$this->load->model('pbdt_model');
		
		$varKode = ($varKode == '') ? KODE_BASE : $varKode;
		if(KODE_BASE == 3372){
			if(strlen($varKode)==10){
				$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
				$varKode .= "00";
			}else{
				$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
			}
		}
		
		$data["sub_wilayah"] = $this->wilayah_model->subwilayah($varKode);
		$data["wilayah"] = $this->wilayah_model->get_alamat($varKode);
		$data["tingkatan"] = _tingkat_wilayah();
		
		$data['pbk_periode'] = $this->pbk_model->pbk_periode();
		$data['pbk_kategori'] = $this->pbk_model->pbk_kategori();
		$data['pbk_param'] = $this->pbk_model->pbk_param();
		$data['desil'] = $this->pbk_model->pbk_klasifikasi();
		$data['varKode'] = $varKode;

		
		if($varID == ''){$varID = 0;}
		
		switch ($varApa)
		{
			case "indikator":
				if($varID > 0){
					$data['indikator'] = $this->pbk_model->pbk_param_load($varID);
					$data['indikator_opsi'] = $this->pbk_model->pbk_param_opsi($varID);

					$data["pageTitle"] = "Data Lokal ".$data["wilayah"];
					$data["boxTitle"] = $data['indikator']['nama'];
					
					if($data['indikator']['jenis']==1){
						foreach($data['desil'] as $d=>$ds){
							$data["data_kelas"][$d] = $this->pbk_model->data_by_indikator($varID,$varKode,$d,$data['indikator']['jenis']);
							$data["warna"] = Gradient("000037", "0000FF", count($data["data_kelas"]));
						}
						$this->load->view('publik_pbk_indikator',$data);
					}else{
						foreach($data['desil'] as $d=>$ds){
							$data["data_kelas"][$d] = $this->pbk_model->data_by_indikator($varID,$varKode,$d,$data['indikator']['jenis']);
							$data["warna"] = Gradient("000037", "0000FF", count($data["data_kelas"]));
						}
						$this->load->view('publik_pbk_indikator_angka',$data);
					}					
				}
				
				break;
			default:

				$data["data_kelas"] = $this->pbk_model->data_by_kelas($varKode);
				$data["warna"] = Gradient("000037", "0000FF", count($data["data_kelas"]));
			
				$data['pageTitle'] = "Data Sisiran ".$data["wilayah"];
				$data['boxTitle'] = "Hasil Data Sisiran";
				$this->load->view('publik_pbk',$data);
		}
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
		
	}
	
}
