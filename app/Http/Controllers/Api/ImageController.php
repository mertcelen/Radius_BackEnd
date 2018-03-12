<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
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
        try{
            DB::table('favorites')->insert([
                'userId' => request('userId'),
                'imageId' => request('imageId')
            ]);
        }catch(\Exception $e){
            return [
                'error' => [
                    "message" => 'Image already added.',
                    "code" => 4
                ]
            ];
        }
        return [
            'success' => [
                "message" => 'Image added to favorites.',
                "code" => 5
            ]
        ];
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
        try{
            DB::table('favorites')->where('userId',request('userId'))->where('imageId',request('imageId'))->delete();
        }catch(\Exception $e){
            return [
                'error' => [
                    "message" => 'Image not found in favorites.',
                    "code" => 4
                ]
            ];
        }
        return [
            'success' => [
                "message" => 'Image successfully removed from favorites.',
                "code" => 5
            ]
        ];
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
        $favoriteList = DB::table('favorites')->select('imageID')->where('userId',request('userId'))->get()->toArray();
        return [
            'success' => [
                "message" => 'Favorite images retrieved.',
                "code" => 5
            ],
            'favoriteList' => $favoriteList
        ];
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
        $images = DB::table('images')->select('imageId','type')->where('userId',request('userId'))->get()->toArray();
        return [
            'success' => [
                "message" => 'Images retrieved.',
                "code" => 5
            ],
            "images" => $images
        ];
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
    public function addImage(){
        $file_name = str_random(32);
        //Making sure id not exist in db.
        while(DB::table('images')->where('imageId',$file_name)->exists() == true){
            $file_name = str_random(32);
        }
        $file = request()->file('photo');
        //Write image information to database
        DB::table('images')->insert([
            "userId" => request('userId'),
            "imageId" => $file_name,
            "type" => $file->getClientOriginalExtension()
        ]);
        //Finally save file into the disk.
        $file->move(public_path('images'),$file_name . "." . $file->getClientOriginalExtension());
        return [
            'success' => [
                "message" => 'Images added.',
                "code" => 5
            ],
            "imageId" => $file_name
        ];
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
        $type = DB::table('images')->select('type')->where('imageId',request('imageId'))->value('type');
        if($type == null){
            return [
                'error' => [
                    "message" => 'Image not found.',
                    "code" => 3
                ]
            ];
        }

        DB::table('images')->where('imageId',request('imageId'))->where('userId',request('userId'))->delete();
        unlink(public_path('images') . DIRECTORY_SEPARATOR . request('imageId') . "." . $type);
        return [
            'success' => [
                "message" => 'Image removed.',
                "code" => 5
            ]
        ];
    }
}
