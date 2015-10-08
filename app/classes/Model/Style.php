<?php namespace Model;

class Style extends \Bootie\ORM { 
    public static $table = 'styles';
    public static $foreign_key = 'style_id';

    public static $belongs_to = array(
        'user' => '\Model\User'
    ); 
}