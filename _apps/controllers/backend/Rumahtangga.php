<?php
/*
 * Rumahtangga.php
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

class Rumahtangga extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->siteman_login->cek_login();

		$this->load->model('siteman_model');
		$this->load->model('wilayah_model');
		$this->load->model('penduduk_model');
		$this->load->model('administrasi_model');
		$this->load->model('rumahtangga_model');
		$this->load->model('bdt_model');
	}
	   
	public function index(){
		// echo "Hello World";
		$data['user'] = $this->session->userdata;
		$data['app'] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode'] : $data['user']['wilayah'];
		$data['varKode'] = $varKode;
		$data['rts'] = $this->rumahtangga_model->list_rumahtangga_by_wilayah($varKode);
		$data['pageTitle'] = $data['boxTitle'] = "Daftar Rumah Tangga";
		$this->load->view('backend/rumahtangga/index',$data);	
		if(ENVIRONMENT=='development'){
			$this->output->enable_profiler(TRUE);
		}

	}
	public function detail($varID=0){
		if($varID > 0){
			$data['user'] = $this->session->userdata;
			$data['app'] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
			$varKode = (@$_REQUEST['kode']) ? $_REQUEST['kode'] : $data['user']['wilayah'];
			$data['varKode'] = $varKode;
			$data['varID'] = $varID;
			$data['rts'] = $this->rumahtangga_model->rumahtangga_by_id($varID);
			if($data['rts']){
				$data['bdt_periode'] = $this->bdt_model->periodes();
				$data['rts_bdt'] = $this->rumahtangga_model->status_data_bdt_by_periode($varID);
				$data['pageTitle'] = $data['boxTitle'] = "Detail Rumah Tangga ".$data['rts']['rumahtangga']['nama_kepala_rumah_tangga'];
				$data['alamat_bc'] = $this->wilayah_model->alamat_bc($data['rts']['rumahtangga']['kode_wilayah']);
				$this->load->view('backend/rumahtangga/detail',$data);	
				if(ENVIRONMENT=='development'){
					$this->output->enable_profiler(TRUE);
				}
			}else{
				redirect('backend/rumahtangga');
			}
		}else{
			redirect('backend/rumahtangga');
		}
	}
}
