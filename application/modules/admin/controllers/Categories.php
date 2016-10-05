<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    $this->load->model('Mst_categories_model', 'mst_categories');
	}
  
	public function index()	{      
		$crud = $this->generate_crud('mst_categories','Categories');
    $crud->columns('category');
    
    $this->mTitle = 'Categories';
    $this->render_crud();
	}
  
}
