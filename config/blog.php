<?php 

$config = [
	'data' => \Model\User::row(
		['username' => SD]
	) ?: (object) [
		'id' => 0,
		'title' => locale('missing_blog'),
		'caption' => locale('missing_blog_caption'),
		'username' => 'missing_blog',
		'icon' => 'ion-speakerphone',
		'style' => (object) [ 'name' => 'silver' ]
	]
];

