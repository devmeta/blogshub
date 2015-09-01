<?php 

//$setarch = 'ajax';

$local_ips = ["127.0.0.1","192.168.2.100","192.168.2.101"];
$baseurl = "";

if(in_array($_SERVER['REMOTE_ADDR'],$local_ips)){
	$baseurl = "http://blogs";
	$user = "root";
	$name = "blogs";
	$pass = 'coala090';
} else {
	$baseurl = "http://blogs.devmeta.net";
	$user = "root";
	$name = "blogs";
	$pass = 'c9a0db68ef9dfa33';
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
