<?php

namespace App\Http\Controllers\Api;
define('DS', DIRECTORY_SEPARATOR);
use App\Http\Controllers\Auth\InstagramController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;

class UserController extends Controller
{
    /**
     * @api {post} /api/login Login User
     * @apiName LoginUser
     * @apiGroup User
     *
     * @apiParam {String} email User' email address.
     * @apiParam {String} password User' password.
     *
     * @apiSuccess {String} secret Secret token to use in API calls.
     * @apiError {String} error  Login error
     */
    public function login(){
        if(!request()->has('email') && request()->has('password')){
            return response()->json([
                'error' => 'Missing information'
            ]);
        }

        //Now that api is done, let's check user

        $flag = Auth::attempt([
            'email' => request('email'),
            'password' => request('password')
        ]);
        if($flag == true){
            //Let's add session token to DB
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            DB::table('users')->where('email',request('email'))->update(['secret' => $token]);
            return response()->json([
                'success' => 'user logged in',
                'secret' => $token
            ]);
        }else{
            return response()->json([
               'error' => 'Wrong information'
            ]);
        }
    }

    /**
     * @api {post} /api/register Register User
     * @apiName RegisterUser
     * @apiGroup User
     *
     * @apiParam {String} email User' email address.
     * @apiParam {String} password User' password.
     * @apiParam {String} name User' name.
     *
     * @apiSuccess {String} secret Secret token to use in API calls.
     * @apiError {String} error  Register error
     */

    public function register(){
        $this->validate(request(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $token = str_random(64);
        while(DB::table('users')->where('secret',$token)->exists() == true){
            $token = str_random(64);
        }
        $id = DB::table('users')->insertGetId([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'secret' => $token
        ]);
        DB::table('standart_users')->insert([
            'user_id' => $id
        ]);
        return response()->json([
            'ok' => 'confirmed',
            'secret' => $token
        ]);
    }

    /**
     * @api {post} /api/index Home Page
     * @apiName HomePage
     * @apiGroup Home
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} secret Homepage messages.
     * @apiSuccess {String} instagram If user is Instagram User or not.
     * @apiError {String} error  Secret key error
     */
    public function index(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        $userId = DB::table('users')->select('id')->where('secret',request('secret'))->value('id');
        if($userId == null){
            return response()->json([
               'error' => 'Wrong token'
            ]);
        }

        $userType = DB::table('users')->select('isInstagram')->where('secret',request('secret'))->value('isInstagram');
        if($userType==0){
            $flag = DB::table('standart_users')->select('is_completed')->where('user_id',$userId)->value('is_completed');
            if($flag == 1){
                return response()->json([
                    'instagram' => 0,
                    'success' => 'Welcome back'
                ]);
            }else{
                return response()->json([
                    'error' => 'Missing preferences'
                ]);
            }
        }else{
//            if instagram account
            $token = DB::table('instagram-users')->where('user_id',$userId)->value('access_token');
            $rawData = Curl::to('https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $token)->asJsonResponse()->get();
            $data = array();
            for($i=0;$i < count($rawData->data);$i++){
                $flag = DB::table('images')->where('imageId',$rawData->data[$i]->id)->value('hasFace');
                if($flag == 1 || $flag === null){
                    $data[] = [
                        'image' => $rawData->data[$i]->images->standard_resolution->url,
                        'id' => $rawData->data[$i]->id
                    ];
                }else if($flag == 0){
                }
            };
            return response()->json([
                'instagram' => 1,
                'images' => $data
            ]);
        }
    }

    /**
     * @api {post} /api/user/preferences Preferences Update
     * @apiName UserPreferences
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} body_type User' body type.
     * @apiParam {String} body_style User' body style.
     *
     * @apiSuccess {String} secret Update confirmation.
     * @apiError {String} error  Secret key error
     */

    public function preferences(){
        if(!request()->has('body_type') || !request()->has('body_style') || !request()->has('secret')){
            return response()->json([
                'error' => 'Missing information'
            ]);
        }

        $userId = DB::table('users')->select('id')->where('secret',request('secret'))->value('id');
        DB::table('standart_users')->where('user_id',$userId)->update([
           'body_type' => request('body_type'),
           'body_style' => request('body_style'),
            'is_completed' => 1
        ]);

        return response()->json([
           'success' => 'Information Updated'
        ]);
    }

    /**
     * @api {get} /api/instagram/url Instagram Url
     * @apiName InstagramUrl
     * @apiGroup Instagram
     *
     * @apiSuccess {String} url Instagram url to oAuth.
     */

    public function instagramUrl(){
        return response()->json([
           'url' => 'https://api.instagram.com/oauth/authorize/?client_id=' . env('INSTAGRAM_ID')
               . '&redirect_uri=' . env('INSTAGRAM_URI') . '&response_type=code'
        ]);
    }

    /**
     * @api {post} /api/instagram/oauth Register Instagram User
     * @apiName InstagramOauth
     * @apiGroup Instagram
     *
     * @apiParam {String} code User' instagram code(from callback).
     *
     * @apiSuccess {String} secret Secret token to use in API calls.
     * @apiError {String} error  Secret or Code key error
     */

    public function instagram(){
        if(!request()->has('code') || request()->has('error')){
            return response()->json([
                'error' => 'Missing information'
            ]);
        }
        list($error, $user) =  InstagramController::getToken(request('code'),env('INSTAGRAM_URI'));
        if($error){
            return response()->json([
               'error' => 'Error retrieving instagram token'
            ]);
        }

        if(DB::table('instagram-users')->where('instagram_id',$user->user->id)->exists() == false){
            //User not found on database, so we need to create one.
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            $userId = DB::table('users')->insertGetId([
                'name' => $user->user->username,
                'isInstagram' => true,
                'instagram_id' => $user->user->id,
                'status' => 1,
                'secret' => $token
            ]);
            if(is_null($userId) == true){
                return response()->json([
                    'error' => 'Error Inserting DB'
                ]);
            }else{
                //Now add user to instagram table using that user id.
                DB::table('instagram-users')->insert([
                    'user_id' => $userId,
                    'access_token' => $user->access_token,
                    'username' => $user->user->username,
                    'full_name' => $user->user->full_name,
                    'profile_picture' => $user->user->profile_picture,
                    'instagram_id' => $user->user->id
                ]);
            }
            return response()->json([
                'success' => 'ok',
                'secret' => $token
            ]);
        }else{
            $token = str_random(64);
            while(DB::table('users')->where('secret',$token)->exists() == true){
                $token = str_random(64);
            }
            DB::table('users')->where('instagram_id',$user->user->id)->update([
               'secret' => $token
            ]);
            return response()->json([
                'success' => 'ok',
                'secret' => $token
            ]);
        }
    }

    /**
     * @api {post} /api/logout Logout User
     * @apiName LogoutUser
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} status Confirmation of logout.
     */
    public function logout(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        DB::table('users')->where('secret',request('secret'))->update([
           'secret' => null
        ]);
        return response()->json([
           'success' => 'User logged out'
        ]);
    }

    /**
     * @api {post} /api/user/favorites/add Add User Favorites
     * @apiName UpdateFavorites
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} imageID Image ID to add.
     *
     * @apiSuccess {String} success Confirmation of favorites update.
     */
    public function addFavorite(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }else if(!request()->has('imageID')){
            return response()->json([
                'error' => 'Missing image id'
            ]);
        }
        //Get User id from database
        $userId = DB::table('users')->where('secret',request('secret'))->select('id')->value('id');
        //Let's add favorites to database.
        DB::table('favorites')->insert([
            'userID' => $userId,
            'imageID' => request('imageID')
        ]);
        return response()->json([
           'success' => 'Favorites added'
        ]);
    }
    /**
     * @api {post} /api/user/favorites/remove Remove User Favorites
     * @apiName RemoveFavorites
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} imageID Image ID to add.
     *
     * @apiSuccess {String} success Confirmation of delete request.
     */
    public function removeFavorite(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }else if(!request()->has('imageID')){
            return response()->json([
                'error' => 'Missing image id'
            ]);
        }
        $userId = DB::table('users')->where('secret',request('secret'))->select('id')->value('id');
        DB::table('favorites')->where('userID',$userId)->where('imageID',request('imageID'))->delete();

        return response()->json([
            'success' => 'Image removed from favorites'
        ]);
    }
    /**
     * @api {post} /api/user/favorites/list List User Favorites
     * @apiName GetFavorites
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} success Confirmation of favorites request.
     * @apiSuccess {String} favoritesList List of Favorites
     */
    public function getFavorites(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        $userId = DB::table('users')->where('secret',request('secret'))->select('id')->value('id');
        $favoriteList = DB::table('favorites')->select('imageID')->where('userID',$userId)->get()->toArray();
        return response()->json([
            'success' => 'Favorites received',
            'favoriteList' => $favoriteList
        ]);
    }
    /**
     * @api {post} /api/images/get List User Images
     * @apiName GetImages
     * @apiGroup Images
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} success Confirmation of images request.
     * @apiSuccess {String} images List of images
     */
    public function getImages(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        $userId = DB::table('users')->where('secret',request('secret'))->select('id')->value('id');
        $images = DB::table('images')->select('imageId','type')->where('userId',$userId)->get()->toArray();
        return response()->json([
            "success" => "Imaged retrieved",
            "images" => $images
        ]);
    }
    /**
     * @api {post} /api/images/add Add User Image
     * @apiName AddImage
     * @apiGroup Images
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {File} photo Photo of the User.
     *
     * @apiSuccess {String} success Confirmation.
     * @apiSuccess {String} imageId Added Image Id
     */
    public function addImage(Request $request){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        if(!$request->hasFile('photo') || !$request->file('photo')->isValid()){
            return response()->json([
                'error' => 'Invalid photo'
            ]);
        }
        $file_name = str_random(32);
        //Making sure id not exist in db.
        while(DB::table('images')->where('imageId',$file_name)->exists() == true){
            $file_name = str_random(64);
        }
        $user_id = DB::table('users')->where('secret',request('secret'))->select('id')->value('id');
        $file = $request->file('photo');
        //Write image information to database
        DB::table('images')->insert([
            "userId" => $user_id,
            "imageId" => $file_name,
            "type" => $file->getClientOriginalExtension()
        ]);
        //Finally save file into the disk.
        $file->move(public_path('images'),$file_name . "." . $file->getClientOriginalExtension());
        return response()->json([
           "success" => "Image Added",
           "imageId" => $file_name
        ]);
    }
    /**
     * @api {post} /api/images/remove Remove User Image
     * @apiName RemoveImage
     * @apiGroup Images
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} imageId Requested Image Id.
     *
     * @apiSuccess {String} success Confirmation of remove request.
     */
    public function removeImage(){
        if(!request()->has('secret')){
            return response()->json([
                'error' => 'Missing token'
            ]);
        }
        if(!request()->has('imageId')){
            return response()->json([
                'error' => 'Missing Image ID'
            ]);
        }
        $user_id = DB::table('users')->where('secret',request('secret'))->select('id')->value('id');
        $type = DB::table('images')->select('type')->where('imageId',request('imageId'))->value('type');
        DB::table('images')->select('userId',$user_id)->select('imageId',request('imageId'))->delete();
        unlink(public_path('images') . DS . request('imageId') . "." . $type);
        return response()->json([
           "success" => "File deleted"
        ]);
    }
}
