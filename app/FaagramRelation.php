<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class FaagramRelation extends Eloquent
{
    protected $collection = 'faagram_relations';
    protected $connection = 'mongodb';

    public function __construct($follower,$following,array $attributes = [])
    {
        parent::__construct($attributes);
        $this->follower = $follower;
        $this->following = $following;
        $this->save();
    }
}
