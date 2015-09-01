<?php namespace App\Controllers;

use App\Models\DB as DB;


class TimelineController {
	
	public function index(){
		$entry = DB::query('select * from users where id = ' . $_SESSION['user_id'],1);
		return array('entry'=>$entry);
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