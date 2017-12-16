<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function setup(){
        return view('setup');
    }

    public function save(){
        if(Auth::check() == false)return redirect('/');
//        request()->validate([
//            'title' => 'required|unique:posts|max:255',
//            'body' => 'required',
//        ]);
        DB::table('standart_users')->where('user_id',Auth::id())->update([
            'is_completed' => 1,
            'body_type' => request('body_type'),
            'body_style' => \request('style')
        ]);
        return redirect('/');
    }
}
