<?php 

function ip2geolocation($ip)
{
    //$apiurl = 'http://freegeoip.net/json/' . $ip;
    $apiurl = 'http://www.telize.com/geoip/' . $ip;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiurl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($ch);
    curl_close($ch);
 
    return json_decode($data);
}

function call_method($c,$method,$params){

	global $link;
	
	$json = $c->$method($params);

	if( $link ){
		mysql_close($link);
	}

	if( $json ){
		return json_encode($json);
	} 

	return '';
}

function _e(){

	global $lang, $flag;

	$args = func_get_args();
	$parts = array();
	foreach($args as $str){
		if(isset($lang[$str])){
			$parts[]= utf8_encode($lang[$str]);
		} else {
			$parts[]= $str;
		}
	}
	return implode(' ',$parts);
}

// translation with no utf8 encoding
function _l($str){

	global $lang, $flag;

	if(isset($lang[$str]))

		return $lang[$str];

	return $str;
}

function get_languages(){
	$langdata=array();
	$langs = dirList( dirname(__FILE__) . "/languages/", 'php' );
	if(is_array($langs[1]))
		foreach($langs[1] as $row)
			$langdata[]= str_replace(array(".php","lang_"),array("",""),$row[0]);
	return $langdata;
}


function set_language(){

	$lang = "";

	if(defined('LANGS')){

		$langs = explode(' ',LANGS);
		$lang = $langs[0];

		if(count($langs)){ // multilanguage mode
			if( isset($_REQUEST['lang'])){
				if(in_array($_REQUEST['lang'],$langs)){
					$lang = $_REQUEST['lang'];
					if(isset($_SESSION['lang'])){
						unset($_SESSION['lang']);
						$_SESSION['lang'] = $lang;
					}
				}
			} else if( ! isset($_SESSION['lang'])){	
				$blang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
				if(in_array($blang,$langs)){
					$lang = $blang;
				}
			} else {
				$lang = $_SESSION['lang'];
			}
		}
	}

	return $lang;
}

function imagecreatefromfile($imagepath=false) { 
    if(!$imagepath || !is_readable($imagepath)) return false; 
    return @imagecreatefromstring(file_get_contents($imagepath)); 
} 

function dd($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	die();
}

function __($j){
	return html_entity_decode( utf8_encode($j));
}

function __t($j){
	return trim($j);
}

function get_var($a,$b = null){
	return trim($_REQUEST[$a]) ? $_REQUEST[$a] : $b;
}

function doSection(){
	include(getFile());	
}

function getSection(){
	return strtolower($_REQUEST['permalinks'][0]);
}

function getFile(){
	$file = implode(".",array(getSection(),'php'));

	if(file_exists($file))
		return $file;

	return false;	
}


function debug($str,$force=false){
	global $config;
	if($force||$config['debug'] && in_array($_SERVER['REMOTE_ADDR'],$config['local_ips'])){
		log2file( dirname(__FILE__) . "/../../../public/debug.txt",$str);	
	}
}

function log2file($filename, $data, $mode="a+"){
   $fh = fopen($filename, $mode) or die("can't open file " . $filename);
   fwrite($fh,$data . "\n");
   fclose($fh);
}

function calculate_text_color($color) {
   $c = str_replace('#','',$color);
   $rgb[0] = hexdec(substr($c,0,2));
   $rgb[1] = hexdec(substr($c,2,2));
   $rgb[2] = hexdec(substr($c,4,2));
   if ($rgb[0]+$rgb[1]+$rgb[2]<382) {
     return '#fff';
     } else {
     return '#000';     
     }
   }
   
function invert_colour($start_colour) {
	$colour_red = hexdec(substr($start_colour, 1, 2));
	$colour_green = hexdec(substr($start_colour, 3, 2));
	$colour_blue = hexdec(substr($start_colour, 5, 2));
	
	$new_red = dechex(255 - $colour_red);
	$new_green = dechex(255  - $colour_green);
	$new_blue = dechex(255 - $colour_blue);
	
	if (strlen($new_red) == 1) {$new_red .= '0';}
	if (strlen($new_green) == 1) {$new_green .= '0';}
	if (strlen($new_blue) == 1) {$new_blue .= '0';}
	
	$new_colour = '#'.$new_red.$new_green.$new_blue;
	
	return $new_colour;
}

function filename2date( $filename, $format='U' ) {
	preg_match('/\d+/', $filename, $matches);
	$y = substr($matches[0], 0, 2);
	$m = substr($matches[0], 2, 2);
	$d = substr($matches[0], 4, 2);
	$H = substr($matches[0], 6, 2);
	$i = substr($matches[0], 8, 2);	
	//$s = substr($matches[0], 10, 2);	
	return date($format,mktime($H,$i,0,$m,$d,$y));
}

function date2es($date){
	$en = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun","Jan","Apr","Aug","Dec");
	$es = array("Lun","Mar","Mie","Jue","Vie","Sab","Dom","Ene","Abr","Ago","Dic");
	return str_replace($en, $es, $date);
}	

function timespan($ts) {
	$mins = (time() - $ts) / 60;
	$mins = round($mins);
	$x3="";
	
	if($mins==0){
		$x3="now";
	} elseif($mins > 483840){ // años
		$ratio = $mins / 483840 ;
		$d = round($ratio);
		$s = $d > 1 ? "s":"";
		$x3 = $d . "y";//.$s;
	} elseif($mins > 40319){ // meses
		$ratio = $mins / 40320 ;
		$d = round($ratio);
		$s = $d > 1 ? "es":"";
		$x3 = $d . "mo";//.$s;
	} elseif($mins > 10079 && $mins < 40319){ // semanas
		$ratio = $mins / 10080 ;
		$d = round($ratio);
		$s = $d > 1 ? "s":"";
		$x3 = $d . "w";//.$s;
	} elseif($mins > 1439 && $mins < 10079){ // dias
		$ratio = $mins / 1440 ;
		$d = round($ratio);
		$s = $d > 1 ? "s":"";
		$x3 = $d . "d";//.$s;
	} elseif($mins > 59 && $mins < 1439){ // horas
		$x3 = min2hour(round($mins));
	} else {
		$s = $mins > 1 ? "s":"";
		$x3 = $mins . "m";//.$s;
	}
	
	return __($x3);
}

function min2hour($mins) { 
    if ($mins < 0) { 
        $min = Abs($mins); 
    } else { 
        $min = $mins; 
    } 
    $H = Floor($min / 60); 
    $M = ($min - ($H * 60)) / 100; 
    $hours = $H +  $M; 
    if ($mins < 0) { 
        $hours = $hours * (-1); 
    } 
    $expl = explode(".", $hours); 
    $H = $expl[0]; 
    if (empty($expl[1])) { 
        $expl[1] = 00; 
    } 
    $M = $expl[1]; 
    if (strlen($M) < 2) { 
        $M = $M . 0; 
    }
    
    $hours = $H;
    
    if($M > 0 && $H < 3)
    	$hours.= ":" . $M;
    
    $s = ($H > 1 || $M > 1) ? "s":"";
    $hours.= "h";//.$s; 
    
    return $hours; 
} 

	
function ts2date($d){
	return mktime(intval(substr($d,6,2))
	,intval(substr($d,8,2))
	,0
	,intval(substr($d,2,2))
	,intval(substr($d,4,2))
	,intval(substr($d,0,2)));
	}