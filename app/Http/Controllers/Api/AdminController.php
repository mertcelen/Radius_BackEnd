<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public static function index(){
        $users = DB::table('users')->select('id','name','isInstagram','status')->get();
        return [
            "users" => $users,
            'success' => [
                "message" => 'All users listed.',
                "code" => 5
            ]
        ];

    }

    public static function logs(){
        return DB::table('logs')->get();
    }

    public static function updateStatus(){
        echo(request('id'));
        die();
        DB::table('users')->where('id',request('id'))->update([
            'status'=> request('status')
        ]);
    }
}
