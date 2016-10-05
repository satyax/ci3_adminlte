<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Godpad extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
	}

	public function index()
	{
		$this->render('godpad/entry_form');
	}
  
  public function newGodPad() {
    $varGet = $this->input->post();
    
    $this->load->library('wawan_lib');
    
    if ($this->wawan_lib->isOdd($varGet['i'])) {
      $html = $this->load->view('godpad/add_form_odd',$varGet,true);
    } else {
      $html = $this->load->view('godpad/add_form_even',$varGet,true);
    }
    
    echo $html;
  }

}
