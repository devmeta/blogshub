<?php namespace Controller;

class BlogController extends \Controller\BaseController {

	static $layout = "default";
		
	public function index(){
		$blog = config('blog');

		if($blog)
		{

			$posts = \Model\Post::fetch([
				'user_id' => $blog->data->id 
			]);

			/* all tags */
			foreach($posts as $post){
				$posts_ids[] = $post->id;
			}

			if(count($posts_ids)){
				$tag_obs = \Model\PostTag::fetch([
					'post_id IN(' . implode(',',$posts_ids) . ')'
				]);

				foreach($tag_obs as $tag){
					$tags_ids[] = $tag->tag_id;
				}
			}

			if(count($tags_ids)){
				$tags = \Model\Tag::fetch([
					'id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);
			}


			return \Bootie\App::view('blog.index',[
				'posts'	=> $posts,
				'tags'	=> $tags				
			]);
		}

		return \Bootie\App::view('errors.missing');
	}

	public function tag($path,$tag){
		$blog = config('blog');
		$posts_ids = $tag_obs = $tags_ids = $tags =  [];

		$tag_id = \Model\Tag::column([
			'tag' => $tag
		]);

		if(is_numeric($tag_id)){

			/* find posts by tag */

			$tag_obs = \Model\PostTag::fetch([
				'tag_id' => $tag_id
			]);

			foreach($tag_obs as $tag2){
				$posts_ids[] = $tag2->post_id;
			}

			if(count($posts_ids)){
				$posts = \Model\Post::fetch([
					'id IN(' . implode(',',array_unique($posts_ids)) . ')'
				]);
			}


			$posts_all = \Model\Post::fetch([
				'user_id' => $blog->data->id 
			]);

			/* all tags */
			foreach($posts_all as $post2){
				$posts_ids[] = $post2->id;
			}

			if(count($posts_ids)){
				$tag_obs = \Model\PostTag::fetch([
					'post_id IN(' . implode(',',$posts_ids) . ')'
				]);

				foreach($tag_obs as $tag2){
					$tags_ids[] = $tag2->tag_id;
				}
			}

			if(count($tags_ids)){
				$tags = \Model\Tag::fetch([
					'id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);
			}

			return \Bootie\App::view('blog.tags',[
				'posts'	=> $posts,
				'tags'	=> $tags,
				'tag'	=> $tag
			]);
		}

		return \Bootie\App::view('errors.missing');			
	}

	public function show($path,$slug){

		$tags_ids = [];
		$blog = config('blog');

		$entry = \Model\Post::row([
			'slug' => urldecode($slug),
			'user_id' => $blog->data->id
		]);

		if($entry) {

			$posts_ids = $related = [];

			foreach($entry->tags() as $tag){
				if( isset($tag->id)){
					$tags_ids[] = $tag->id;
				}
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

			$entry->hits = $entry->hits+1;
			$entry->save();
			
			return \Bootie\App::view('blog.entry',[
				'entry'	=> $entry,
				'related' => $related
			]);
		} 

		return \Bootie\App::view('errors.missing');
	}

	public function files($path,$id){
		$files = [];

		if($id){
			$files = \Model\File::select('fetch','*',null,[
				'post_id' => $id
			]);
		}

		return $files;
	}	
}