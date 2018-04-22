<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public static function index()
    {
        $users = DB::table('users')->select('id', 'name', 'isInstagram', 'status')->get();
        return [
            "users" => $users,
            'success' => [
                "message" => 'All users listed.',
                "code" => 5
            ]
        ];

    }

    public static function logs()
    {
        return [
            "logs" => DB::table('logs')->get(),
            'success' => [
                "message" => 'Logs retrieved.',
                "code" => 5
            ]
        ];
    }

    public static function updateStatus()
    {
        DB::table('users')->where('id', request('id'))->update([
            'status' => request('status')
        ]);
        return [
            'success' => [
                "message" => 'User updated.',
                "code" => 5
            ]
        ];
    }
}
