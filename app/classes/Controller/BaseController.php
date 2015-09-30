<?php namespace Controller;

class BaseController {
	
	static $blog = [];

	public function __construct(){
		\Bootie\App::load_database();
	}

	public function ip2geo(){
		extract($_POST);

		$ip = getenv('REMOTE_ADDR');

		if( $ip != '127.0.0.1' ) {

			$blog = self::$blog;
			$now = time();

			$geoip = curl_request('http://www.telize.com/geoip/' . $ip);

			$hit = new \Model\Hit();
			$hit->city = $geoip->city;
			$hit->country = $geoip->country;
			$hit->region = $geoip->region;
			$hit->latitude = $geoip->latitude;
			$hit->longitude = $geoip->longitude;
			$hit->path = $path;
			$hit->user_id = $blog->data->id;
			$hit->user_agent = getenv('HTTP_USER_AGENT');
			$hit->created = $now;
			$hit->updated = $now;
			$hit->insert();

			return TRUE;
		}

		return FALSE;
	}	
}

