<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// NOTE: this controller inherits from MY_Controller instead of Admin_Controller,
// since no authentication is required
class Login extends MY_Controller {
  
  public function __construct() {
    parent::__construct();
    
    //$this->add_script('assets/dist/oshop/captcha.js',true,'head');
  }
  
  private function isUseCaptcha() {
    $use_captcha = $this->mViewData['captcha_config']['enabled'];
    return $use_captcha;
  }
  
  private function isCaptchaOK($captcha) {
    // First, delete old captchas
    $captchaExpire = $this->mViewData['captcha_config']['expire'];
    $expiration = time() - $captchaExpire; //$this->mSiteConfig['captcha_expiration'];
    $this->db->where('captcha_time < ', $expiration)
      ->delete('mst_captcha');

    // Then see if a captcha exists:
    $sql = 'SELECT COUNT(*) AS count FROM mst_captcha WHERE'.($this->mViewData['captcha_config']['case_sensitive']==1 ? 'binary' : '').' word = ? AND ip_address = ? AND captcha_time > ?';
    $binds = array($captcha, $this->input->ip_address(), $expiration);
    $query = $this->db->query($sql, $binds);
    $row = $query->row();

    if ($row->count == 0) return false; else return true;
  }
  
	/**
	 * Login page and submission
	 */
	public function index()	{    
    $this->load->model('Mst_captcha_model', 'mst_captcha');
    $use_captcha = $this->isUseCaptcha();
    
		$this->load->library('form_builder');
		$form = $this->form_builder->create_form();

		if ($form->validate()) {
      
			// passed validation
			$identity = $this->input->post('username');
			$password = $this->input->post('password');
			$remember = ($this->input->post('remember')=='on');
      
      // get captcha data
      if ($use_captcha) $captcha = $this->input->post('captcha'); else $captcha = '';
      
      if ($use_captcha && $captcha == '') {
        // config use captcha = 1 but the posted captcha is empty string, cannot login
        $this->system_message->set_error('Captcha is mandatory');
        refresh();
        
      } else {
        
        // cek capctha nya
        if ($use_captcha) {
          if (!$this->isCaptchaOK($captcha)) {
            $this->system_message->set_error('Wrong captcha!');
            refresh();
            //header('Location: '.base_url());
          }
        }
        
        // cek login nya
			  if ($this->ion_auth->login($identity, $password, $remember)) {
				  // login succeed
				  $messages = $this->ion_auth->messages();
				  $this->system_message->set_success($messages);
				  //redirect('admin');
          
          //wawan modified ini biar langsung "lari" ke url yang di maksud sebelumya
          if (strpos($this->session->userdata('current_url_full'), 'login') == false && !empty($this->session->userdata('current_url_full'))) {
            $reqURL = $this->session->userdata('current_url_full');
            $this->session->set_userdata([ 'current_url_full' => '' ]);
            if ($reqURL == base_url()) $reqURL .= 'admin';
            redirect($reqURL);
          } else {
            redirect('admin');
            //header('Location: '.base_url().'admin');
          }
			  }
			  else {
				  // login failed
				  $errors = $this->ion_auth->errors();
				  $this->system_message->set_error($errors);
				  refresh();
          //header('Location: '.base_url());
			  }
        
      }
		}
		
    // setup captcha
    // mulai setup captcha
    if ($use_captcha) {
      $this->load->helper('captcha');
      $cap = create_captcha($this->mViewData['captcha_config']);
      $cap_data = array(
        'captcha_time'  => $cap['time'],
        'ip_address'    => $this->input->ip_address(),
        'word'          => $cap['word']
      );
      $query = $this->mst_captcha->insert($cap_data);
      $this->mViewData['captcha'] = $cap;
    }
    
		// display form when no POST data, or validation failed
		$this->mViewData['body_class'] = 'login-page';
    $this->mViewData['form'] = $form;
		$this->mBodyClass = 'login-page';
		$this->render('login', 'empty');
	}  
  
}
