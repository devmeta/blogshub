<?php namespace App\Controllers;

use App\Models\DB as DB;


class NewsletterController {
	
	public function add(){

		$email = $_REQUEST['email'];
		$exists = DB::query("select * from suscribers where email = '" . $email . "'",1);
		$json = "already_suscribed";
		if(!$exists){
			DB::write("insert into suscribers set email = '" . $email . "'");
			$json = "success";
		} 

		return array('result'=>$json);
	}	
}