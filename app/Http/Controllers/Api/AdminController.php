<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public static function index()
    {
        $users = User::all();
        return [
            "users" => $users,
            'success' => [
                "message" => 'All users listed.',
                "code" => 5
            ]
        ];

    }

    public static function updateStatus()
    {
        User::where('_id',request('id'))->update([
           'status' => intval(request('status'))
        ]);
        return [
            'success' => [
                "message" => 'User updated.',
                "code" => 5
            ]
        ];
    }
}
