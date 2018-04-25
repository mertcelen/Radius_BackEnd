<?php

namespace App\Http\Controllers\Api;

use App\Faagram\User;
use App\Http\Controllers\Controller;

class RecommendationController extends Controller
{
    public static function main($userId)
    {
        try{
            $faagramId = \DB::table('users')->select('faagramId')->where('id',$userId)->value('faagramId');
            $posts = User::getPosts($faagramId);
            $likes = User::getLikes($faagramId);
            $followingPosts = User::getFollowingPosts($faagramId);
            $result = array();
            $post_count = count($posts);
            if($post_count < 10){
                $result = array_merge($posts,$result);
                $remainer = 20 - $post_count;
                $likes_count = $remainer / 2;
                $result = array_merge(array_random($likes,$likes_count),$result);
                $following_count = 20 - $post_count - $likes_count;
                $result = array_merge(array_random($followingPosts,$following_count),$result);
            }else{
                $result = array_random($posts,10);
                $result = array_merge(array_random($likes,5),$result);
                $result = array_merge(array_random($followingPosts,5),$result);
            }
            return $result;
        }catch (\Exception $e){

        }

    }
}
