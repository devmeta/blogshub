<?php 

use \Bootie\App as App;

App::route('/',			['uses' => 'Controller\BlogController@index']);
App::route('/tag/([^/]+)', [ 'uses' => 'Controller\BlogController@tag']);
App::route('/ip2geo', [ 'uses' => 'Controller\BaseController@ip2geo','method' => 'post']);
App::route('/(.*)', [ 'uses' => 'Controller\BlogController@show']);