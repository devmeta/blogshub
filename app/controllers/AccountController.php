<?php namespace App\Controllers;

use App\Helpers\DB as DB;


class AccountController {
	
	public function index(){
	}

	
	public function show($segments){

		global $config;

		$username = $segments[1];	

		$entry = DB::query("select id,username,title,caption,location,bio,avatar,facebook,twitter,linkedin,youtube,
			from_unixtime(created_ts, '%M %Y') as created
			from users 
			where username = '{$username}'",1);

		$entry['posts_count'] = DB::query('select count(*) as value from posts where user_id = \'' . $entry['id'] . '\'',2,'value');

		return array(
			'view'	=> "account",
			'blog'	=> $config['blog'],
			'entry' => $entry
		);
	}

	public function signup_preview(){

		extract($_REQUEST);

		$exists = DB::query("select * from users where email = '" . $email . "'",1);
		$json = "email_not_evailable";
		$id = 0;

		if(!$exists){
			$id = DB::write("insert into suscribers set 
					title = '" . $title . "',
					email = '" . $email . "',
					username = '" . $username . "'"
			);
			$json = "email_evailable";
		} 

		return array('result'=>$json,'id'=>$id);
	}	
}