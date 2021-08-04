<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program_bansos extends CI_Controller {

	function __construct(){
		parent::__construct();
		// session_start();
		$this->load->model('wilayah_model');
		$this->load->model('program_model');
		$this->load->model('lembaga_model');
		
	}
	   
  public function index($varKode = ''){
		$data['user'] = $this->session->userdata;

		$data["boxTitle"] = "Daftar Program Layanan Jaminan Sosial";
		$data["pageTitle"] = "Program Layanan Jaminan Sosial";

		$data["kecamatan"] = $this->wilayah_model->list_subwilayah(KODE_BASE,3);
		$data["desa"] = $this->wilayah_model->list_subwilayah(KODE_BASE,4);
		$data["wilayah"] = $this->wilayah_model->get_wilayah(KODE_BASE);
		$data['msg'] = "";
		
		$data['program_bansos'] = $this->program_model->program_list(1);
		$data['program_sasaran'] = $this->program_model->program_sasaran();
		$data['program_status'] = $this->program_model->program_status();

		$this->load->view('siteman/program_bansos',$data);

		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}		
		
  }
  
  function view($varID = 0,$varKode=""){
		if($varID > 0){
			
			$data['user'] = $this->session->userdata;

			$data["boxTitle"] = "Daftar Program Layanan Jaminan Sosial";
			$data["pageTitle"] = "Program Layanan Jaminan Sosial";

			$varKode = ($varKode == "") ? KODE_BASE:$varKode;
			
			if(KODE_BASE == 3372){
				if(strlen($varKode)==10){
					$varKode .= "00";
				}
			}
			
			$data["sub_wilayah"] = $this->wilayah_model->subwilayah($varKode);
			$data["wilayah"] = $this->wilayah_model->get_wilayah($varKode);
			$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
			$data["varKode"] = $varKode;
			
			$data['msg'] = "";
			
			$data['program_bansos'] = $this->program_model->program_list(1);
			$data['program_sasaran'] = $this->program_model->program_sasaran();
			$data['program_status'] = $this->program_model->program_status();
			if($varID > 0){
				$data['program'] = $this->program_model->program_load($varID);
				$data['program_id']=$varID;
				
				$data['form_action'] = site_url('program_bansos/viewsimpan');
				$data['program_peserta']= $this->program_model->program_peserta_bywilayah($varID,$data['program']['jenis'],$varKode);

				$this->load->view('siteman/program_bansos_view',$data);

			}else{
				redirect('program_bansos');
			}
		}else{
			redirect('program_bansos');
		}
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}		
	}
	
	function siapa($varID = 0,$varKode="",$varSex=''){
		if($varID > 0){
			
			$data['user'] = $this->session->userdata;

			$data["boxTitle"] = "Daftar Program Layanan Jaminan Sosial";
			$data["pageTitle"] = "Program Layanan Jaminan Sosial";

			$varKode = ($varKode == "") ? KODE_BASE:$varKode;
			
			if(KODE_BASE == 3372){
				if(strlen($varKode)==10){
					$varKode .= "00";
				}
			}
			
			$data["sub_wilayah"] = $this->wilayah_model->subwilayah($varKode);
			$data["wilayah"] = $this->wilayah_model->get_wilayah($varKode);
			$data["alamat"] = $this->wilayah_model->alamat_bc($varKode);
			$data["varKode"] = $varKode;
			
			$data['msg'] = "";
			
			$data['program_bansos'] = $this->program_model->program_list(1);
			$data['program_sasaran'] = $this->program_model->program_sasaran();
			$data['program_status'] = $this->program_model->program_status();
			if($varID > 0){
				$data['program'] = $this->program_model->program_load($varID);
				$data['program_id']=$varID;
				
				$data['form_action'] = site_url('program_bansos/viewsimpan');
				$data['program_peserta']= $this->program_model->program_peserta($varID,$data['program']['jenis'],$varKode,$varSex);

				$this->load->view('siteman/program_bansos_viewsiapa',$data);

			}else{
				redirect('program_bansos');
			}
		}else{
			redirect('program_bansos');
		}
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}		
	}
  
  function edit($varID=0){
		
		$data['user'] = $this->session->userdata;
		$data['program'] = $this->program_model->program_load($varID);
		$data['program_id']=$varID;
		$data['msg'] = "";
		$data['form_action'] = site_url('program_bansos/simpan');
		$data['program_jenis']=1;
		$data['program_sasaran'] = $this->program_model->program_sasaran();
		$data['program_status'] = $this->program_model->program_status();

		$data["boxTitle"] = "Daftar Program Layanan Jaminan Sosial";
		$data["pageTitle"] = "Program Layanan Jaminan Sosial";

		$data["kecamatan"] = $this->wilayah_model->list_subwilayah(KODE_BASE,3);
		$data["desa"] = $this->wilayah_model->list_subwilayah(KODE_BASE,4);
		$data["wilayah"] = $this->wilayah_model->get_wilayah(KODE_BASE);
		$data['lembaga'] = $this->lembaga_model->lembaga_load(0);

		$this->load->view('siteman/program_bansos_form',$data);
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}		
	}
  
	function simpan(){
		$data['user'] = $this->session->userdata;
		$_SESSION['msg'] = $this->program_model->program_simpan();
		redirect('program_bansos');
	}
}
