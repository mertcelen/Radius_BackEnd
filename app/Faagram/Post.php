<?php

namespace App\Faagram;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Post extends Eloquent
{
    protected $collection = 'faagram_post';
    protected $connection = 'mongodb';
    static $colors = array();
    static $types = array();

    public static function boot()
    {
        $types = DB::connection('mysql_clothes')->table('type')->get()->toArray();
        $colors = DB::connection('mysql_clothes')->table('color')->get()->toArray();
        Post::$colors = $colors;
        Post::$types = $types;
    }

    public static function add($userId)
    {
        $post = new self();
        $post->userId = $userId;
        $label = array_random(Post::$types, 1)[0]->name;
        $post->label = $label;
        $targetGender = Post::$types[array_search($post->label,array_column(Post::$types,'name'))]->gender;
        if($targetGender == 0) $targetGender = 1;
        else if($targetGender == 1) $targetGender = 2;
        else $targetGender = rand(1,2);
        $post->color = array_random(Post::$colors, 1)[0]->name;
        $post->gender = $targetGender;
        $post->likes = array();
        $post->like_count = 0;
        $post->save();
        return $post;
    }
}