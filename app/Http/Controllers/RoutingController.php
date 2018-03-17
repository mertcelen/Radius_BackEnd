<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingController extends Controller
{
    public function home(){
      $response = Api\UserController::index();
      return view('home',[
          'images' => $response["images"]
      ]);
    }

    public function upload(){
      return view('upload');
    }

    public function admin(){
      $users = Api\AdminController::index();
      return view('admin',[
          'users' => $users
      ]);
    }
}
