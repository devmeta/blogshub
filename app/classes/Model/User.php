<?php namespace Model;

class User extends \Bootie\ORM { 
    public static $table = 'users';
	public static $foreign_key = 'user_id';

    public static $belongs_to = array(
        'style' => '\Model\Style'
    );    
}