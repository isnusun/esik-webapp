<?php
/*
 * Pengguna.php
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

class Pengguna extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->siteman_login->cek_login();
		$this->load->library('form_validation');
		$this->load->model('siteman_model');
		$this->load->model('user_model');
		$this->load->model('wilayah_model');
	}
	   
  	public function index(){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		if($data['user']['tingkat'] == 2){
			$data["pageTitle"] = "Pengelolaan Data Pengguna ".$data['app']['app_title'];
		}elseif($data['user']['tingkat'] >= 3){
			$data["pageTitle"] = "Pengelolaan Data Pengguna di ".$data['user']['wilayah_nama'];
		}else{
			$data["pageTitle"] = "Pengelolaan Data Pengguna ".$data['app']['app_title'];
		}
		$data['user_status'] = _status_pengguna();
		$data['user_level'] = _tingkat_pengguna();

		$data['pengguna'] = $this->user_model->pengguna_list($data['user']['wilayah'],$data['user']['id']);
		$data["boxTitle"] = "Data Daftar Pengguna Sistem";

		$this->load->view('backend/pengguna/index',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function detail($varID=0){
		if($varID > 0){
			$data['user'] = $this->session->userdata();
			$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
			$data["id"] = $varID;
			$data['user_level'] = _tingkat_pengguna();
			$data['pengguna'] = $this->user_model->pengguna_by_id($varID);
			$data["boxTitle"] = $data["pageTitle"] = $data['pengguna']['nama'] ." : Data Detail";
			$this->load->view('backend/pengguna/detail',$data);
		}
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function edit($varID = ''){
		$data['user'] = $this->session->userdata();
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);

		if($data['user']['tingkat'] == 2){
			$data["pageTitle"] = "Pengelolaan Data Pengguna ".$data['app']['app_title'];
		}elseif($data['user']['tingkat'] >= 3){
			$data["pageTitle"] = "Pengelolaan Data Pengguna di ".$data['user']['wilayah_nama'];
		}else{
			$data["pageTitle"] = "Pengelolaan Data Pengguna ".$data['app']['app_title'];
		}
		$data['user_status'] = _status_pengguna();
		$data['user_level'] = _tingkat_pengguna();

		$data['wilayah_tingkat'] = _tingkat_wilayah();		
		$data["kecamatan"] = $this->wilayah_model->list_subwilayah(KODE_BASE,3);
		$data["desa"] = $this->wilayah_model->list_subwilayah(KODE_BASE,4);

		if($varID <> ''){
			$data['id'] = $varID;
			$data['pengguna'] = $this->user_model->pengguna_by_id($varID);
			// echo var_dump($data['pengguna']);
			$data["boxTitle"] = "Pemutakhiran Data Pengguna: ".$data['pengguna']['nama'];
			$data['form_action'] = site_url('backend/pengguna/simpan');
			$this->load->view('backend/pengguna/form_add_edit',$data);
		}else{
			$data['id'] = 0;
			$data['pengguna'] = false;
			$data["boxTitle"] = "Penambahan Data Pengguna Baru";
			$data['form_action'] = site_url('backend/pengguna/simpan');
			$this->load->view('backend/pengguna/form_add_edit',$data);

		}
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}
	}

	function simpan(){
		$data = $this->user_model->pengguna_simpan();
		$_SESSION['strMsg'] = $data;
		// echo var_dump($data);
		redirect('backend/pengguna/');
		// echo var_dump($data['msg']);
	}

}
