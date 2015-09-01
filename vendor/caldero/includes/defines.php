<?php 


//@ini_set('display_errors', 'off');
ini_set('upload_max_filesize', '100M');
ini_set('default_charset', 'utf-8');
ini_set('magic_quotes_runtime', 0);
ini_set("mbstring.internal_encoding","UTF-8");
ini_set("mbstring.func_overload",7);

/*
if (! ini_get('date.timezone') && function_exists('date_default_timezone_set')) {
	date_default_timezone_set('America/Argentina/Buenos_Aires');
}
*/

/* Correct Apache charset */
header('Content-Type: text/html; charset=utf-8');
//header('Access-Control-Allow-Origin: *');

define('PATH_BASE',		__DIR__ . '/../../../');
define('PATH_VNDR', 	PATH_BASE . 'vendor/caldero/');
define('PATH_CTRL', 	PATH_BASE . 'app/controllers/');
define('PATH_MDL', 		PATH_BASE . 'app/models/');
define('PATH_LOCALE', 	PATH_BASE . 'app/locale/');
define('PATH_VIEWS', 	PATH_BASE . 'public/views/');
define('PATH_UPLOAD',	PATH_BASE . '../blogs/public/upload/');
define('REMOTE_ADDR',$_SERVER['REMOTE_ADDR']);
define('HTTP_USER_AGENT',$_SERVER['HTTP_USER_AGENT']);
define('LOCALE_DEFAULT','es');

function get_flag(){
	$locale= isset($_SESSION['language']) ? $_SESSION['language'] : ( isset($_COOKIE['language']) ? $_COOKIE['language'] : LOCALE_DEFAULT );
	if(isset($_REQUEST['lang'])){
		if(isset($_SESSION['language'])){
			unset($_SESSION['language']);
		}
		$locale = $_REQUEST['lang'];
		$_SESSION['language'] = $locale;
	}
	return $locale;
}

$flag = get_flag();

define('LOCALE',$flag);

if(is_file( PATH_LOCALE . LOCALE . '.php')){
	include PATH_LOCALE . LOCALE . '.php';
}