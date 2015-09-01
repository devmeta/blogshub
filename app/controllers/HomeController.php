<?php namespace App\Controllers;

use App\Helpers\DB as DB;
use App\Helpers\ArrayUtils as ArrayUtils;

class HomeController {
	
	public function index(){

		global $config,$reserved;


		die("asdasd");
		if( ! in_array($config['blog']['username'],$reserved)){

			$posts = DB::query('select posts.*, files.name as image, privacy.icon 
				from posts 
				left join privacy on privacy.id = posts.privacy_id 
				left join files on files.parent_id = posts.id 
				where posts.user_id = \'' . $config['blog']['id'] . '\' 
				and posts.privacy_id = 1 
				group by posts.id 
				order by posts.updated_ts desc',0);

			if( ! count($posts)){
				return array(
					'view' => "blog-missing",
					'blogname' => $blog
				);
			}

			foreach($posts as $i => $row){
				$posts[$i]['i'] = ($i+1);
				$posts[$i]['timespan'] = timespan($row['updated_ts']);
			}

			$count = DB::query('select count(*) from users',2,'count(*)');

		} 
		
		return array(
			'view' => "blog",
			'count' => $count,
			'posts' => $posts
		);
	}
}