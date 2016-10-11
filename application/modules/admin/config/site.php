<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Site (by CI Bootstrap 3)
| -------------------------------------------------------------------------
| This file lets you define default values to be passed into views when calling 
| MY_Controller's render() function. 
|
| Each of them can be overrided from child controllers.
|
*/

$config['site'] = array(

	// Site name
	'name' => 'Oshop - Internal Application',

	// Default page title
	// (set empty then MY_Controller will automatically generate one based on controller / action)
	'title' => '',

	// Default meta data (name => content)
	'meta'	=> array(
		'author'		=> 'Sotya Bawana (www.sotya.net)',
		'description'	=> 'OShop - Internal Application'
	),

	// Default scripts to embed at page head / end
	'scripts' => array(
		'head'	=> array(
      'assets/js/tinymce/tinymce.min.js',
			'assets/dist/adminlte.min.js',
			'assets/dist/admin.min.js'
		),
		'foot'	=> array(
      'assets/js/selectize.js/dist/js/standalone/selectize.min.js',
      'assets/dist/oshop/godpad.js',
		),
	),

	// Default stylesheets to embed at page head
	'stylesheets' => array(
		'screen' => array(
			'assets/dist/adminlte.min.css',
			'assets/dist/admin.min.css',
      'assets/js/selectize.js/dist/css/selectize.css',
      'assets/js/selectize.js/dist/css/selectize.default.css',
      'assets/dist/sotya.css',
		)
	),

	// Multilingual settings (set empty array to disable this)
	'multilingual' => array(),

	// AdminLTE settings
	'adminlte' => array(
		'webmaster'	=> array('skin' => 'skin-red'),
		'admin'		=> array('skin' => 'skin-purple'),
		'manager'	=> array('skin' => 'skin-black'),
		'staff'		=> array('skin' => 'skin-blue')
	),

	// Menu items which support icon fonts, e.g. Font Awesome
	// (or directly update view file: /application/modules/admin/views/_partials/sidemenu.php)
	'menu' => array(
		'home' => array(
			'name'		=> 'Home',
			'url'		=> '',
			'icon'		=> 'fa fa-home',
		),
		'user' => array(
			'name'		=> 'Users',
			'url'		=> 'user',
			'icon'		=> 'fa fa-users',
			'children'  => array(
				'List'			=> 'user',
				'Create'		=> 'user/create',
				'User Groups'	=> 'user/group',
			)
		),
    'panel' => array(
      'name'    => 'Admin Panel',
      'url'    => 'panel',
      'icon'    => 'fa fa-cog',
      'children'  => array(
        'Admin Users'      => 'panel/admin_user',
        'Create Admin User'    => 'panel/admin_user_create',
        'Admin User Groups'    => 'panel/admin_user_group',
      )
    ),
    'master' => array(
      'name'    => 'Master Data',
      'url'    => 'master',
      'icon'    => 'fa fa-shopping-basket',
      'children'  => array(
        'Auth Codes' => 'auth',
        'Admin Auth'   => 'authadmin',
        'Categories'      => 'categories',
        'Sub Categories'  => 'subcategories',
        'One Notes Email'  => 'onenoteemail',
      )
    ),
    'oshop' => array(
      'name'    => 'Oshop',
      'url'    => 'oshop',
      'icon'    => 'fa fa-shopping-basket',
      'children'  => array(
        'One Note' => 'onenote',
        'Godpad'      => 'godpad',
      )
    ),
		/*'demo' => array(
			'name'		=> 'Demo',
			'url'		=> 'demo',
			'icon'		=> 'ion ion-load-b',	// use Ionicons (instead of FontAwesome)
			'children'  => array(
				'AdminLTE'			=> 'demo/adminlte',
				'Blog Posts'		=> 'demo/blog_post',
				'Blog Categories'	=> 'demo/blog_category',
				'Blog Tags'			=> 'demo/blog_tag',
				'Cover Photos'		=> 'demo/cover_photo',
				'Pagination'		=> 'demo/pagination',
				'Sortable'			=> 'demo/sortable',
				'Item 1'			=> 'demo/item/1',
				'Item 2'			=> 'demo/item/2',
				'Item 3'			=> 'demo/item/3',
			)
		),*/
		'logout' => array(
			'name'		=> 'Sign Out',
			'url'		=> 'panel/logout',
			'icon'		=> 'fa fa-sign-out',
		),
	),

	// default page when redirect non-logged-in user
	'login_url' => 'admin/login',

	// restricted pages to specific groups of users, which will affect sidemenu item as well
	// pages out of this array will have no restriction (except required admin user login)
	'page_auth' => array(
    'user'              => array('webmaster', 'admin', 'manager'),
		'user/create'				=> array('webmaster', 'admin', 'manager'),
		'user/group'				=> array('webmaster', 'admin', 'manager'),
		'panel'						=> array('webmaster'),
		'panel/admin_user'			=> array('webmaster'),
		'panel/admin_user_create'	=> array('webmaster'),
		'panel/admin_user_group'	=> array('webmaster'),
    'auth'            => array('webmaster'),
    'authadmin'            => array('webmaster'),
	),

	// Useful links to display at bottom of sidemenu (e.g. to pages outside Admin Panel)
	/*'useful_links' => array(
		array(
			'auth'		=> array('webmaster', 'admin', 'manager', 'staff'),
			'name'		=> 'Frontend Website',
			'url'		=> '',
			'target'	=> '_blank',
			'color'		=> 'text-aqua'
		),
		array(
			'auth'		=> array('webmaster', 'admin'),
			'name'		=> 'API Site',
			'url'		=> 'api',
			'target'	=> '_blank',
			'color'		=> 'text-orange'
		),
		array(
			'auth'		=> array('webmaster', 'admin', 'manager', 'staff'),
			'name'		=> 'Github Repo',
			'url'		=> CI_BOOTSTRAP_REPO,
			'target'	=> '_blank',
			'color'		=> 'text-green'
		),
	),*/
  'useful_links' => array(),  

	// For debug purpose (available only when ENVIRONMENT = 'development')
	'debug' => array(
		'view_data'		=> FALSE,	// whether to display MY_Controller's mViewData at page end
		'profiler'		=> FALSE,	// whether to display CodeIgniter's profiler at page end
	),
  
  'image_folder' => 'assets/images/',
  'image_profile_folder' => 'assets/images_profile_pic/',
  'image_profile_real_folder' => FCPATH.'assets/images_profile_pic/',
  'file_upload_real_folder' => FCPATH.'assets/files/',
  'file_upload_folder' => 'assets/files/',
  'captcha_folder' => FCPATH.'assets/captcha/',
  'captcha_url' => 'assets/captcha/',
  'captcha_expiration' => 7200,
);

$config['captcha'] = array(
  //'word'          => 'Random word',
  'img_path'      => $config['site']['captcha_folder'], //'./captcha/',
  'img_url'       => $config['site']['captcha_url'], //'http://example.com/captcha/',
  'font_path'     => '', //'./path/to/fonts/texb.ttf',
  'img_width'     => '150',
  'img_height'    => 30,
  'expiration'    => $config['site']['captcha_expiration'], //7200,
  'word_length'   => 8,
  'font_size'     => 16,
  'img_id'        => 'Imageid',
  'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

  // White background and border, black text and red grid
  'colors'        => array(
          'background' => array(255, 255, 255),
          'border' => array(255, 255, 255),
          'text' => array(0, 0, 0),
          'grid' => array(255, 40, 40)
  )
);