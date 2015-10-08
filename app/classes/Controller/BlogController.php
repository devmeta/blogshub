<?php namespace Controller;

class BlogController extends \Controller\BaseController  {

	static $layout = "default";
		
	public function index(){
		$blog = config('blog');
		return \Bootie\App::view('blog.index',[
			'posts'	=> \Model\Post::paginate([
				'id' => 'DESC'
			],[
				'user_id' => $blog->data->id 
			],6),
			'tags'	=> self::find_all_tags(),
		]);
	}

	public function tag($tag){
		return \Bootie\App::view('blog.tags',[
			'posts'	=> self::find_by_tag($tag),
			'tags'	=> self::find_all_tags(),
			'tag'	=> $tag
		]);
	}

	public function show($slug){

		$tags_ids = [];
		$blog = config('blog');

		$entry = \Model\Post::row([
			'slug' => urldecode($slug),
			'user_id' => $blog->data->id 
		]);

		if($entry) {

			$meta = new \stdClass();
			$meta->og_title = $entry->title;
			$meta->og_description = $entry->caption;
			$posts_ids = $related = [];

			foreach($entry->tags() as $tag){
				if( isset($tag->id)){
					$tags_ids[] = $tag->id;
				}
			}

			if(count($entry->files())){
				$meta->og_image = site_url('upload/posts/std/' . $entry->files()[0]->name);
			}

			if(count($tags_ids)){
				$tag_obs = \Model\PostTag::fetch([
					'tag_id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);

				foreach($tag_obs as $tag){
					if($tag->post_id == $entry->id) continue;
					$posts_ids[] = $tag->post_id;
				}

				if(count($posts_ids)){
					$related = \Model\Post::fetch([
						'id IN(' . implode(',',array_unique($posts_ids)) . ')'
					]);
				}
			}

			$entry->hits = $entry->hits + 1;
			$entry->save();
			
			return \Bootie\App::view('blog.entry',[
				'entry'	=> $entry,
				'meta'	=> $meta,
				'related' => $related
			]);
		} 

		return \Bootie\App::view('errors.missing');
	}

	public function files($id){
		$files = [];

		if($id){
			$files = \Model\File::select('fetch','*',null,[
				'post_id' => $id
			]);
		}

		return $files;
	}

	public function find_by_tag($tag){

		$blog = config('blog');
		
		$tag_id = \Model\Tag::column([
			'tag' => $tag,
			'user_id' => $blog->data->id 
		]);

		if(is_numeric($tag_id)){

			$posts_ids = \Model\PostTag::select('fetch','post_id',null,[
				'tag_id' => $tag_id
			]);

			if(count($posts_ids)){
				$posts = \Model\Post::paginate([
					'id' => 'DESC'
				],[
					'id IN(' . implode(',',array_unique($posts_ids)) . ')'
				],6);
			}

			return $posts;
		}

		return FALSE;

	}

	public function find_all_tags(){

		$blog = config('blog');

		$posts_ids = \Model\Post::select('fetch','id',null,[
			'user_id' => $blog->data->id 
		]);

		if(count($posts_ids)){

			$tags_ids = \Model\PostTag::select('fetch','tag_id',null,[
				'post_id IN(' . implode(',',$posts_ids) . ')'
			]);

			if(count($tags_ids)){
				return \Model\Tag::fetch([
					'id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);
			}
		}

		return FALSE;
	}


	public function search(){

		global $db;

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

			$posts = $db->fetch('select posts.id, posts.caption, posts.title, posts.slug, posts.updated, posts.user_id, files.name as image 
				from posts 
				left join files on files.post_id = posts.id and files.position = 1 
				left join users on users.id = posts.user_id 
				where posts.user_id = \'' . config('blog')->data->id . '\' 
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
				else 3 end');

		}

		if(count($posts))
		{
			foreach($posts as $i => $post)
			{
				$post->updated = timespan($post->updated);
				$post->caption = words($post->caption,30);
			}
		}

		return [
			'count' => count($posts),
			'posts' => $posts
		];
	}	
}