<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onenoteemail extends Admin_Controller {
  
	public function __construct()	{
		parent::__construct();
		$this->load->library('form_builder');
    $this->load->model('Trs_notes_email_to_model', 'trs_notes_email_to');
	}
  
	public function index()	{      
		$crud = $this->generate_crud('trs_notes_email_to','One Notes Email Setup');
    $crud->columns('email','active');
    $this->mTitle = 'One Notes Email Setup';
    $this->render_crud();
	}
  
}
