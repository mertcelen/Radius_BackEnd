<?php

namespace App\Http\Controllers\Api;

use App\Faagram\User;
use App\Http\Controllers\Controller;

class RecommendationController extends Controller
{
    public static function main($userId)
    {
        try{
            $user = \App\User::where('id',$userId)->first();
            $preferences = explode(',',$user->values);
            $faagramId = $user->faagramId;
            $posts = User::getPosts($faagramId);
            $likes = User::getLikes($faagramId);
            $followingPosts = User::getFollowingPosts($faagramId);
            $desiredPost = ((Integer)$preferences[0]/5);
            $desiredLike = ((Integer)$preferences[1]/5);
            $desiredFollowing = ((Integer)$preferences[2]/5);
            $result = array();
            $post_count = count($posts);
            if($post_count < $desiredPost){
                $result = array_merge($posts,$result);
                $remainer = 20 - $post_count;
                $likes_count = $remainer / ($desiredLike + $desiredFollowing) * $desiredLike;
                $result = array_merge(array_random($likes,$likes_count),$result);
                $following_count = 20 - $post_count - $likes_count;
                $result = array_merge(array_random($followingPosts,$following_count),$result);
            }else{
                $result = array_random($posts,$desiredPost);
                $result = array_merge(array_random($likes,$desiredLike),$result);
                $result = array_merge(array_random($followingPosts,$desiredFollowing),$result);
            }
            return $result;
        }catch (\Exception $e){
        }
    }
}
