<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class InstagramController extends Controller
{
    /**
     * @api {get} /api/instagram/url Instagram Url
     * @apiName InstagramUrl
     * @apiGroup Instagram
     *
     * @apiSuccess {String} url Instagram url to oAuth.
     */
    public function instagramUrl(){
        return [
            'url' => 'https://api.instagram.com/oauth/authorize/?client_id=' . env('INSTAGRAM_ID')
                . '&redirect_uri=' . env('INSTAGRAM_URI') . '&response_type=code'
        ];
    }
    /**
     * @api {post} /api/instagram/oauth Register Instagram User
     * @apiName InstagramOauth
     * @apiGroup Instagram
     *
     * @apiParam {String} code User' instagram code(from callback).
     *
     * @apiSuccess {String} secret Secret token to use in API calls.
     * @apiError {String} error  Secret or Code key error
     */
    public function create(){
        if(!request()->has('code') || request()->has('error')){
            return [
                'error' => [
                    "message" => 'Missing parameter(s).',
                    "code" => 4
                ]
            ];
        }
        $userId = \App\Http\Controllers\Auth\InstagramController::dcreate(true,request('code'));
        $token = str_random(64);
        while(DB::table('users')->where('secret',$token)->exists() == true){
           $token = str_random(64);
        }
        DB::table('users')->where('id',$userId)->update([
            'secret' => $token
        ]);
        return [
            'success' => [
                "message" => 'User logged in.',
                "code" => 5
            ],
            'secret' => $token
        ];
    }

    public static function retrieve($userId = null){
        if(request('userId') && empty($userId)){
            $userId = request('userId');
        }
        if(DB::table('users')->where('id',$userId)->select('isInstagram')->value('isInstagram') != 1){
            return [
                'error' => [
                    "message" => 'User is not instagram user.',
                    "code" => 4
                ]
            ];
        }
        $start = time();
        $token = DB::table('instagram-users')->where('user_id',$userId)->value('access_token');
        $rawData = Curl::to('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $token)->asJsonResponse()->get();
        if($rawData->meta->code != 200){
            return [
                'error' => [
                    "message" => 'Instagram token expired, please login again.',
                    "code" => 6
                ]
            ];
        }
        $data = array();
        for($i=0;$i < count($rawData->data);$i++){
            $error = false;
            try{
                DB::table('images')->insert([
                   "userId" => $userId,
                   "imageId" =>  $rawData->data[$i]->id,
                    "type" => "jpg"
                ]);
            }catch (\Exception $e){
                $error = true;
            }
            if(!$error || !file_exists(public_path('images') . DIRECTORY_SEPARATOR . $rawData->data[$i]->id . ".jpg")){
                $temp = file_get_contents($rawData->data[$i]->images->standard_resolution->url);
                file_put_contents(public_path('images') . DIRECTORY_SEPARATOR . $rawData->data[$i]->id . ".jpg",$temp);
            }
            array_push($data,[
                'imageId' => $rawData->data[$i]->id,
                'type' => 'jpg'
            ]);
        };
        $end = time();
        return [
            'success' => [
                "message" => 'Images retrieved.',
                "code" => 5
            ],
            'instagram' => 1,
            'images' => $data,
            'times' => [
                'start_time' => $start,
                'end_time' => $end
            ]
        ];
    }

}
