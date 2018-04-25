<?php

namespace App\Faagram;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Relation extends Eloquent
{
    protected $collection = 'faagram_relations';
    protected $connection = 'mongodb';

    public static function add($followerId, $followingId)
    {
        $relation = new self();
        $relation->follower = $followerId;
        $relation->following = $followingId;
        $relation->save();
    }
}
