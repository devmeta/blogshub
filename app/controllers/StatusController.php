<?php namespace App\Controllers;

use App\Models\DB as DB;


class StatusController {
	
	public function index(){
	}

	public function user_username(){

		extract($_REQUEST);

		$exists = DB::query("select * from users where username = '" . $username . "'",1);
		$ion = "alert-circled";
		$status = "danger";
		$message = 'username_not_available';

		if(!$exists){
			$ion = "checkmark-round";
			$status = "success";
			$message = 'username_available';
		} 

		return array('ion'=>$ion,'status'=>$status,'message'=>$message);
	}	
}