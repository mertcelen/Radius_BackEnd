<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Favorite extends Eloquent
{
    protected $collection = 'favorites';
    protected $connection = 'mongodb';

    public static function add($userId,$productId){

        if(Favorite::where('userId',$userId)->where('productId',$productId)->exists()){
            return 1;
        }
        if($product = Product::where('_id',$productId)->exists() == false){
            return 2;
        }
        $product = Product::where('_id',$productId)->first();
        $favorite = new self();
        $favorite->userId = $userId;
        $favorite->product = [
            "id" => $product->_id,
            "imageId" => $product->image,
            "purchaseLink" => $product->link
        ];
        $favorite->save();
        return 3;
    }

    public static function remove($favoriteId,$userId){
        if($favorite = Favorite::where('_id',$favoriteId)->where('userId',$userId)->first()){
            $favorite->delete();
            return true;
        }else{
            return false;
        }
    }
}
