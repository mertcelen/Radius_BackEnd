<?php

namespace App\Http\Controllers\Faagram;

use App\Faagram\Like;
use App\Faagram\Post;
use App\Faagram\Relation;
use App\Faagram\User;
use App\Http\Controllers\Controller;
use App\Image;
use Faker\Factory as FakerFactory;

class AssociateController extends Controller
{
    public function fake()
    {
        $userNames = array();
        $faker = FakerFactory::create();
        for ($i = 0; $i < 100; $i++) {
            array_push($userNames, $faker->userName);
        }
        $users = array();
        $posts = array();
        $user_count = 0;
        $post_count = 0;
        $relation_count = 0;
        $like_count = 0;
        foreach ($userNames as $name) {
            $user = User::add($name);
            array_push($users, $user);
            $user_count++;
            for ($i = 0; $i < $user->post_count; $i++) {
                $post = Post::add($user->_id);
                array_push($posts, $post);
                $post_count++;
            }
        }
        foreach ($users as $user) {
            $following = array();
            for ($j = 0; $j < $user->following; $j++) {
                $random_id = $users[array_rand($users, 1)]["_id"];
                while (in_array($random_id, $following) == true) {
                    $random_id = $users[array_rand($users, 1)]["_id"];
                }
                array_push($following, $random_id);
                Relation::add($user->_id, $random_id);
                $relation_count++;
            }
            $addedPosts = array();
            for ($i = 0; $i < $user->likes; $i++) {
                $random_id = $posts[array_rand($posts, 1)]["_id"];
                while (in_array($random_id, $addedPosts) == true) {
                    $random_id = $posts[array_rand($posts, 1)]["_id"];
                }
                Like::add($user->_id, $random_id);
                $like_count++;
            }
        }
        echo "Total " . $user_count . " user added<br>";
        echo "Total " . $post_count . " post added<br>";
        echo "Total " . $relation_count . " relation added<br>";
        echo "Total " . $like_count . " like added<br>";
        return;
    }

    public function temp()
    {
        AssociateController::real(request('id'));
    }

    public static function real($id)
    {
        $sqlUser = \App\User::where('id', $id)->first();
        $posts = Image::where('userId', $id)->get();
        $user = new User();
        $user->name = $sqlUser->name;
        $user->following = random_int(10, 100);
        $user->follower = random_int(10, 100);
        $user->post_count = count($posts);
        $user->like_count = random_int(50, 200);
        $user->save();
        foreach ($posts as $post) {
            for ($i = 1; $i <= 3; $i++) {
                try {
                    $newPost = new Post();
                    $newPost->userId = $user->_id;
                    $newPost->label = $post["part" . $i]["label"];
                    $newPost->color = $post["part" . $i]["color"];
                    $newPost->like_count = 0;
                    $newPost->save();
                } catch (\Exception $e) {
                }
            }
        }
        $randomUsers = array_random(User::all()->toArray(), $user->following);
        foreach ($randomUsers as $randomUser) {
            Relation::add($user->_id, $randomUser["_id"]);
        }
        $randomPosts = array_random(Post::all()->toArray(), $user->like_count);
        foreach ($randomPosts as $randomPost) {
            Like::add($user->_id, $randomPost["_id"]);
        }
        \DB::table('users')->where('id', request('id'))->update([
            'faagramId' => $user->_id
        ]);
    }
}
