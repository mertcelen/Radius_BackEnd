<?php

namespace App\Http\Controllers;

use App\Dummy;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class RoutingController extends Controller
{
    public function home()
    {
        $result = Api\RecommendationController::main(Auth::id());
        if ($result != null) {
            shuffle($result);
        }
        return view('home', [
            'recommendations' => $result
        ]);
    }

    public function welcome()
    {
        if (Auth::check()) {
            return redirect('/home');
        } else {
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
        return view('admin.users', [
            'users' => $users
        ]);
    }

    public function settings()
    {
        $array = explode(',', Auth::user()->values);
        return view('settings', [
            'secret' => Auth::user()->getAttribute('secret'),
            'first' => (Integer)$array[0],
            'second' => (Integer)$array[1],
            'third' => (Integer)$array[2]
        ]);
    }

    public function instagram()
    {
        $link = Api\InstagramController::instagramUrl();
        return redirect($link["url"]);
    }

    public function updateInstagram()
    {
        $result = Api\InstagramController::get();
        return $result;
    }

    public function verify()
    {
        if (request()->has('code')) {
            $response = Api\UserController::verify();
            if (array_key_exists('success', $response)) {
                Auth::loginUsingId($response["userId"]);
                return redirect('/home');
            }
        }
        return redirect('/');
    }

    public function productAdd()
    {
        $types = DB::connection('mysql_clothes')->table('type')->get()->toArray();
        $colors = DB::connection('mysql_clothes')->table('color')->get()->toArray();
        $brands = DB::connection('mysql_clothes')->table('brand')->get()->toArray();
        return view('products.add', [
            'types' => $types,
            'colors' => $colors,
            'brands' => $brands
        ]);
    }

    public function productList()
    {
        $search = array();
        if (request()->has('brand')) array_push($search,['brand',request('brand')]);
        if (request()->has('color')) array_push($search,['color',request('color')]);
        if (request()->has('type')) array_push($search,['type',request('type')]);
        if (request()->has('gender')) array_push($search,['gender',request('gender')]);
        $types = DB::connection('mysql_clothes')->table('type')->get()->toArray();
        $colors = DB::connection('mysql_clothes')->table('color')->get()->toArray();
        $brands = DB::connection('mysql_clothes')->table('brand')->get()->toArray();
        $products = Product::where($search)->paginate(20);
        return view('products.list', [
            "products" => $products->appends(Input::except(['page','userId'])),
            'types' => $types,
            'colors' => $colors,
            'brands' => $brands
        ]);
    }

    public function faagramUsers()
    {
        $users = \App\Faagram\User::paginate(20);
        return view('faagram.user', [
            "users" => $users
        ]);
    }

    public function faagramPosts()
    {
        $posts = \App\Faagram\Post::paginate(20);
        return view('faagram.post', [
            "posts" => $posts
        ]);
    }

    public function faagramRelations()
    {
        $relations = \App\Faagram\Relation::paginate(20);
        return view('faagram.relation', [
            "relations" => $relations
        ]);
    }

    public function getData()
    {
        $id = User::where('secret', request('secret'))->first()->id;
        return Dummy::where('userId', $id)->first()->toArray();
    }

    public function setData()
    {
        $id = User::where('secret', request('secret'))->first()->id;
        $dummy = Dummy::where('userId',$id)->first();
        if($dummy == null){
            $dummy = new Dummy();
        }
        $dummy->type = request('type');
        $dummy->gender = request('gender');
        $dummy->color = request('color');
        $dummy->brand = request('brand');
        $dummy->userId = $id;
        $dummy->save();
        return 'ok';
    }
    public function getCategories(){
        $types = DB::connection('mysql_clothes')->table('type')->get()->toArray();
        $colors = DB::connection('mysql_clothes')->table('color')->get()->toArray();
        $brands = DB::connection('mysql_clothes')->table('brand')->get()->toArray();
        return [
            'types' => $types,
            'colors' => $colors,
            'brands' => $brands
        ];
    }
}
