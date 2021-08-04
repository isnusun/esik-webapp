<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siteman extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('siteman_login');
		$this->load->model('siteman_model');
	}
	   
  public function index(){

	$data['user'] = $this->session->userdata;
	$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
	$data["pageTitle"] = "Selamat Datang";
	$this->load->view('siteman/siteman_dashboard',$data);
	if(ENVIRONMENT=='development'){
		$this->output->enable_profiler(TRUE);
	}
  }
  public function login(){
		
		$this->load->library('recaptcha');
		
		$data["form_action"] = site_url('siteman/auth');
		$data["pageTitle"] = "Halaman Authentikasi";
		$data["app"] = $this->siteman_model->config_site($_SERVER['SERVER_NAME']);
		// echo var_dump($data["app"]);
		$data['widget'] = $this->recaptcha->getWidget();
		$data['script'] = $this->recaptcha->getScriptTag();
    
		$this->load->view('siteman/login',$data);
	}

  	public function logout(){
		$this->siteman_login->logout();
	}
  
  	public function auth(){

		$this->load->library('recaptcha');
		$recaptcha = $this->input->post('g-recaptcha-response');
		if (!empty($recaptcha)) {
			$response = $this->recaptcha->verifyResponse($recaptcha);
			if (isset($response['success']) and $response['success'] === true) {

				// echo "You got it!";

				$this->form_validation->set_rules('username','Username','trim|required|xss_clean');
				$this->form_validation->set_rules('password','Password','trim|required');
				if ($this->input->post('submit')) {

					if($this->form_validation->run() == false) {
						$username = $this->input->post('username');
						$password = $this->input->post('password');
						$hasil = $this->siteman_login->login($username,$password, site_url('siteman'), site_url('login'));
						echo var_dump($hasil);
						// redirect('siteman');
						// 
					}else{
						$this->session->set_flashdata('formValidationError',  validation_errors('<p class="error">', '</p>'));
						// redirect('siteman');
						// echo "Dal dul del";
					}
				}

			}
		}else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			// echo "DISNI";
			$hasil = $this->siteman_login->login($username,$password, site_url('siteman'), site_url('login'));
			echo var_dump($hasil);
			// redirect('siteman');
		}
	}
}
