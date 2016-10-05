<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategories extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    
    $this->load->model('Mst_categories_model', 'mst_categories');
    $this->load->model('Mst_sub_categories_model', 'mst_sub_categories');    
	}
  
	public function index()	{      
		  $crud = $this->generate_crud('mst_sub_categories','Sub Categories');
      $crud->display_as('id_categories','Category');
      $crud->set_relation('id_categories','mst_categories','category');
      
      $this->mTitle = 'Sub Categories';
      $this->render_crud();
	}
  
}
