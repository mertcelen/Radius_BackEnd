<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(){
        $users = DB::table('users')->select('id','name','isInstagram','status')->get();
        return view('admin',[
            'users' => $users
        ]);
    }

    public function updateStatus(){
        if(DB::table('users')->select('status')->where('id',Auth::id())->value('status') != 3) return 0;
        DB::table('users')->where('id',request('id'))->update([
            'status'=> request('status')
        ]);
        return 1;
    }
}
