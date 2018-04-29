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
        if($product = Product::find($productId)->get() == null){
            return 2;
        }
        $favorite = new self();
        $favorite->userId = $userId;
        $favorite->product = $product;
        $favorite->save();
        return $favorite;
    }

    public static function remove($favoriteId,$userId){
        if($favorite = Favorite::where('_id',$favoriteId)->where('userId',$userId)->first()){
            $favorite->delete();
        }else{
            return false;
        }
    }
}
