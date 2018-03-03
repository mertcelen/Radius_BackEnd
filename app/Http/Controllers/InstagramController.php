<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class InstagramController extends Controller
{
    public static function getUserMedia(){
        $token = InstagramController::getUserToken();
        $rawData = Curl::to('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $token)->asJsonResponse()->get();
        if($rawData->meta->code != 200){
          return null;
        }
        $data = array();
        for($i=0;$i < count($rawData->data);$i++){
            $flag = DB::table('images')->where('imageId',$rawData->data[$i]->id)->value('hasFace');
            if($flag >= 0){
                $data[] = [
                    'image' => $rawData->data[$i]->images->standard_resolution->url,
                    'id' => $rawData->data[$i]->id
                ];
            }
        };
        return $data;
    }

    protected static function getUserToken(){
        return DB::table('instagram-users')->where('user_id',Auth::id())->value('access_token');
    }
}
