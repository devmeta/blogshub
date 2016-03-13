<?php 

use \Bootie\App as App;

App::route('/',			['uses' => 'Controller\BlogController@index']);
App::route('/search',			['uses' => 'Controller\BlogController@search','method'=>'post']);
App::route('/search',			['uses' => 'Controller\BlogController@getsearch']);
App::route('/tag/([^/]+)', [ 'uses' => 'Controller\BlogController@tag']);
App::route('/ip2geo', [ 'uses' => 'Controller\BaseController@ip2geo','method' => 'post']);
App::route('/(.*)', [ 'uses' => 'Controller\BlogController@show']);