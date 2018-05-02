<?php

namespace App\Http\Controllers\Api;

use App\Faagram\User;
use App\Http\Controllers\Controller;
use App\Log;
use App\Product;
use App\Image;

class RecommendationController extends Controller
{
    public static function main($userId)
    {
        $user = \App\User::where('_id', $userId)->first();
        if ($user->type == 1) {
            return RecommendationController::generateProducts(RecommendationController::standard($user), $user->gender);
        } else {
            return RecommendationController::generateProducts(RecommendationController::instagram($user), $user->gender);
        }
    }

    public static function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && RecommendationController::in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }

    public static function generateProducts($items, $genderInt)
    {
        $gender = ($genderInt == 1 ? 'male' : 'female');
        $products = array();
        if (count($items) < 20) {
            $target = 20 - count($items);
            for ($i = 0; $i < $target; $i++) {
                $random = array_random($items, 1);
                array_push($items, [
                    "label" => $random[0]["label"],
                    "color" => $random[0]["color"],
                    "source"=> $random[0]["source"]
                ]);
            }
        } else {
            $items = array_slice($items, 0, 20);
        }
        foreach ($items as $item) {
            $type = $item["label"];
            $color = $item["color"];
            $source = $item["source"];
            $resultArray = Product::where('type', $type)->where('color', $color)->where('gender', $gender)->get()->toArray();
            if(count($resultArray) > 0){
                $number = rand(0, count($resultArray) - 1);
                $k = 0;
                while(RecommendationController::in_array_r($resultArray[$number]["_id"],$products) == true && $k <3){
                    $number = rand(0, count($resultArray) - 1);
                    $k = $k + 1;
                }
                $product = $resultArray[$number];
                array_push($products, [
                    "id" => $product["_id"],
                    "source" => $source,
                    "gender" => $product["gender"],
                    "type" => $product["type"],
                    "brand" => $product["brand"],
                    "color" => $product["color"],
                    "image" => $product["image"],
                    "link" => $product["link"]
                ]);
            }
        }
        return $products;
    }

    public static function standard($user)
    {
        $preferences = explode(',', $user->values);
        $result = array();
        $upload = array();
        $uploadedImages = Image::where('userId', $user->_id)->where('enabled', true)->where('style', null)->get()->toArray();
        foreach ($uploadedImages as $uploadedImage) {
            if (count($uploadedImage["part1"]) > 0) {
                $image = new \stdClass();
                $image->label = $uploadedImage["part1"]["label"];
                $image->color = $uploadedImage["part1"]["color"];
                $image->_id = $uploadedImage["_id"];
                array_push($upload, $image);
            }
            if (count($uploadedImage["part2"]) > 0) {
                $image = new \stdClass();
                $image->label = $uploadedImage["part2"]["label"];
                $image->color = $uploadedImage["part2"]["color"];
                $image->_id = $uploadedImage["_id"];
                array_push($upload, $image);
            }
            if (count($uploadedImage["part3"]) > 0) {
                $image = new \stdClass();
                $image->label = $uploadedImage["part3"]["label"];
                $image->color = $uploadedImage["part3"]["color"];
                $image->_id = $uploadedImage["_id"];
                array_push($upload, $image);
            }
        }
        $styles = Image::where('userId', $user->_id)->where('enabled', true)->where('style', true)->get()->toArray();
        $desiredUpload = (Integer)round($preferences[0] / 5);
        if (count($upload) < $desiredUpload && $desiredUpload > 0) {
            foreach ($upload as $item) {
                array_push($result, [
                    "label" => $item->label,
                    "color" => $item->color,
                    "source" => 1
                ]);
            }
        } else if ($desiredUpload > 0) {
            for ($i = 0; $i < $desiredUpload; $i++) {
                $random = array_random($upload, 1)[0];
                array_push($result, [
                    "label" => $random->label,
                    "color" => $random->color,
                    "source" => 1
                ]);
            }
        }
        $desiredStyle = (Integer)round($preferences[1] / 5);
        if (count($styles) < $desiredStyle) {
            foreach ($styles as $style) {
                array_push($result, [
                    "label" => $style["label"],
                    "color" => $style["color"],
                    "source" => 2
                ]);
            }
        } else {
            for ($i = 0; $i < $desiredStyle; $i++) {
                $random = array_random($styles, 1);
                array_push($result, [
                    "label" => $random[0]["label"],
                    "color" => $random[0]["color"],
                    "source" => 2
                ]);
            }
        }
        return $result;
    }

    public static function instagram($user)
    {
        $preferences = explode(',', $user->values);
        $faagramId = $user->faagramId;
        $posts = User::getPosts($faagramId);
        $likes = User::getLikes($faagramId);
        $followingPosts = User::getFollowingPosts($faagramId);
        $desiredPost = intval(round($preferences[0] / 5));
        $desiredLike = intval(round($preferences[1] / 5));
        $desiredFollowing = intval(round($preferences[2] / 5));
        $result = array();
        if (count($posts) < $desiredPost && $desiredPost > 0) {
            foreach ($posts as $post) {
                array_push($result, [
                    "label" => $post["label"],
                    "color" => $post["color"],
                    "source" => 1
                ]);
            }
        } else if ($desiredPost > 0) {
            for ($i = 0; $i < $desiredPost; $i++) {
                $random = array_random($posts, 1)[0];
                array_push($result, [
                    "label" => $random["label"],
                    "color" => $random["color"],
                    "source" => 1
                ]);
            }
        }
        if ($desiredLike != 0) {
            for ($i = 0; $i < $desiredLike; $i++) {
                $random = array_random($likes, 1)[0];
                array_push($result, [
                    "label" => $random["label"],
                    "color" => $random["color"],
                    "source" => 2
                ]);
            }
        }
        if ($desiredFollowing != 0) {
            for ($i = 0; $i < $desiredFollowing; $i++) {
                $random = array_random($followingPosts, 1)[0];
                array_push($result, [
                    "label" => $random["label"],
                    "color" => $random["color"],
                    "source" => 3
                ]);
            }
        }
        return $result;
    }
}
