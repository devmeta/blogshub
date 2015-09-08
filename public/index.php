<?php 

session_start();
ob_start();

include '../vendor/caldero/includes/functions.php';
include '../vendor/caldero/includes/defines.php';
include '../vendor/caldero/config.php';
include '../app/helpers/Utils.php';
include '../app/helpers/Str.php';
include '../app/helpers/ArrayUtil.php';
include '../app/helpers/DB.php';
include '../app/helpers/Model.php';
include '../app/helpers/Controller.php';

use App\Helpers\DB as DB;

$reserved = ['devmeta','social','caldero'];
$blogname = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
$link = DB::connect();
$missing_page = 'views/missing.html';
$link = false;
$request_uri = $_SERVER['REQUEST_URI'];
$segments = array_values(array_filter(explode('/',$request_uri)));
$mode = isset($segments[1]) && $segments[1] == 'views' ? 'include' : 'app';
$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || strpos($request_uri,'json');
$arch = isset($config) && isset($config['mode']) ? $config['mode'] : "normal";

$fb_image = 'http://blogs.devmeta.net/upload/posts/sd-fist-of-power-e1366397947671_rs.jpg';
$blog = DB::query('select users.*, styles.name as style 
	from users 
	left join styles on styles.id = users.style_id 
	where users.username = \'' . $blogname . '\'',1);

if( ! $blog ){
	$blog['lang'] = "es";
}

define('LOCALE',$blog['lang']);

if(is_file( PATH_LOCALE . LOCALE . '.php')){
	include PATH_LOCALE . LOCALE . '.php';
}

if( ! $blog['title'] ){
	$blog['title'] = _l('Site_Title');
	$blog['caption'] = _l('Site_Slogan');
}

$config['blog'] = $blog;

if( ! is_file( PATH_UPLOAD . 'profile/th-' . $config['blog']['avatar'] )){
	$config['blog']['avatar'] = 'default.png';
}

if(isset($segments[0]) && $segments[0] == 'posts' && isset($segments[1]) ){
	$post = DB::query('select posts.title, posts.caption, files.name as image 
		from posts 
		left join files on files.post_id = posts.id and files.position = 1 
		where posts.slug = \'' . strtolower(urldecode($segments[1])) . '\' 
		group by posts.id',1);
	
	$config['post'] = $post;
}

if ( $ajax ) {
	if( $mode == 'app' ){

		$uname = $segments[0];
		$umethod = "";

		if(strpos($uname,'-')>-1){
			$umethod = substr(strstr($uname,'-'),1);
			$uname = strstr($uname,'-',true);
		}

		$controller = $uname;
		$mname = ucfirst($uname);
		$mname2 = substr($mname,0,-1);
		$cname = $mname.'Controller';
		$view = isset($segments[1]) && strlen($segments[1]) ? strstr($segments[1],"%20",true) : 'index';
		$cfile = PATH_CTRL . $cname . '.php';
		$mfile = PATH_MDL . $mname . '.php';
		$mfile2 = PATH_MDL . $mname2 . '.php';
		$method = count($segments) > 1 ? implode('_',array_slice($segments, 1)) : ($umethod!=''?$umethod: 'index');
		$params = array_slice($segments,2);

		if(is_file( $mfile )){
			include $mfile;
		}

		if(is_file( $mfile2 )){
			include $mfile2;
		}

		if(is_file( $cfile )){

			header('Content-Type: application/json',true);

			// log user activity
			if( isset($_SESSION['user_id'])){
				$data = array(
					'activity_path' => '/' . implode('/',$segments),
					'last_ts' => time()
				);

				DB::update('users',$_SESSION['user_id'],'last_ts activity_path',$data);
			}

			include $cfile;

			$obj = "App\\Controllers\\" . $cname;
			$c = new $obj;
			
			debug('*** ' . date('H:i:s'));
			debug("method: " . $method);
			debug("view: " . $view);
			debug(json_encode($segments));

			if(method_exists($c,$method)){
				echo call_method($c,$method,$segments);
				debug($cname.'->'.$method);
			} else if(method_exists($c,$segments[1])){
				echo call_method($c,$segments[1],$segments);
				debug($cname.'->'.$segments[1]);
			} else if(method_exists($c,$view)) {
				echo call_method($c,$view,$segments);
				debug($cname.'->'.$view);
			} else if(method_exists($c,'show')) {
				echo call_method($c,'show',$segments);
				debug($cname.'->show');
			} else {
				header('Content-Type: application/json',true);
				echo json_encode(array('view'=>"missing"));

				//die("{$cname} controller is missing.");
			}

			if(count($_POST)){
				debug('POST ' . json_encode(array($_POST)));
			}
			if(count($_GET)){
				debug('GET ' . json_encode(array($_GET)));
			}

		} else {
			if( ! is_file('views' . $request_uri . '.html')){
				header('Content-Type: application/json',true);
				echo json_encode(array('view'=>"missing"));
			}
		}

	} else {
		
		debug("this page is missing : " . $request_uri);
		if( ! is_file($request_uri)){
			include $missing_page;
		} 
	}
} else {
	if( isset($segments[0]) && $segments[0] == 'api' ){

		$link = DB::connect();

		include PATH_CTRL . 'ApiController.php';

		header('Content-Type: application/json',true);

		$obj = "App\\Controllers\\ApiController";
		$method = strstr($segments[1],'?',true);
		$c = new $obj;

		echo call_method($c,$method,$segments);

	} else {
		include "views/index.php";
	}
}


http_response_code(200);