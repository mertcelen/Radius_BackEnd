<?php

namespace App\Http\Controllers\Api;

use App\Faagram\Post;
use App\Http\Controllers\Controller;
use App\Jobs\SendVerification;
use App\Style;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

define('DS', DIRECTORY_SEPARATOR);

class UserController extends Controller
{
    /**
     * @api {post} /api/index Home Page
     * @apiName HomePage
     * @apiGroup Home
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {Array} recommendations User' Recommended Images.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function index()
    {
        $result = RecommendationController::main(request('userId'));
        if ($result != null) {
            shuffle($result);
        }
        return [
            'success' => [
                "message" => 'Recommendations retrieved.',
                "code" => 5
            ],
            "recommendations" => $result
        ];
    }

    /**
     * @api {post} /api/user/avatar Change Avatar
     * @apiName UpdateAvatar
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {File} image Photo file to be added.
     *
     * @apiSuccess {String} avatarId Avatar id.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function userAvatar($url = null)
    {
        $avatarId = str_random(8);
        while (User::where('avatar', $avatarId)->exists() == true) {
            $avatarId = str_random(8);
        }
        if ($url == null) {
            $image = Image::make(Input::file('image'));
        } else {
            $image = Image::make($url);
        }
        $image->resize('150', '150')->save(public_path('avatar') . DS . $avatarId . ".jpg");
        User::where('_id', request('userId'))->update([
            'avatar' => $avatarId
        ]);
        return [
            'success' => [
                "message" => 'Avatar updated',
                "code" => 5
            ],
            'avatarId' => $avatarId
        ];
    }

    /**
     * @api {post} /api/user/verify Verify Email
     * @apiName VerifyEmail
     * @apiGroup User
     *
     * @apiParam {String} code Setup Email
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function verify()
    {
        $user = \App\User::where('verification', request('code'))->first();
        if ($user == null) {
            return [
                'error' => [
                    "message" => 'Setup code is invalid.',
                    "code" => 4
                ]
            ];
        }
        $user->verification = 1;
        $user->status = 1;
        $user->save();
        return [
            'success' => [
                "message" => 'Email successfully verified.',
                "code" => 5
            ],
            'userId' => $user->_id
        ];
    }

    /**
     * @api {post} /api/login Login User
     * @apiName LoginUser
     * @apiGroup User
     *
     * @apiParam {String} email User' email address.
     * @apiParam {String} password User' password.
     *
     * @apiSuccess {String} user User All user information.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function login()
    {
        if (!Auth::validate(['email' => request('email'), 'password' => request('password')])) {
            return [
                'error' => [
                    "message" => 'Email/password is wrong.',
                    "code" => 2
                ]
            ];
        }
        $secret = str_random(64);
        while (User::where('secret', $secret)->exists() == true) {
            $secret = str_random(64);
        }
        $user = User::where('email', request('email'))->first();
        $user->secret = $secret;
        $user->save();
        return [
            'success' => [
                "message" => 'User logged in.',
                "code" => 5
            ],
            'user' => $user
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
     * @apiParam {String} gender User' gender (1 Male 2 Female).
     *
     * @apiSuccess {String} user User All user information.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function register()
    {
        $validator = \Validator::make(request()->all(),[
            'name' => 'required|string|max:255|min:3',
            'email' => ['required', 'email',
                function ($attribute, $value, $fail) {
                    if ($attribute == 'email') {
                        $user = User::where($attribute, 'like', $value)->first();
                        if ($user !== null)
                            return $fail(ucfirst($attribute) . ' already exists.');
                    }
                }],
            'password' => 'required|string|min:6|max:255',
            'gender' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }
        $user = User::add(request('name'), request('email'), request('password'), intval(request('gender')));
        //Send Setup Email
        $email = new SendVerification($user->email, $user->verification);
        $this->dispatch($email);
        return [
            'success' => [
                "message" => 'User added.',
                "code" => 5
            ],
            'user' => $user
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
    public function logout()
    {
        $secret = str_random(64);
        while (User::where('secret', $secret)->exists() == true) {
            $secret = str_random(64);
        }
        $user = User::where('_id', request('userId'))->first();
        $user->secret = $secret;
        $user->save();
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
    public function password()
    {
        $old = request('old-password');
        $new = request('new-password');
        $new2 = request('new-password2');
        if (strcmp($new, $new2) != 0) {
            return [
                'error' => [
                    "message" => 'Passwords not match.',
                    "code" => 4
                ]
            ];
        }
        if (strlen($new) == 0){
            return [
                'error' => [
                    "message" => 'New password cannot blank.',
                    "code" => 4
                ]
            ];
        }
        $user = User::where('_id', request('userId'))->first();
        $flag = Hash::check(request('old-password'), $user->password);
        if ($flag == false) {
            return [
                'error' => [
                    "message" => 'Old password is wrong.',
                    "code" => 4
                ]
            ];
        }
        $user->password = bcrypt(request('new-password'));
        $user->save();
        return [
            'success' => [
                "message" => 'Password changed.',
                "code" => 5
            ],
        ];
    }

    /**
     * @api {get} /api/user/avatar Get Avatar
     * @apiName GetAvatar
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} avatarId Avatar id.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function getAvatar()
    {
        $user = User::where('_id', request('userId'))->first();
        return [
            'success' => [
                "message" => 'Avatar id retrieved.',
                "code" => 5
            ],
            'avatarId' => $user->avatar
        ];
    }

    /**
     * @api {post} /api/user/values Recommendation Preferences
     * @apiName UpdateRecommendationPreferences
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} first User' Post Preference
     * @apiParam {String} second User' Like Preference
     * @apiParam {String} third User' Following Preference
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function values()
    {
        $user = User::where('secret', request('secret'))->first();
        $user->values = implode(',', [request('first'), request('second'), request('third')]);
        $user->save();
        return [
            'success' => [
                "message" => 'User preferences updated.',
                "code" => 5
            ]
        ];
    }

    /**
     * @api {get} /api/user/values Recommendation Preferences
     * @apiName GetRecommendationPreferences
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     *
     * @apiSuccess {Array} values User' Recommendation Preferences.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function getValues()
    {
        $user = User::find(request('userId'))->first();
        $values = explode(',', $user->values);
        return [
            'success' => [
                "message" => 'User preferences updated.',
                "code" => 5
            ],
            'values' => $values
        ];
    }

    /**
     * @api {post} /api/user/style User Style
     * @apiName UserStyle
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {Array} selected User' selected image id array.
     *
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function setup(){
        $user = User::where('_id',request('userId'))->first();
        $selected = (is_array(request('selected')) ? request('selected') : explode(',',request('selected')));
        $array = array();
        if(count($selected) < 5){
            return [
                'error' => [
                    "message" => 'Please select at least 5 photos that you like.',
                    "code" => 4
                ]
            ];
        }
        foreach ($selected as $item){
            $style = Style::where('name',$item)->where('gender',intval($user->gender))->first();
            $post = new Post();
            $post->userId = $user->faagramId;
            $post->label = $style->part1["type"];
            $post->color = $style->part1["color"];
            $post->save();
            array_push($array,$post->_id);
            if($style->part2 != null){
                $post = new Post();
                $post->userId = $user->faagramId;
                $post->label = $style->part2["type"];
                $post->color = $style->part2["color"];
                $post->save();
                array_push($array,$post->_id);
            }
        }
        $user->setup = true;
        $user->save();
        return [
            'success' => [
                "message" => 'User style updated.',
                "code" => 5
            ],
            'posts' => $array
        ];
    }
}
