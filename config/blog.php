<?php 

$config = [
	'baseurl' => ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? 'http://blogs' : 'http://blogs.devmeta.net' ),
	'data' => \Model\User::row(
		['username' => SD]
	)
];
