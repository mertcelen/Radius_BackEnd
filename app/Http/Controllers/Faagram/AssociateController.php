<?php

namespace App\Http\Controllers\Faagram;

use App\FaagramPost;
use App\FaagramRelation;
use App\FaagramUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssociateController extends Controller
{
    public function init($name){
        $user = new FaagramUser($name);
        for ($i = 0; $i < $user->post_count ; $i++){
            $post = new FaagramPost($user->__id);
        }
        $currentUsers = FaagramUser::all()->toArray();
        for ($i = 0; $i < $user->follower; $i++){

        }
        return [
            'success' => [
                "message" => 'New user added.',
                "code" => 5
            ],
            'user' => $user
        ];
    }
}
