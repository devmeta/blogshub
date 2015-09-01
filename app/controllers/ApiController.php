<?php namespace App\Controllers;

use App\Helpers\DB as DB;

class ApiController {

	public function __construct(){
	}

	public function index(){
	}

	public function detect(){

		global $lang,$config;

		$data = array(
			'lang' => $lang,
			'sess' => array()
		); 
		$data['sess']['baseurl'] = $config['baseurl'];
		
		return $data;
	}

	public function ip2geo(){

		global $config,$local_ips;
	
		extract($_POST);

		if( ! in_array(REMOTE_ADDR,$local_ips)) {
			$geoip = ip2geolocation(REMOTE_ADDR);
			$geoip->path = $path;
			$geoip->user_id = $config['blog']['id'];
			$geoip->user_agent = HTTP_USER_AGENT;

			return DB::update('hits',0,'*',$geoip);
		}

		return [];
	}
}