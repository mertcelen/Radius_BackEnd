<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class RoutingController extends Controller
{
    public function home(){
      if(Auth::check()) {
          $response = Api\UserController::index();
          return view('home', [
              'images' => $response["images"]
          ]);
      }else{
          return view('welcome');
      }
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
          'instagram' => $result["instagram"],
          'secret' => Auth::user()->getAttribute('secret')
      ]);
    }

    public function instagram(){
        $link = Api\InstagramController::instagramUrl();
        return redirect($link["url"]);
    }

    public function updateInstagram(){
        $result = Api\InstagramController::get(Auth::user()->getAttribute('id'));
        return $result;
    }
}
