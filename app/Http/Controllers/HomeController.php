<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->isInstagram == 1){
            $arr = InstagramController::getUserMedia();
            $face = new FaceRecognitionController();
            $status = DB::table('users')->select('status')->where('id',Auth::id())->value('status');
//            $arr = $face->detectArr($arr);
            return view('instagram',[
                'images' => $arr,
                'status' => $status
            ]);
        }else{
            $setup = DB::table('standart_users')->select('is_completed')->where('user_id',Auth::id())->value('is_completed');
            if($setup == 0){
                return redirect('/user/setup');
            }
            $status = DB::table('users')->select('status')->where('id',Auth::id())->value('status');

            $images = DB::table('images')->select('imageId','type')->where('userId',Auth::id())->get()->toArray();
            return view('home',[
                'status' => $status,
                'images' => $images
            ]);
        }
    }
}
