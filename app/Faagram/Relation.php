<?php

namespace App\Faagram;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Relation extends Eloquent
{
    protected $collection = 'faagram_relations';
    protected $connection = 'mongodb';
    static $users = array();

    public static function addFollowing($follower)
    {
        $randomId = array_random(Relation::$users, 1)[0]['_id'];
        while (Relation::where('following', $randomId)->where('follower', $follower)->count() > 0) {
            $randomId = array_random(Relation::$users, 1)[0]['_id'];
        }
        $relation = new self();
        $relation->follower = $randomId;
        $relation->following = $follower;
        $relation->save();
    }

    public static function addFollower($following)
    {
        $randomId = array_random(Relation::$users, 1)[0]['_id'];
        while (Relation::where('follower', $randomId)->where('following', $following)->count() > 0) {
            $randomId = array_random(Relation::$users, 1)[0]['_id'];
        }
        $relation = new self();
        $relation->follower = $randomId;
        $relation->following = $following;
        $relation->save();
    }

    public static function boot()
    {
        Relation::$users = User::all();
    }
}
