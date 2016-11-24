<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library to import data from .csv files
 */
class Wawan_lib_admin {
  public function __construct() {
    $this->CI =& get_instance();
  }
  
  function variableExistAndNotEmpty($var) {
    if (isset($var) AND !empty($var)) return true;
  }
  
  function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

  function isOdd($num) { return $num % 2;}
  
  function elipsis($string, $length, $stopanywhere=false) {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length) {
        //limit hit!
        $string = substr($string,0,($length -3));
        if ($stopanywhere) {
            //stop anywhere
            $string .= '...';
        } else{
            //stop on a word.
            $string = substr($string,0,strrpos($string,' ')).'...';
        }
    }
    return $string;
}

  function current_full_url() {
    $CI =& get_instance();
    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
  }
  
  // ini untuk nimpa config captcha di CI dengan config captcha yang ada di database (jadi config captcha bisa disimpan di database)
  function retrieveCaptchaConfigurations() {
    $this->CI->load->model('Admin/Mst_configurations_model', 'mst_configurations');
    $configCaptchaDatabase = $this->CI->mst_configurations->get_many_by('groups', 'Admin Captcha');
    $configCaptchaCI = $this->CI->config->load('Admin/site', true)['captcha'];
    foreach ($configCaptchaDatabase as $data) {
      switch ($data->code) {
        case 'admcap1':
          $configCaptchaCI['enabled'] = ($data->value == 1 ? true : false);
          break;
        case 'admcap2':
          $configCaptchaCI['expire'] = $data->value;
          break;
        case 'admcap3':
          $configCaptchaCI['img_height'] = $data->value;
          break;
        case 'admcap4':
          $configCaptchaCI['img_width'] = $data->value;
          break;
        case 'admcap5':
          $configCaptchaCI['word_length'] = $data->value;
          break;
        case 'admcap6':
          $configCaptchaCI['font_size'] = $data->value;
          break;
        case 'admcap7':
          $configCaptchaCI['case_sensitive'] = $data->value;
          break;
      }
    }
    return $configCaptchaCI;
  }
    
}