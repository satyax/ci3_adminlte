<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home page
 */
class Captcha extends MY_Controller {

	function refreshAdminCaptcha() {
    $this->load->library('Admin/wawan_lib_admin');
    $capcthaConfiguration = $this->wawan_lib_admin->retrieveCaptchaConfigurations();
    
    if ($capcthaConfiguration['enabled']) {
      $this->load->helper('captcha');      
      $cap = create_captcha($this->mViewData['captcha_config']);
      $cap_data = array(
        'captcha_time'  => $cap['time'],
        'ip_address'    => $this->input->ip_address(),
        'word'          => $cap['word']
      );
      $this->load->model('Admin/Mst_captcha_model', 'mst_captcha');
      $query = $this->mst_captcha->insert($cap_data);
      
      header('Content-Type: application/json');
      echo json_encode($cap);
    }
  }
  
}
