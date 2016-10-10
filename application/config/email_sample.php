<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Email Settings
| -------------------------------------------------------------------
| Configuration of outgoing mail server.
| */

$config['protocol'] = 'smtp';
$config['smtp_host'] = '';
$config['smtp_port'] = '25';
$config['smtp_timeout'] = '30';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;
//$config['newline'] = "\r\n";
$config['newline'] = "\n";
$config['useragent'] = 'Microsoft Outlook';

// custom values from CI Bootstrap
$config['from_email'] = "";
$config['from_name'] = "Wawan Default App";
$config['subject_prefix'] = "";

// Mailgun API (to be used in Email Client library)
$config['mailgun'] = array(
	'domain'				=> '',
	'private_api_key'		=> '',
);