<?php 

$local_ips = ["127.0.0.1","192.168.2.100","192.168.2.101"];
$baseurl = "";

if(in_array($_SERVER['REMOTE_ADDR'],$local_ips)){
	$baseurl = "http://blogs";
	$name = "blogs";
	$user = "root";
	$pass = 'mysqllocalpassword';
} else {
	$baseurl = "http://blogs.domain.net";
	$name = "mysqluser";
	$user = "root";
	$pass = 'mysqlserverpassword';
}

$config = [
	'mode' => "normal",
	'debug' => true,
	'baseurl' => $baseurl,
	'local_ips' => $local_ips,
	'database' => [
		'host' => "localhost",
		'user' => $user,
		'pass' => $pass,
		'name' => $name
	]
];
