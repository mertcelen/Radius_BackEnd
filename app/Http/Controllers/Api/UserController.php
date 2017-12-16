<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\InstagramController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(){
        if(!request()->has('email') && request()->has('password')){
            return response()->json([
                'error' => 'Missing information'
            ]);
        }

        //Now that api is done, let's check user

        $flag = Auth::attempt([
            'email' => request('email'),
            'password' => request('password')
        ]);
        if($flag == true){
            //Let's add session token to DB
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            DB::table('users')->where('email',request('email'))->update(['secret' => $token]);
            return response()->json([
                'success' => 'user logged in',
                'secret' => $token
            ]);
        }else{
            return response()->json([
               'error' => 'Wrong information'
            ]);
        }
    }

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
        return response()->json([
            'ok' => 'confirmed',
            'secret' => $token
        ]);
    }

    public function index(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        $userId = DB::table('users')->select('id')->where('secret',request('secret'))->value('id');
        if($userId == null){
            return response()->json([
               'error' => 'Wrong token'
            ]);
        }

        $flag = DB::table('standart_users')->select('is_completed')->where('user_id',$userId)->value('is_completed');
        if($flag == 1){
            return response()->json([
                'success' => 'Welcome back'
            ]);
        }else{
            return response()->json([
                'error' => 'Missing preferences'
            ]);
        }

    }

    public function preferences(){
        if(!request()->has('body_type') || !request()->has('body_style') || !request()->has('secret')){
            return response()->json([
                'error' => 'Missing information'
            ]);
        }

        $userId = DB::table('users')->select('id')->where('secret',request('secret'))->value('id');
        DB::table('standart_users')->where('user_id',$userId)->update([
           'body_type' => request('body_type'),
           'body_style' => request('body_style'),
            'is_completed' => 1
        ]);

        return response()->json([
           'success' => 'Information Updated'
        ]);
    }

    public function instagramUrl(){
        return response()->json([
           'url' => 'https://api.instagram.com/oauth/authorize/?client_id=' . env('INSTAGRAM_ID')
               . '&redirect_uri=' . env('INSTAGRAM_API_URI') . '&response_type=code'
        ]);
    }

    public function instagram(){
        if(!request()->has('code') || request()->has('error')){
            return response()->json([
                'error' => 'Missing information'
            ]);
        }
        list($error, $user) =  InstagramController::getToken(request('code'),env('INSTAGRAM_API_URI'));
        if($error){
            return response()->json([
               'error' => 'Error retrieving instagram token'
            ]);
        }

        if(DB::table('instagram-users')->where('instagram_id',$user->user->id)->exists() == false){
            //User not found on database, so we need to create one.
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            $userId = DB::table('users')->insertGetId([
                'name' => $user->user->username,
                'isInstagram' => true,
                'instagram_id' => $user->user->id,
                'status' => 1,
                'secret' => $token
            ]);
            if(is_null($userId) == true){
                return response()->json([
                    'error' => 'Error Inserting DB'
                ]);
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
            return response()->json([
                'success' => 'ok',
                'secret' => $token
            ]);
        }else{
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            DB::table('users')->where('instagram_id',$user->user->id)->update([
               'secret' => $token
            ]);
            return response()->json([
                'success' => 'ok',
                'secret' => $token
            ]);
        }
    }

    public function logout(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        DB::table('users')->where('secret',request('secret'))->update([
           'secret' => null
        ]);
        return response()->json([
           'success' => 'User logged out'
        ]);
    }
}
