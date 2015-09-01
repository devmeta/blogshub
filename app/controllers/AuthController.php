<?php namespace App\Controllers;

use App\Models\DB as DB;


class AuthController {
	

	public function index(){
	}

	private function clear_session(){
		if(count($_SESSION)){
			foreach($_SESSION as $k=>$v){
				unset($_SESSION[$k]);
			}
		}
	}

	private function add_session($data){
		$_SESSION['user_id'] = $data['id'];
		$_SESSION['group_id'] = $data['group_id'];
		$_SESSION['username'] = $data['username'];
		$_SESSION['title'] = $data['title'];
		$_SESSION['role'] = $data['role'];
		$_SESSION['privacy_id'] = $data['privacy_id'];
		$_SESSION['privacy_icon'] = _l('Privacy_Icon_' . $_SESSION['privacy_id']);
		$_SESSION['privacy_icons'] = DB::query('select * from privacy',0);
		$_SESSION['first_name'] = strstr($data['title'],' ',true);
	}

	public function signup_preview(){

		extract($_REQUEST);

		$id = DB::query("select * from users where email = '" . $email . "'",1);
		$json = "email_not_evailable";

		return array('result'=>$json,'id'=>$id);
	}	

	public function signup(){

		extract($_REQUEST);

		$exists = DB::query("select * from users where email = '" . $email . "'",1);
		$json = "email_not_evailable";
		$id = 0;

		if(!$exists){
			$id = DB::write("insert into users set 
				title = '" . $title . "',
				email = '" . $email . "',
				pass = '" . $password . "',
				username = '" . $username . "',
				privacy_id = 2,
				created_ts = " . time() . ",
				created_at = now()"
			);

			$data = DB::query('select * from users where id = ' . $id,1);
			AuthController::add_session($data);
			$json = "email_evailable";
		} 

		return array('result'=>$json,'id'=>$id);
	}	

	public function signout(){
		//AuthController::clear_session();
		foreach($_SESSION as $k=>$v){
			unset($_SESSION[$k]);
		}

		return array("result"=>1);
	}

	public function signin(){

		extract($_REQUEST);

		$data = DB::query("select users.id, users.username, users.title, roles.name as role 
			from users 
			left join roles on roles.id = users.role_id 
			where email = '" . $email . "' and pass = '" . $password . "' 
			or username = '" . $email . "' and pass = '" . $password . "'",1);

		DB::write('update users set lastlogin_ts = ' . time() . ' where id = ' . $data['id']);
		
		$json = 0;

		if($data){
			$json = 1;
			AuthController::add_session($data);
		} 

		return array('result'=>$json);
	}	
}