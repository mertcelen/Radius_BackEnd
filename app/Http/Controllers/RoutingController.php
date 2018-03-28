<?php

namespace App\Http\Controllers;

class RoutingController extends Controller
{
    public function home(){
      $response = Api\UserController::index();
      return view('home',[
          'images' => $response["images"]
      ]);
    }

    public function photos(){
      $images = Api\PhotoController::get();
      return view('photos',[
        'images' => $images["images"]
      ]);
    }

    public function admin(){
      $users = Api\AdminController::index();
      return view('admin',[
          'users' => $users
      ]);
    }

    public function settings(){
        $result = Api\UserController::settings();
      return view('settings',[
          'instagram' => $result["instagram"]
      ]);
    }

    public function instagram(){
        $link = Api\InstagramController::instagramUrl();
        return redirect($link["url"]);
    }
}
