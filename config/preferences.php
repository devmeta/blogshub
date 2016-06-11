<?php 


// Get locale from user agent
if(isset($_REQUEST['lang']))
{
	$preference = $_REQUEST['lang'];
} 
else if(isset($_COOKIE['lang']))
{
	$preference = \Bootie\Cookie::get('lang');
}
else
{
	$preference = Locale::acceptFromHttp(getenv('HTTP_ACCEPT_LANGUAGE'));
}

if( ! isset($_COOKIE['lang']) OR isset($_REQUEST['lang']))
{
	\Bootie\Cookie::set('lang',$preference);
}

// Match preferred language to those available, defaulting to generic English
$locale = Locale::lookup(config()->languages, $preference, false, 'es');

define('LOCALE',$locale);

// Default Locale
Locale::setDefault($locale);
setlocale(LC_ALL, $locale . '.utf-8');
//putenv("LC_ALL", $locale);

// Default timezone of server
date_default_timezone_set('America/Argentina/Buenos_Aires');

