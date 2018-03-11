<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


define('DS', DIRECTORY_SEPARATOR);
class UserController extends Controller
{
    /**
     * @api {post} /api/login Login User
     * @apiName LoginUser
     * @apiGroup User
     *
     * @apiParam {String} email User' email address.
     * @apiParam {String} password User' password.
     *
     * @apiSuccess {String} secret Secret token to use in API calls.
     * @apiError {String} error  Login error
     */
    public function login(){
        if(!request()->has('email') && request()->has('password')){
            return [
                'error' => [
                    "message" => 'Missing parameter(s).',
                    "code" => 1
                ]
            ];
        }
        $flag = Auth::validate([
            'email' => request('email'),
            'password' => request('password')
        ]);
        if($flag == false){
            return [
                'error' => [
                    "message" => 'Wrong parameter(s).',
                    "code" => 2
                ]
            ];
        }
        $token = str_random(64);
        while(DB::table('users')->where('secret',$token)->exists() == true){
            $token = str_random(64);
        }
        DB::table('users')->where('email',request('email'))->update(['secret' => $token]);
        return [
            'success' => [
                "message" => 'User logged in.',
                "code" => 5
            ],
           'secret' => $token
        ];
    }
    /**
     * @api {post} /api/register Register User
     * @apiName RegisterUser
     * @apiGroup User
     *
     * @apiParam {String} email User' email address.
     * @apiParam {String} password User' password.
     * @apiParam {String} name User' name.
     *
     * @apiSuccess {String} secret Secret token to use in API calls.
     * @apiError {String} error  Register error
     */
    public function register(){
        $this->validate(request(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $token = str_random(64);
        while(DB::table('users')->where('secret',$token)->exists() == true){
            $token = str_random(64);
        }
        $id = DB::table('users')->insertGetId([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'secret' => $token
        ]);
        DB::table('standart_users')->insert([
            'user_id' => $id
        ]);
        return [
            'success' => [
                "message" => 'User added.',
                "code" => 5
            ],
            'secret' => $token
        ];
    }
    /**
     * @api {post} /api/index Home Page
     * @apiName HomePage
     * @apiGroup Home
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} secret Homepage messages.
     * @apiSuccess {String} instagram If user is Instagram User or not.
     * @apiError {String} error  Secret key error
     */
    public function index(){
        $images = DB::table('images')->select('imageId')->select('type')->where('userId',request('userId'))->get()->toArray();
        return [
            'success' => [
                "message" => 'Images retrieved.',
                "code" => 5
            ],
            "images" => $images
        ];
    }
    /**
     * @api {post} /api/user/preferences Preferences Update
     * @apiName UserPreferences
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} body_type User' body type.
     * @apiParam {String} body_style User' body style.
     *
     * @apiSuccess {String} secret Update confirmation.
     * @apiError {String} error  Secret key error
     */
    public function preferences(){
        DB::table('standart_users')->where('user_id',request('userId'))->update([
           'body_type' => request('body_type'),
           'body_style' => request('body_style'),
            'is_completed' => 1
        ]);
        return [
            'success' => [
                "message" => 'Information updated.',
                "code" => 5
            ],
        ];
    }
    /**
     * @api {post} /api/logout Logout User
     * @apiName LogoutUser
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} status Confirmation of logout.
     */
    public function logout(){
        DB::table('users')->where('id',request('userId'))->update([
           'secret' => null
        ]);
        return [
            'success' => [
                "message" => 'User logged out.',
                "code" => 5
            ],
        ];
    }

}
