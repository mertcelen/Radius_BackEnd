<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoutingController extends Controller
{
    public function home()
    {
        if(Auth::check()){
            $result = Api\RecommendationController::main(Auth::id());
            shuffle($result);
            return view('home',[
                'recommendations' => $result
            ]);
        }else{
            return view('welcome');
        }
    }

    public function photos()
    {
        $images = Api\ImageController::get();
        return view('photos', [
            'images' => $images["images"]
        ]);
    }

    public function admin()
    {
        $users = Api\AdminController::index();
        return view('admin', [
            'users' => $users
        ]);
    }

    public function settings()
    {
        $result = Api\UserController::settings();
        return view('settings', [
            'instagram' => $result["instagram"],
            'secret' => Auth::user()->getAttribute('secret')
        ]);
    }

    public function instagram()
    {
        $link = Api\InstagramController::instagramUrl();
        return redirect($link["url"]);
    }

    public function updateInstagram()
    {
        $result = Api\InstagramController::get(Auth::user()->getAttribute('id'));
        return $result;
    }

    public function verify()
    {
        if (Auth::check() == true && !Auth::user()->isVerified()) {
            return redirect('/');
        }
        if (request()->has('code')) {
            $response = Api\UserController::verify();
            if (array_key_exists('succcess', $response)) {
                return redirect('/');
            } else {
                return view('verification', [
                    'error' => $response["error"]["message"]
                ]);
            }
        } else {
            return view('verification');
        }
    }

    public function product()
    {
        $types = DB::connection('mysql_clothes')->table('type')->get()->toArray();
        $colors = DB::connection('mysql_clothes')->table('color')->get()->toArray();
        $brands = DB::connection('mysql_clothes')->table('brand')->get()->toArray();
        return view('admin.product', [
            'types' => $types,
            'colors' => $colors,
            'brands' => $brands
        ]);
    }

    public function color()
    {
        $colors = [
            'black' => 0,
            'beige' => 16777215,
            'blue' => 16777215,

        ];
        return view('color');
    }
}
