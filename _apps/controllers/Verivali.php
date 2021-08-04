<?php
/*
 * Ajax.php
 * 
 * Copyright 2016 Isnu Suntoro <isnusun@isnusun-X450LCP>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Verivali extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->model('siteman_model');
		$this->load->model('pbdt_model');
		$this->load->model('kependudukan_model');
	}
	   
  public function index(){
		$data['user'] = $this->session->userdata;
		$data["pageTitle"] = "Verifikasi dan Validasi Data Rumah Tangga ".APP_TITLE;
		$data["boxTitle"] = "Form Pencarian Verifikasi dan Validasi Data Rumah Tangga";

		$data['msg'] = "";
		$data['form_action'] = site_url('verivali/form');

		$this->load->view('siteman/verivali',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}
	
	function form($varRTM = 0){
		
		/*
		 * Load formulir Verivali
		 */ 
		$data['user'] = $this->session->userdata;
		$data["pageTitle"] = "Verifikasi dan Validasi Data Rumah Tangga ".APP_TITLE;

		$data['msg'] = "";

		$data['periodes'] = $this->pbk_model->pbk_periode(0);
		$data['form_action'] = site_url('verivali/simpan');
		
		if($varRTM > 0){
			$data['rtm'] = $this->kependudukan_model->data_rtm($varRTM);
			
			$data["boxTitle"] = "VeriVali data Rumah Tangga <strong>".$data['rtm']['kepala']."</strong>/ ".$varRTM." | PBDT ".$data['rtm']['kelas_pbdt']." PBK ".$data['rtm']['kelas_pbk'];

			if($data['rtm']['kelas_pbdt'] > 0){
				$data['indikator_kategori'] = $this->pbk_model->pbk_kategori();
				$data['indikator_item'] = $this->pbdt_model->pbdt_param();
				foreach($data['periodes']['periode'] as $key=>$item){
					$data['indikator_data'][$item['id']] = $this->pbdt_model->pbdt_by_responden($varRTM,$item['id']);
				}
				$this->load->view('siteman/verivali_form_pbdt',$data);	
				
			}else{
				$data['indikator_kategori'] = $this->pbk_model->pbk_kategori();
				$data['indikator_item'] = $this->pbk_model->pbk_param();
				foreach($data['periodes']['periode'] as $key=>$item){
					$data['indikator_data'][$item['id']] = $this->pbk_model->pbk_by_responden($varRTM,$item['id']);
				}

				$this->load->view('siteman/verivali_form_pbk',$data);	
			}
		}else{
			if(@$_POST['rtm_no']){
				$data['rtm'] = $this->kependudukan_model->data_rtm($_POST['rtm_no']);
				$data["boxTitle"] = "VeriVali data Rumah Tangga <strong>".$data['rtm']['kepala']."</strong>/".$_POST['rtm_no'];

				if(strtolower(trim($_POST['datane'])) == "pbdt"){
					$data['indikator_kategori'] = $this->pbk_model->pbk_kategori();
					$data['indikator_item'] = $this->pbdt_model->pbdt_param();
					foreach($data['periodes']['periode'] as $key=>$item){
						$data['indikator_data'][$item['id']] = $this->pbdt_model->pbdt_by_responden($_POST['rtm_no'],$item['id']);
					}
					$this->load->view('siteman/verivali_form_pbdt',$data);	
				}else{
					$data['indikator_kategori'] = $this->pbk_model->pbk_kategori();
					$data['indikator_item'] = $this->pbk_model->pbk_param();
					foreach($data['periodes']['periode'] as $key=>$item){
						$data['indikator_data'][$item['id']] = $this->pbk_model->pbk_by_responden($_POST['rtm_no'],$item['id']);
					}

					$this->load->view('siteman/verivali_form_pbk',$data);	
				}
			}else{
				redirect('verivali');
			}
		}


		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}
	
	function simpan(){
/*		
		echo var_dump($_POST);
*/
		if(strtolower(trim($_POST['data_to'])) == "pbdt"){
			$hasil = $this->pbdt_model->pbdt_proses_verivali($_POST);
		}else{
			$hasil = $this->pbk_model->pbk_proses_verivali($_POST);
		}
//		echo $hasil;
		if($hasil=="OK"){
			redirect('verivali/'.$_POST['responden_id']);
		}

	}
}
