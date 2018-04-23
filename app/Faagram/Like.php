<?php

namespace App\Faagram;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Like extends Eloquent
{
    protected $collection = 'faagram_likes';
    protected $connection = 'mongodb';
    static $posts = array();

    public static function add($id)
    {
        $like = new self();
        $randomId = array_random(Like::$posts, 1)[0]['_id'];
        while (Relation::where('postId', $randomId)->count() > 0) {
            $randomId = array_random(Like::$posts, 1)[0]['_id'];
        }
        $like->userId = $id;
        $like->postId = $randomId;
        $like->save();
    }

    public static function boot()
    {
        Like::$posts = Post::all();
    }
}
