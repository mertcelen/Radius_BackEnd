<?php

namespace App\Faagram;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Like extends Eloquent
{
    protected $collection = 'faagram_post';
    protected $connection = 'mongodb';

    public static function add($userId, $postId)
    {
        $post = Post::where("_id",$postId)->first();
        $temp  = $post->likes;
        if($temp == null){
            $temp = array();
        }
        array_push($temp,$userId);
        $post->likes = $temp;
        $post->like_count = $post->like_count + 1;
        $post->save();
        return $post;
    }
}
