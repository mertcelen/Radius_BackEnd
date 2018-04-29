<?php

namespace App\Http\Controllers\Faagram;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @api {post} /api/faagram/user/add Add Faagram User
     * @apiName AddFaagramUser
     * @apiGroup Faagram
     *
     * @apiParam {String} name User' name.
     *
     * @apiSuccess {Array} user Added user.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function add()
    {
        $user = User::add(request('name'));
        return [
            'success' => [
                "message" => 'New user added.',
                "code" => 5
            ],
            'user' => $user
        ];
    }

    /**
     * @api {post} /api/faagram/user/get Get Faagram User
     * @apiName GetFaagramUser
     * @apiGroup Faagram
     *
     * @apiParam {String} name User' name.
     *
     * @apiSuccess {Array} user Found user.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function get()
    {
        $user = User::where('name', request('name'));
        return [
            'success' => [
                "message" => 'User retrieved.',
                "code" => 5
            ],
            'user' => $user
        ];
    }
    /**
     * @api {post} /api/faagram/user/remove Remove Faagram User
     * @apiName RemoveFaagramUser
     * @apiGroup Faagram
     *
     * @apiParam {String} name User' name.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function remove()
    {
        User::where('name', request('name'))->delete();
        return [
            'success' => [
                "message" => 'User deleted.',
                "code" => 5
            ]
        ];
    }
}
