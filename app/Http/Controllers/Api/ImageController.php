<?php

namespace App\Http\Controllers\Api;

use App\Faagram\Post;
use App\Favorite;
use App\Http\Controllers\Controller;
use App\Jobs\CloudVision;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class ImageController extends Controller
{
    /**
     * @api {get} /api/image List User Images
     * @apiName GetImages
     * @apiGroup Images
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} images List of images
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public static function get()
    {
        $images = \App\Image::where('userId', request('userId'))
            ->where('enabled',true)->select('imageId')->orderBy('id','DESC')->get()->toArray();
        return [
            'success' => [
                "message" => 'Images retrieved.',
                "code" => 5
            ],
            "images" => $images
        ];
    }

    /**
     * @api {post} /api/user/favorites Add User Favorites
     * @apiName UpdateFavorites
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} productID Product ID to add.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function addFavorite()
    {
        if ($result = Favorite::add(request('userId'), request('productId')) == 3) {
            return [
                'success' => [
                    "message" => 'Image added to favorites.',
                    "code" => 5
                ]
            ];
        } else if ($result == 1) {
            return [
                'error' => [
                    "message" => 'Image already added.',
                    "code" => 4
                ]
            ];
        } else {
            return [
                'error' => [
                    "message" => 'Product not found.',
                    "code" => 4
                ]
            ];
        }
    }

    /**
     * @api {delete} /api/user/favorites Remove User Favorites
     * @apiName RemoveFavorites
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} favoriteId Favorite ID to remove.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function removeFavorite()
    {
        if (Favorite::remove(request('favoriteId'), request('userId'))) {
            return [
                'success' => [
                    "message" => 'Recommendation successfully removed from favorites.',
                    "code" => 5
                ]
            ];
        } else {
            return [
                'error' => [
                    "message" => 'Recommendation not found in favorites.',
                    "code" => 4
                ]
            ];
        }
    }

    /**
     * @api {get} /api/user/favorites List User Favorites
     * @apiName GetFavorites
     * @apiGroup User
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {String} favoritesList List of Favorites
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function getFavorites()
    {
        $favoriteList = Favorite::where('userId', request('userId'))->get();
        return [
            'success' => [
                "message" => 'Favorite images retrieved.',
                "code" => 5
            ],
            'favoriteList' => $favoriteList
        ];
    }

    /**
     * @api {post} /api/image Add User Image
     * @apiName AddImage
     * @apiGroup Images
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {File} image Image of the User.
     *
     * @apiSuccess {String} imageId Added Image Id
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function add()
    {
        $imageId = str_random(16);
        while (!empty(\App\Image::where('imageId', $imageId)->get()->toArray())) {
            $imageId = str_random(16);
        }
        $image = Image::make(Input::file('image'));
        //Save original file
        $image->save(storage_path('images') . DIRECTORY_SEPARATOR . $imageId . ".jpg");
        //Save thumbnail
        $image->fit(600, 600)->save(public_path('thumb') . DIRECTORY_SEPARATOR . $imageId . ".jpg");
        // Write image information to database
        $mongoImage = new \App\Image();
        $mongoImage->imageId = $imageId;
        $mongoImage->userId = request('userId');
        $mongoImage->enabled = true;
        $mongoImage->part1 = array();
        $mongoImage->part2 = array();
        $mongoImage->part3 = array();
        $mongoImage->save();
        for ($i = 1; $i <= 3; $i++) {
            $job = (new CloudVision(request('userId'), $imageId, (String)$i));
            dispatch($job);
        }
        return [
            'success' => [
                "message" => 'Images added.',
                "code" => 5
            ],
            "imageId" => $imageId
        ];
    }

    /**
     * @api {delete} /api/image Remove User Image
     * @apiName RemoveImage
     * @apiGroup Images
     *
     * @apiParam {String} secret User' secret key.
     * @apiParam {String} imageId Requested Image Id.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function remove()
    {
        $flag = empty(\App\Image::where('imageId', request('imageId'))->get()->toArray());
        if ($flag == true) {
            return [
                'error' => [
                    "message" => 'Image not found.',
                    "code" => 3
                ]
            ];
        }
        \App\Image::where('imageId', request('imageId'))->update([
            'enabled' => false
        ]);
        Post::where('imageId', request('imageId'))->delete();
        unlink(storage_path('images') . DIRECTORY_SEPARATOR . request('imageId') . ".jpg");
        unlink(public_path('thumb') . DIRECTORY_SEPARATOR . request('imageId') . ".jpg");
        return [
            'success' => [
                "message" => 'Image removed.',
                "code" => 5
            ]
        ];
    }

}
