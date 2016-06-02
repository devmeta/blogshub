<?php namespace Controller;

class BaseController {
	
	public function __construct(){
		\Bootie\App::load_database();
	}

	public function ip2geo(){
		extract($_POST);

		$ip = getenv('REMOTE_ADDR');

		if( $ip != '127.0.0.1' ) {

			$blog = config('blog');
			$geoip = self::ip2geolocation($ip);
			$hit = new \Model\Hit();

			if($geoip)
			{
				if(isset($geoip->city))
				{
					$hit->city = $geoip->city;
				}

				if(isset($geoip->country))
				{
					$hit->country = $geoip->country;
				}

				if(isset($geoip->region))
				{
					$hit->region = $geoip->region;
				}

				if(isset($geoip->lat))
				{
					$hit->latitude = $geoip->lat;
				}

				if(isset($geoip->lon))
				{
					$hit->longitude = $geoip->lon;
				}
			}

			$hit->path = $path;
			$hit->user_id = $blog->data->id;
			$hit->user_agent = getenv('HTTP_USER_AGENT');
			$hit->created = TIME;
			$hit->updated = TIME;
			$hit->save();

			return TRUE;
		}
		return FALSE;
	}	

	private function ip2geolocation($ip)
	{
	    $apiurl = 'http://ip-api.com/json/' . $ip;
	    //$apiurl = 'http://freegeoip.net/json/' . $ip;
	    //$apiurl = 'http://www.telize.com/geoip/' . $ip;
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $apiurl);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    $data = curl_exec($ch);
	    curl_close($ch);
	 
	    return json_decode($data);
	}
}

