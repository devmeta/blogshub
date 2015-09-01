<?php namespace App\Controllers;

use App\Helpers\DB as DB;
use App\Models\Str as Str;
use App\Models\Post as Post;

class TagsController {

	public function show($segments){

		global $config,$reserved;

		if( ! in_array($config['blog']['username'],$reserved)){

			$tag = urldecode($segments[1]);

			$posts = DB::query('select posts.*, files.name as image, privacy.icon 
				from posts_tags 
				left join posts on posts.id = posts_tags.post_id 
				left join tags on tags.id = posts_tags.tag_id 
				left join privacy on privacy.id = posts.privacy_id 
				left join files on files.post_id = posts.id and files.position = 1 
				left join users on users.id = posts.user_id 
				where posts.user_id = \'' . $config['blog']['id'] . '\' 
				and posts.lang = users.lang  
				and tags.tag = \'' . $tag . '\' 
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
			'view' => "tags",
			'tag'	=> $tag,
			'count' => $count,
			'blog'	=> $config['blog'],
			'posts' => $posts
		);
	}
}