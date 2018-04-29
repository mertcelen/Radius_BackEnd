<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Faagram\AssociateController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class InstagramController extends Controller
{
    public static function create($isApicall = false, $apiCode = null)
    {
        //Extract the code from instagram, save it to database if successful
        //Check if request has code and doesn't have any errors.
        if (!request()->has('code') || request()->has('error')) return redirect('/');
        //Request permanent token from instagram using that 1 time code.
        if ($isApicall == true) {
            $code = $apiCode;
        } else {
            $code = request('code');
        }
        list($error, $instagramUser) = InstagramController::getToken($code, env('INSTAGRAM_URI'));
        if ($error) return redirect('/');
        //Now check if user is actually exist or not.
        $secret = str_random(64);
        $user = new User();
        if (User::where('instagram.id', $instagramUser->user->id)->exists() == false) {
            //User not found on database, so we need to create one.
            $user->name = $instagramUser->user->username;
            $secret = str_random(64);
            while (User::where('secret', $secret)->exists() == true) {
                $secret = str_random(64);
            }
            $user->secret = $secret;
            $user->status = 1;
            $user->type = 2;
            $user->avatar = \App\Http\Controllers\Api\UserController::userAvatar($instagramUser->user->profile_picture)['avatarId'];
            $user->instagram = [
                'access_token' => $instagramUser->access_token,
                'username' => $instagramUser->user->username,
                'full_name' => $instagramUser->user->full_name,
                'id' => $instagramUser->user->id
            ];
            $user->values = "50,25,25";
            $user->save();
            $userId = $user->_id;
            $user->faagramId = AssociateController::real($user->_id,$user->name);
            $user->save();
            \App\Http\Controllers\Api\InstagramController::get($user->_id);
        } else {
            $user = User::where('instagram.id',$instagramUser->user->id)->first();
            $userId = $user->_id;
            //Update the token of the existing user once again.
            while (User::where('secret', $secret)->exists() == true) {
                $secret = str_random(64);
            }
            $user->secret = $secret;
            $user->instagram = [
                'access_token' => $instagramUser->access_token,
                'username' => $instagramUser->user->username,
                'full_name' => $instagramUser->user->full_name,
                'id' => $instagramUser->user->id
            ];
            $user->save();
        }
        if ($isApicall == true) {
            return $user;
        } else {
            Auth::loginUsingId($user->_id,true);
            return redirect('/home');
        }
    }

    public
    static function getToken($code, $uri)
    {
        $response = Curl::to('https://api.instagram.com/oauth/access_token')->withData(array(
            'client_id' => env('INSTAGRAM_ID'),
            'client_secret' => env('INSTAGRAM_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $uri,
            'code' => $code
        ))->asJsonResponse()->post();
        if (isset($response->access_token)) {
            return array(false, $response);
        } else {
            return array(true, $response);
        }
    }
}
