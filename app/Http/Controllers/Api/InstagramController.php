<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\CloudVision;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
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
    public static function instagramUrl()
    {
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
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function create()
    {
        if (!request()->has('code') || request()->has('error')) {
            return [
                'error' => [
                    "message" => 'Missing parameter(s).',
                    "code" => 4
                ]
            ];
        }
        $userId = \App\Http\Controllers\Auth\InstagramController::create(true, request('code'));
        $token = str_random(64);
        while (DB::table('users')->where('secret', $token)->exists() == true) {
            $token = str_random(64);
        }
        DB::table('users')->where('id', $userId)->update([
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

    /**
     * @api {post} /api/instagram/get Update Instagram Photos
     * @apiName InstagramUpdate
     * @apiGroup Instagram
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} updated Amount of retrieved images from Instagram.
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function get()
    {
        $userId = DB::table('users')->select('id')->where('secret', request('secret'))->value('id');
        if (DB::table('users')->where('id', $userId)->select('isInstagram')->value('isInstagram') != 1) {
            return [
                'error' => [
                    "message" => 'User is not instagram user.',
                    "code" => 4
                ]
            ];
        }
        $start = time();
        $token = DB::table('instagram-users')->where('user_id', $userId)->value('access_token');
        $rawData = Curl::to('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $token)->asJsonResponse()->get();
        if ($rawData->meta->code != 200) {
            return [
                'error' => [
                    "message" => 'Instagram token expired, please login again.',
                    "code" => 6
                ]
            ];
        }
        for ($i = 0; $i < count($rawData->data); $i++) {
            if (!empty(\App\Image::where('imageId', $rawData->data[$i]->id)->get()->toArray()))
                continue;
            $image = new \App\Image();
            $image->userId = $userId;
            $image->imageId = $rawData->data[$i]->id;
            $image->enabled = true;
            $image->save();
            if (!file_exists(public_path('images') . DIRECTORY_SEPARATOR . $rawData->data[$i]->id . ".jpg")) {
                $image = Image::make($rawData->data[$i]->images->standard_resolution->url);
                $image->save(public_path('images') . DIRECTORY_SEPARATOR . $rawData->data[$i]->id . ".jpg");
                $image->fit(600, 600)->save(public_path('thumb') . DIRECTORY_SEPARATOR . $rawData->data[$i]->id . ".jpg");
            }
            $faagramId = DB::table('users')->where('id',request('userId'))->select('faagramId')->value('faagramId');
            for ($i = 1; $i <= 3; $i++) {
                $job = (new CloudVision($userId, $rawData->data[$i]->id, (String)$i,$faagramId));
                dispatch($job);
            }
        };
        $end = time();
        return [
            'success' => [
                "message" => 'New images retrieved.',
                "code" => 5
            ]
        ];
    }

}
