<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

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
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function login(){
        if(!Auth::validate(['email' => request('email'),'password' => request('password')])){
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
        //Lastly check if request needs to start session
        if(request()->has('session') && request('session') == true){
            Auth::attempt([
                "email" => request('email'),
                "password" => request('password')
            ]);
        }
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
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
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
     * @apiSuccess {Array} images User' Recommended Images (Currently all available images).
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function index(){
        return PhotoController::get();
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
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
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
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
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
    /**
     * @api {post} /api/user/password Change Password
     * @apiName ChangePassword
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} old-password User' old password.
     * @apiParam {String} new-password User' new password.
     * @apiParam {String} new-password2 User' new password confirmation.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function password(){
        $old = request('old-password');
        $new = request('new-password');
        $new2 = request('new-password2');
        if(strcmp($new,$new2) != 0){
            return [
                'error' => [
                    "message" => 'Passwords not match.',
                    "code" => 4
                ]
            ];
        }
        if(strcmp($old,$new) == 0){
            return [
                'error' => [
                    "message" => 'Old and new passwords are same.',
                    "code" => 4
                ]
            ];
        }
        $current = DB::table('users')->select('password')->where('id',request('userId'))->value('password');
        $flag = Hash::check(request('old-password'),$current);
        if($flag == false){
            return [
                'error' => [
                    "message" => 'Old password is wrong.',
                    "code" => 4
                ]
            ];
        }
        DB::table('users')->where('id',request('userId'))->update([
            'password' => bcrypt(request('new-password'))
        ]);
        return [
          'success' => [
              "message" => 'Password changed.',
              "code" => 5
          ],
        ];
    }

    public static function settings(){
        $isInstagram = DB::table('users')->select('isInstagram')->where('id',Auth::id())->value('isInstagram');
        //If user doesn't have secret, create one.
        if(DB::table('users')->select('secret')->where('id',Auth::id())->value('secret') == null){
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            DB::table('users')->where('id',Auth::id())->update([
                'secret' => $token
            ]);
        }
        return [
            'success' => [
                "message" => 'Password changed.',
                "code" => 5
            ],
            'instagram' => $isInstagram
        ];
    }
    /**
     * @api {post} /api/user/avatar Change Avatar
     * @apiName UpdateAvatar
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {File} photo Photo file to be added.
     *
     * @apiSuccess {String} id Avatar id.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function userAvatar($url = null){
        $avatarId = str_random(8);
        while(DB::table('users')->where('avatar',$avatarId)->exists() == true){
            $avatarId = str_random(8);
        }
        if($url == null){
            $image = Image::make(Input::file('photo'));
        }else{
            $image = Image::make($url);
        }

        $image->resize('150','150')->save(public_path('avatar') . DS . $avatarId . ".jpg");
        DB::table('users')->where('id',request('userId'))->update([
           'avatar' => $avatarId
        ]);
        return [
            'success' => [
                "message" => 'Avatar updated',
                "code" => 5
            ],
            'id' => $avatarId
        ];
    }
    /**
     * @api {post} /api/user/avatar/get Get Avatar
     * @apiName GetAvatar
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} id Avatar id.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function getAvatar(){
        $avatarId = DB::table('users')->select('avatar')->where('id',request('userId'))->value('avatar');
        return [
            'success' => [
                "message" => 'Avatar id retrieved.',
                "code" => 5
            ],
            'id' => $avatarId
        ];
    }
    /**
     * @api {post} /api/user/verify Verify Email
     * @apiName VerifyEmail
     * @apiGroup User
     *
     * @apiParam {String} code Verification Email
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function verify(){
        //First find id
        $flag = DB::table('users')->where('verification',request('code'))->exists();
        if($flag == false){
            return [
                'error' => [
                    "message" => 'Verification code is invalid.',
                    "code" => 4
                ]
            ];
        }
        DB::table('users')->where('verification',request('code'))->update([
            'verification' => '1',
            'status' => 1
        ]);
        return [
            'success' => [
                "message" => 'Email successfully verified.',
                "code" => 5
            ]
        ];
    }
}
