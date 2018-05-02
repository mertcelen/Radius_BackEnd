<?php

namespace App\Faagram;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $collection = 'faagram_user';
    protected $connection = 'mongodb';

    public function getFollowers()
    {
        $followers = Relation::where('following', $this->id);
        return $followers;
    }

    public static function add($name)
    {
        $user = new self();
        $user->name = $name;
        $user->follower = random_int(10, 100);
        $user->following = random_int(10, 100);
        $user->post_count = random_int(10, 100);
        $user->likes = random_int(50, 200);
        $user->save();
        return $user;
    }

    public static function getPosts($id)
    {
        $posts = Post::where('userId', $id)->select(['label','color','like_count'])->orderBy('like_count', 'desc')->limit(20)->get()->toArray();
        return $posts;
    }

    public static function getLikes($id)
    {
        $posts = Post::where('likes', 'all', [$id])->select(['label','color','created_at'])->orderBy('like_count', 'desc')->limit(20)->get()->toArray();
        return array_random($posts,10);
    }

    public static function getFollowingPosts($id)
    {
        $following_list = Relation::where('follower', $id)->get();
        $list = array();
        foreach ($following_list as $following_user){
            $posts = User::getPosts($following_user->following);
            $list = array_merge($posts,$list);
        }
        usort($list, function($a, $b) {
            return $b["like_count"] <=> $a["like_count"];
        });
        return array_random($list,10);
    }
}
