<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class InstagramController extends Controller
{
    public function index(){
    //Redirect users to Instagram Login Page
        return redirect('https://api.instagram.com/oauth/authorize/?client_id=' . env('INSTAGRAM_ID')
            . '&redirect_uri=' . env('INSTAGRAM_URI') . '&response_type=code');
    }

    public function create(){
    //Extract the code from instagram, save it to database if successful

        //Check if request has code and doesn't have any errors.
        if(!request()->has('code') || request()->has('error')) return redirect('/');

        //Request permanent token from instagram using that 1 time code.

        list($error, $user) =  $this->getToken(request('code'),env('INSTAGRAM_URI'));
        if($error) return redirect('/');

        //Now check if user is actually exist or not.
        if(DB::table('instagram-users')->where('instagram_id',$user->user->id)->exists() == false){
            //User not found on database, so we need to create one.
            $userId = DB::table('users')->insertGetId([
                'name' => $user->user->username,
                'isInstagram' => true,
                'instagram_id' => $user->user->id,
                'status' => 1
            ]);
            if(is_null($userId) == true){
                return redirect('/');
            }else{
                //Now add user to instagram table using that user id.
                DB::table('instagram-users')->insert([
                    'user_id' => $userId,
                    'access_token' => $user->access_token,
                    'username' => $user->user->username,
                    'full_name' => $user->user->full_name,
                    'profile_picture' => $user->user->profile_picture,
                    'instagram_id' => $user->user->id
                ]);
            }
        }else{
          //Update the token of the existing user once again.
          DB::table('instagram-users')->where('instagram_id',$user->user->id)->update([
            'access_token' => $user->access_token
          ]);
        }
        Auth::loginUsingId(DB::table('users')->where('instagram_id',$user->user->id)->value('id'));
        return redirect('/');
    }

    public static function getToken($code,$uri){
        $response = Curl::to('https://api.instagram.com/oauth/access_token')->withData(array(
            'client_id' => env('INSTAGRAM_ID'),
            'client_secret' => env('INSTAGRAM_SECRET'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $uri,
            'code' => $code
        ))->asJsonResponse()->post();
        if(isset($response->access_token)){
            return array(false,$response);
        }else{
            return array(true,$response);
        }
    }
}
