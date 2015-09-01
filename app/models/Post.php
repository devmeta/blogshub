<?php namespace App\Models;

class Post extends Model { 

	static $table = 'posts';

    public function author()
    {
        return static::belongsTo('User','user_id');
    }

    public function image()
    {
        return static::hasOne('File','parent_id');
    }

    public function images()
    {
        return static::hasMany('File','parent_id');
    }
}