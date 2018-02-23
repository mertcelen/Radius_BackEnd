<?php

namespace App\Http\Controllers;
define('DS', DIRECTORY_SEPARATOR);
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PhotosController extends Controller
{
    public function index(){
        $status = DB::table('users')->select('status')->where('id',Auth::id())->value('status');
        return view('photos',[
            'status' => $status
        ]);
    }

    public function upload(Request $request){
        if(!$request->has('photo') || !$request->hasFile('photo') || !$request->file('photo')->isValid()){
            return redirect()->back();
        }
        $file_name = str_random(32);
        //Making sure id not exist in db.
        while(DB::table('images')->where('imageId',$file_name)->exists() == true){
            $file_name = str_random(64);
        }
        $user_id = Auth::id();
        $file = $request->file('photo');
        //Write image information to database
        DB::table('images')->insert([
            "userId" => $user_id,
            "imageId" => $file_name,
            "type" => $file->getClientOriginalExtension()
        ]);
        //Finally save file into the disk.
        $file->move(public_path('images'),$file_name . "." . $file->getClientOriginalExtension());
        return Redirect::back()->withErrors(['msg', 'The Message']);
    }

    public function remove(){
        if(Auth::check() ==false){
            return response()->json([
                "error" => "Not authorized"
            ]);
        }
        if(!request()->has('imageId')){
            return response()->json([
               "error" => "Image ID is missing"
            ]);
        }

        DB::table('images')->select('userId',Auth::id())->select('imageId',request('imageId'))->delete();
        return response()->json([
            "success" => "ok",
        ]);
    }
}