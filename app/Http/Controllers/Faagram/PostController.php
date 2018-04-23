<?php

namespace App\Http\Controllers\Faagram;

use App\Post;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * @api {post} /api/faagram/post/add Add Faagram Post
     * @apiName AddFaagramPost
     * @apiGroup Faagram
     *
     * @apiParam {String} userId User' id.
     * @apiParam {String} color Post' Image color.
     * @apiParam {String} label Post' Image labels.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function add()
    {
        $post = new Post(request('id'));
        return [
            'success' => [
                "message" => 'Post added.',
                "code" => 5
            ]
        ];
    }

    /**
     * @api {post} /api/faagram/post/get Get Faagram Posts
     * @apiName GetFaagramPosts
     * @apiGroup Faagram
     *
     * @apiParam {String} userId User' id.
     *
     * @apiSuccess {Array} posts User's posts.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function get()
    {
        $posts = Post::where('userId', request('userId'));
        return [
            'success' => [
                "message" => 'Posts retrieved.',
                "code" => 5
            ],
            'posts' => $posts
        ];
    }

    /**
     * @api {post} /api/faagram/post/remove Remove Faagram Post
     * @apiName RemoveFaagramPost
     * @apiGroup Faagram
     *
     * @apiParam {String} id Post' id.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function remove()
    {
        Post::find(request('id'))->delete();
        return [
            'success' => [
                "message" => 'Post removed.',
                "code" => 5
            ]
        ];
    }
}
