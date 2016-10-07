<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Email Settings
| -------------------------------------------------------------------
| Configuration of outgoing mail server.
| */

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'mail.oshop.co.id';
$config['smtp_port'] = '25';
$config['smtp_timeout'] = '30';
$config['smtp_user'] = 'no_reply@oshop.co.id';
$config['smtp_pass'] = 'IT_2016';
$config['charset'] = 'utf-8';
$config['mailtype'] = 'html';
$config['wordwrap'] = TRUE;
//$config['newline'] = "\r\n";
$config['newline'] = "\n";
$config['useragent'] = 'Microsoft Outlook';

// custom values from CI Bootstrap
$config['from_email'] = "no_reply@oshop.co.id";
$config['from_name'] = "Oshop Internal Apps";
$config['subject_prefix'] = "";

// Mailgun API (to be used in Email Client library)
$config['mailgun'] = array(
	'domain'				=> '',
	'private_api_key'		=> '',
);