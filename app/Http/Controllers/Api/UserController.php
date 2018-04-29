<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * @apiParam {File} photo Photo file to be added.
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
            $image = Image::make(Input::file('photo'));
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
     * @apiParam {String} gender User' gender (0 Male 1 Female).
     *
     * @apiSuccess {String} user User All user information.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function register()
    {
        $this->validate(request(), [
            'name' => 'required|string|max:255|min:3',
            'email' => ['required', 'email',
                function ($attribute, $value, $fail) {
                    if ($attribute == 'email') {
                        $customer = User::where($attribute, 'like', $value)->first();
                        if ($customer !== null)
                            return $fail(ucfirst($attribute) . ' already exists.');
                    }
                }],
            'password' => 'required|string|min:6|max:255',
            'female' => 'required|string'
        ]);

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
        $user = User::where('userId', request('userId'))->first();
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
        if (strcmp($old, $new) == 0) {
            return [
                'error' => [
                    "message" => 'Old and new passwords are same.',
                    "code" => 4
                ]
            ];
        }
        $user = User::where('userId', request('userId'))->first();
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
     * @api {post} /api/user/avatar/get Get Avatar
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
        $user = User::where('userId', request('userId'))->first();
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

}
