<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authadmin extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    $this->load->model('Mst_auth_admin_model', 'mst_auth_admin');
	}
  
	public function index()	{      
		$crud = $this->generate_crud('mst_auth_admin','Authorization for Admin Page');
    $crud->display_as('id_auth','Auth Code');
    $crud->display_as('id_admin_user','User Name');
    $crud->set_relation('id_auth','mst_auth','auth_code');
    $crud->set_relation('id_admin_user','admin_users','username');
    
    $this->mTitle = 'Authorization for Admin Page';
    $this->render_crud();
	}
  
}
