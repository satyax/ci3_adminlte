<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// NOTE: this controller inherits from MY_Controller instead of Admin_Controller,
// since no authentication is required
class Login extends MY_Controller {

  private function isUseCaptcha() {
    $this->load->model('Mst_configurations_model', 'mst_configurations');
    $use_captcha = $this->mst_configurations->get_by('config', 'Use captcha in Admin Login page');
    if ($use_captcha) $use_captcha = ($use_captcha->value == 1 ?  1 : 0); else $use_captcha = 0; 
    return $use_captcha;
  }
  
  private function isCaptchaOK($captcha) {
    // First, delete old captchas
    $expiration = time() - $this->mSiteConfig['captcha_expiration'];
    $this->db->where('captcha_time < ', $expiration)
      ->delete('mst_captcha');

    // Then see if a captcha exists:
    $sql = 'SELECT COUNT(*) AS count FROM mst_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
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
      if ($use_captcha == 1) $captcha = $this->input->post('captcha'); else $captcha = '';
      			
      if ($use_captcha == 1 && $captcha == '') {
        // config use captcha = 1 but the posted captcha is empty string, cannot login
        $this->system_message->set_error('Captcha is mandatory');
        refresh();
        
      } else {
        
        // cek capctha nya
        if (!$this->isCaptchaOK($captcha)) {
          $this->system_message->set_error('Wrong captcha!');
          refresh();
          //header('Location: '.base_url());
        }
        
        // cek login nya
			  if ($this->ion_auth->login($identity, $password, $remember)) {
				  // login succeed
				  $messages = $this->ion_auth->messages();
				  $this->system_message->set_success($messages);
				  //redirect('admin');
          
          //wawan modified ini
          if (strpos($this->session->userdata('current_url_full'), 'login') == false && !empty($this->session->userdata('current_url_full'))) {
            $reqURL = $this->session->userdata('current_url_full');
            $this->session->set_userdata([ 'current_url_full' => '' ]);
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
    $this->mViewData['captcha_folder'] = $this->mSiteConfig['captcha_folder'];
    $this->mViewData['captcha_url'] = base_url().$this->mSiteConfig['captcha_url'];
    $this->mViewData['use_captcha'] = $use_captcha;
    if ($use_captcha == 1) {
      $this->load->helper('captcha');
      $config_captcha = $this->config->item('captcha');
      $config_captcha['img_url'] = $this->mViewData['captcha_url'];
      $cap = create_captcha($config_captcha);
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
