<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configurations extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    $this->load->model('Mst_configurations_model', 'mst_configurations');
	}
  
	public function index()	{
		$crud = $this->generate_crud('mst_configurations','Configurations');
    $crud->field_type('code', 'readonly');
    $crud->field_type('config', 'readonly');
    $crud->field_type('groups', 'readonly');
    $crud->set_wwn_default_view_per_page(100);
    
    $this->mTitle = 'Configurations';
    $this->render_crud();
	}
  
}
