<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    $this->load->model('Mst_auth_model', 'mst_auth');
	}
  
	public function index()	{      
		$crud = $this->generate_crud('mst_auth','Authorization Codes');
    
    $this->mTitle = 'Authorization Codes';
    $this->render_crud();
	}
  
}
