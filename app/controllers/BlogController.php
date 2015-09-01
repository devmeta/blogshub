<?php namespace App\Controllers;

use App\Models\DB as DB;
use App\Models\ArrayUtils as ArrayUtils;

class BlogController {
	
	public function index(){

		global $sd,$data;

		$posts = DB::query('select posts.*, posts_privacy.icon 
			from posts 
			left join posts_privacy on posts_privacy.id = posts.privacy_id 
			where posts.user_id = ' . $data['user_id'] . ' 
			and posts.privacy_id = 1 
			order by posts.updated_ts desc',0);

		foreach($posts as $i => $row){
			$posts[$i]['i'] = ($i+1);
			$posts[$i]['timespan'] = timespan($row['updated_ts']);
		}

		$count = DB::query('select count(*) from users',2,'count(*)');

		return array(
			'count' => $count,
			'posts' => $posts
		);
	}
}