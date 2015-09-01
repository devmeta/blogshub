<?php namespace App\Controllers;

use App\Models\DB as DB;

class AdminController {
	
	public function __construct(){
		if(!isset($_SESSION['user_id'])){
			die(header("Location: /login"));
		}
	}

	public function index(){
		$now = time();
		$users = DB::query('select * from users order by last_ts desc limit 5',0);
		$users_count = DB::query('select count(*) as total from users',2,'total');
		$posts = DB::query('select posts.*, posts_privacy.icon 
			from posts 
			left join posts_privacy on posts_privacy.id = posts.privacy_id 
			order by posts.updated_ts desc limit 5',0);
		$posts_count = DB::query('select count(*) as total from posts',2,'total');


		foreach($users as $i => $row){
			$users[$i]['timespan'] = timespan($row['last_ts']);
			$users[$i]['pass'] = "********";
		}

		foreach($posts as $i => $row){
			$posts[$i]['timespan'] = timespan($row['updated_ts']);
		}
		
		return array(
			'posts_count' => $posts_count,
			'users_count' => $users_count,
			'posts' => $posts,
			'users' => $users
		);
	}
	
	public function users(){

		$users = DB::query('select * from users order by lastlogin_ts desc',0);

		foreach($users as $i => $row){
			$users[$i]['timespan'] = timespan($row['created_ts']);
		}

		return array(
			'users' => $users
		);
	}

	public function group(){

		extract($_POST);
		$entry = array();
		$users = DB::query('select id, title from users order by title',0);

		if($id){
			$entry = DB::query('select * from groups where id = ' . $id ,1);
			$entry['timespan'] = timespan($entry['created_ts']);
		}

		return array(
			'users' => $users,
			'entry' => $entry
		);
	}	

	public function groups(){

		$groups = DB::query('select groups.*, users.title as user 
			from groups 
			left join users on users.id = groups.user_id 
			order by groups.id desc',0);

		foreach($groups as $i => $row){
			$groups[$i]['timespan'] = timespan($row['created_ts']);
		}

		return array(
			'groups' => $groups
		);
	}	

	public function group_map(){

		extract($_POST);

		$entry = array();
		$users = array();
		$devices = array();

		if($id){
			$devices = DB::query('select devices.id, devices.title 
				from devices_groups 
				left join devices on devices.id = devices_groups.device_id 
				where devices_groups.group_id = ' . $_SESSION['group_id'] . ' 
				order by devices.id desc limit 5',0);

			$users = DB::query('select users.id, users.title 
				from users_groups 
				left join users on users.id = users_groups.user_id 
				where users_groups.user_id = ' . $_SESSION['user_id'] . ' 
				and users_groups.group_id = ' . $_SESSION['group_id'] . ' 
				order by users.id desc limit 5',0);

			$entry = DB::query('select * from groups where id = ' . $id ,1);
			$entry['timespan'] = timespan($entry['created_ts']);
		}

		return array(
			'devices' => $devices,
			'users' => $users,
			'entry' => $entry
		);
	}	

	public function post($segments){

		extract($_POST);

		if(isset($id) && $id){
			$segments[2] = $id;
		}
		
		$entry = array();
		$entry = DB::query('select * from posts where id = ' . $segments[2] ,1);
		
		if($entry){
			$entry['timespan'] = timespan($entry['created_ts']);

			return array(
				'view' => 'admin/post',
				'entry' => $entry
			);
		}

		return array(
			'view'	=> "missing"
		);
	}	

	public function posts(){

		$posts = DB::query('select posts.*, posts_privacy.icon 
			from posts 
			left join posts_privacy on posts_privacy.id = posts.privacy_id 
			order by posts.updated_ts desc',0);

		foreach($posts as $i => $row){
			$posts[$i]['timespan'] = timespan($row['created_ts']);
		}

		return array(
			'posts' => $posts
		);
	}			

	public function device(){

		extract($_POST);
		$entry = array();
		$groups = DB::query('select groups.id, concat(groups.title,\' (\',users.title,\')\') as title 
			from groups 
			left join users on users.id = groups.user_id 
			order by groups.id desc',0);

		if($id){
			$entry = DB::query('select * from devices where id = ' . $id ,1);
			$entry['timespan'] = timespan($entry['created_ts']);
		}

		return array(
			'groups' => $groups,
			'entry' => $entry
		);
	}	

	public function devices(){

		$devices = DB::query('select devices.*, 
			groups.title as group_title, 
			users.title as user, 
			max(tracking.created_ts) as last_activity
			from tracking 
			left join devices on tracking.device_id = devices.id 
			left join groups on groups.id = devices.group_id 
			left join users on users.id = groups.user_id 
			group by tracking.device_id 
			order by last_activity desc',0);

		foreach($devices as $i => $row){
			$devices[$i]['timespan'] = timespan($row['created_ts']);
			$devices[$i]['last_activity'] = timespan($row['last_activity']);
		}

		return array(
			'devices' => $devices
		);
	}	

	public function track(){

		extract($_POST);
		$entry = array();

		if($id){
			$entry = DB::query('select * from devices where id = ' . $id ,1);
			$entry['timespan'] = timespan($entry['created_ts']);
		}

		return array(
			'entry' => $entry,
			'history' => AdminController::track_history(),
			'velocity' => AdminController::track_velocity()
		);
	}	

	public function track_history(){

		extract($_POST);

		$entries = array();
		$entry = array();
		$pg = 1;

		if(isset($p)){
			$pg = $p;
		}

		$pagelimit = 10;
		$offset = floor(($pg-1) * 10);

		if($id){
			$entry = DB::query('select * from devices where id = ' . $id,1);
			$entries = DB::query('select tracking.*, devices.title 
				from tracking 
				left join devices on devices.id = tracking.device_id 
				where tracking.device_id = ' . $id . ' 
				order by tracking.id desc 
				limit ' . $offset . ',' . $pagelimit,0);

			$count = DB::query('select count(*) as total 
				from tracking 
				left join devices on devices.id = tracking.device_id 
				where tracking.device_id = ' . $id,2,'total');
			$count = ceil($count/$pagelimit);

			if($entries){
				foreach($entries as $i=>$row){
					$entries[$i]['timespan'] = timespan($row['created_ts']);
				}
			}
		}

		return array(
			'p' => $pg,
			'count' => $count,
			'entry' => $entry,
			'entries' => $entries
		);
	}

	public function track_velocity(){

		extract($_POST);

		$entries = array();
		$entry = array();
		$morrisdata = array();
		$pg = 1;

		if(isset($p)){
			$pg = $p;
		}

		$pagelimit = 20;
		$offset = floor(($pg-1) * 20);

		if($id){
			$entry = DB::query('select * from devices where id = ' . $id,1);
			$entries = DB::query('select tracking.vel,  tracking.created_ts 
				from tracking 
				where tracking.device_id = ' . $id . ' 
				order by id desc 
				limit ' . $offset . ',' . $pagelimit,0);

			$count = DB::query('select count(*) as total 
				from tracking 
				where tracking.device_id = ' . $id,2,'total');
			$count = ceil($count/$pagelimit);


			if($entries){
				foreach($entries as $i=>$row){
					$entries[$i]['timespan'] = date('d H:i:s',$row['created_ts']);
					$morrisdata[] = array(
						'period' => date('d H:i:s',$row['created_ts']),
						'velocity' => $row['vel']
					);
				}
			}
		}

		return array(
			'p' => $pg,
			'count' => $count,
			'entry' => $entry,
			'entries' => $entries,
			'morrisdata' => $morrisdata
		);
	}	


	public function track_distance(){

		extract($_POST);

		$entries = array();
		$entry = array();
		$pg = 1;

		if(isset($p)){
			$pg = $p;
		}

		$offset = floor(($pg-1) * 20);

		if($id){
			$entry = DB::query('select * from devices where id = ' . $id,1);
			$entries = DB::query('select tracking.dist,  tracking.created_ts 
				from tracking 
				where tracking.device_id = ' . $id . ' 
				order by id desc 
				limit ' . $offset . ',20',0);

			if($entries){
				foreach($entries as $i=>$row){
					$entries[$i]['timespan'] = date('d H:i:s',$row['created_ts']);
				}
			}
		}

		return array(
			'p' => $pg,
			'entry' => $entry,
			'entries' => $entries
		);
	}		
}