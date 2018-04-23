<?php

namespace App\Http\Controllers\Faagram;

use App\Faagram\Post;
use App\Faagram\Relation;
use App\Faagram\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssociateController extends Controller
{
    public function init()
    {
        $name = request('name');
        $user = User::add($name);
        for ($i = 0; $i < $user->post_count; $i++) {
            Post::add($user->_id);
        }
        return [
            'success' => [
                "message" => 'New user added.',
                "code" => 5
            ],
            'user' => $user
        ];
    }

    public function relations()
    {
        $user = User::find(request('id'));
        $currentUsers = User::all()->toArray();
        for ($i = 0; $i < $user->follower; $i++) {
            Relation::addFollower($user->id);
        }
        for ($i = 0; $i < $user->following; $i++) {
            Relation::addFollowing($user->id);
        }
    }

    public function likes()
    {
        $user = User::find(request('id'));
        for ($i = 0; $i < $user->likes; $i++) {
            Post::add($user->_id);
        }
    }
}
