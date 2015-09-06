<?php namespace App\Controllers;

use App\Helpers\DB as DB;
use App\Models\Str as Str;
use App\Models\Post as Post;

class PostsController {

	public function index(){

		global $config,$reserved;

		if( ! in_array($config['blog']['username'],$reserved)){

			$posts = DB::query('select posts.*, files.name as image, privacy.icon 
				from posts 
				left join privacy on privacy.id = posts.privacy_id 
				left join files on files.post_id = posts.id and files.position = 1 
				left join users on users.id = posts.user_id 
				where posts.user_id = \'' . $config['blog']['id'] . '\' 
				and posts.lang = users.lang  
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
			'view' => "posts",
			'count' => $count,
			'blog'	=> $config['blog'],
			'posts' => $posts
		);
	}

	public function show($segments){

		global $config;

		$entry = DB::query('select posts.id, posts.slug, posts.title, posts.updated_ts, posts.user_id, posts.caption, posts.content, users.disqus, from_unixtime(posts.updated_ts, \'%Y %M %d %H:%i\') as date_updated, users.title as user   
			from posts 
			left join users on users.id = posts.user_id
			where posts.slug = \'' . urldecode($segments[1]) . '\'
			and posts.user_id = ' . $config['blog']['id'],1);

		if($entry) {

			$entry['content'] = str_replace("/upload/",$config['baseurl'] . "/upload/",$entry['content']);
			//$entry['created'] = date('Y, m d H:i',$entry['created_ts']);

			$files = DB::query("select name as image  
				from files 
				where post_id = " . $entry['id'] . " 
				order by position",0);

			$more = DB::query('select posts.title, posts.created_ts, posts.updated_ts, posts.slug, posts.hits, posts.caption, users.title as user, files.name as image 
				from posts 
				left join files on files.post_id = posts.id and files.position = 1
				left join users on users.id = posts.user_id
				where posts.title <> \'\' 
				and posts.id <> ' . $entry['id'] . ' 
				and posts.user_id = ' . $entry['user_id'] . ' 
				and posts.lang = users.lang  
				group by posts.id
				order by hits desc 
				limit 4',0);

			$tags = DB::query('select tags.tag 
				from tags 
				left join posts_tags on posts_tags.tag_id = tags.id 
				where posts_tags.post_id = ' . $entry['id'],
				4,'tag');

			foreach($more as $i => $row){
				$more[$i]['i'] = ($i+1);
				$more[$i]['timespan'] = timespan($row['updated_ts']);
			}
			
			$exists = DB::query("select id from hits where ip ='" . $_SERVER['REMOTE_ADDR'] . "' and path = '/posts/" . $entry['slug'] . "' and user_id = " . $config['blog']['id'] . " limit 1",2,'id');

			//debug("select id from hits where ip ='" . $_SERVER['REMOTE_ADDR'] . "' and path = '/posts/" . $entry['slug'] . "' and user_id = " . $config['blog']['id'] . " limit 1");

			if( ! $exists ){
				DB::write('update posts set hits = hits + 1 where id =  ' . $entry['id']);
			}

			return array(
				'view'	=> "post",
				'entry'	=> $entry,
				'files'	=> $files,
				'tags'	=> $tags,
				'blog'	=> $config['blog'],
				'more' => $more
			);
		} 

		return array(
			'view'	=> "missing"
		);
	}

	public function search(){

		global $config;

		extract($_POST);

		$posts = [];

		if( isset($words) && strlen($words) > 3){

			$keys = array_filter(array_values(explode(" ",$words)));
			$where = [];

			if(count($keys) > 10){
				$keys = array_slice($keys, 0, 10);
			}

			foreach($keys as $key){
				if(strlen($key) > 2){
					$where[]= " posts.title like '%" . utf8_decode($key) . "%' ";
					$where[]= " posts.caption like '%" . utf8_decode($key) . "%' ";
					$where[]= " posts.content like '%" . utf8_decode($key) . "%' ";
				}
			}

			$literal = utf8_decode(implode(' ',$keys));

			$ors = implode(' or ', $where);

			$posts = DB::query('select posts.id, posts.lang, posts.title, posts.slug, posts.updated_ts, posts.user_id, files.name as image 
				from posts 
				left join files on files.post_id = posts.id and files.position = 1 
				left join users on users.id = posts.user_id 
				where posts.user_id = \'' . $config['blog']['id'] . '\' 
				and posts.lang = users.lang  
				and posts.privacy_id = 1 
				and (' . $ors . ') 
				group by posts.id 
				order by case 
				when posts.title like \'' . $literal . ' %\' then 0
				when posts.title like \'' . $literal . ' %\' then 1
				when posts.title like \'% ' . $literal . ' %\' then 2
				when posts.caption like \'' . $literal . ' %\' then 0
				when posts.caption like \'' . $literal . ' %\' then 1
				when posts.caption like \'% ' . $literal . ' %\' then 2
				when posts.content like \'' . $literal . ' %\' then 0
				when posts.content like \'' . $literal . ' %\' then 1
				when posts.content like \'% ' . $literal . ' %\' then 2
				else 3 end',0);

		}

		return array(
			'count' => count($posts),
			'posts' => $posts
		);		
	}


	public function tags(){
		extract($_POST);

		if(isset($post_id)){
			return DB::query('select tags.tag 
				from tags 
				left join posts_tags on posts_tags.tag_id = tags.id 
				where posts_tags.post_id = ' . $post_id,
				4,'tag');
		}
		
		return array();
	}	
}