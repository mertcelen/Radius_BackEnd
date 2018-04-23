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
        $user->followers = random_int(10, 100);
        $user->following = random_int(10, 100);
        $user->post_count = random_int(10, 100);
        $user->likes = random_int(50, 200);
        $user->save();
        return $user;
    }
}
