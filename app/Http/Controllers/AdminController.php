<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(){
        $users = Api\AdminController::index();
        return view('admin',[
            'users' => $users
        ]);
    }

    public function updateStatus(){

        return 1;
    }
}
