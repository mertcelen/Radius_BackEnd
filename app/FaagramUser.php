<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class FaagramUser extends Eloquent
{
    protected $collection = 'faagram_user';
    protected $connection = 'mongodb';

    public function getFollowers(){
        $followers = FaagramRelation::where('following',$this->id);
        return $followers;
    }

    public function __construct($name,array $attributes = [])
    {
        parent::__construct($attributes);
        $this->name = $name;
        $this->followers = random_int(10, 100);
        $this->following = random_int(10, 100);
        $this->post_count = random_int(10, 100);
        $this->save();
    }
}
