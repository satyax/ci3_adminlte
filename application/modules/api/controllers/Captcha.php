<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Demo Controller with Swagger annotations
 * Reference: https://github.com/zircote/swagger-php/
 */
class Captcha extends API_Controller {

	/**
	 * @SWG\Get(
	 * 	path="/demo",
	 * 	tags={"demo"},
	 * 	@SWG\Response(
	 * 		response="200",
	 * 		description="Sample result",
	 * 		@SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Demo"))
	 * 	)
	 * )
	 */
	public function index_post() {
    die('yuhu');
    
		// delete dulu captcha untuk IP ini, biar nggak nyampah filenya...
    $expiration = time() - $this->mSiteConfig['captcha_expiration'];
    $captchas = $this->db->where('ip_address < ', $this->input->ip_address())->get('mst_captcha');
    foreach ($captchas as $c) {
      if (file_exists(path)) delete("target.txt");
    }
    $this->db->where('ip_address < ', $this->input->ip_address())
      ->delete('mst_captcha');
	}

}
