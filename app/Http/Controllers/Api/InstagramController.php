<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $userId = \App\Http\Controllers\Auth\InstagramController::create(true,request('code'));
        $token = str_random(64);
        while(DB::table('users')->where('secret',$token)->exists() == true){
           $token = str_random(64);
        }
        DB::table('users')->where('id',$userId)->update([
            'secret' => $token
        ]);
        return [
            'success' => 'ok',
            'secret' => $token
        ];
    }

    public function getImages(){
        $token = DB::table('instagram-users')->where('user_id',request('userId'))->value('access_token');
        $rawData = Curl::to('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $token)->asJsonResponse()->get();
        $data = array();
        for($i=0;$i < count($rawData->data);$i++){
            $flag = DB::table('images')->where('imageId',$rawData->data[$i]->id)->value('hasFace');
            if($flag == 1 || $flag === null){
                $data[] = [
                    'image' => $rawData->data[$i]->images->standard_resolution->url,
                    'id' => $rawData->data[$i]->id
                ];
            }
        };
        return [
            'instagram' => 1,
            'images' => $data
        ];
    }

}
