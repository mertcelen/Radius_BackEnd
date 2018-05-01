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

    public static function generateProducts($items, $genderInt)
    {
        $gender = ($genderInt == 1 ? 'male' : 'female');
        $products = array();
        if (count($items) < 20) {
            $target = 20 - count($items);
            for ($i = 0; $i < $target; $i++) {
                $random = array_random($items, 1);
                array_push($items, ["label" => $random[0]["label"], "color" => $random[0]["color"]]);
            }
        }
        foreach ($items as $item) {
            $type = $item["label"];
            $color = $item["color"];
            $product = Product::where('type', $type)->where('color', $color)->where('gender', $gender)->first();
            array_push($products, [
                "type" => $product["type"],
                "brand" => $product["brand"],
                "color" => $product["color"],
                "link" => $product["link"],
                "image" => $product["image"],
                "gender" => $product["gender"]
            ]);
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
        $style = Image::where('userId', $user->_id)->where('enabled', true)->where('style', true)->get()->toArray();
        $desiredUpload = (Integer)round($preferences[0] / 5);
        if (count($upload) < $desiredUpload) {
            foreach ($upload as $item) {
                array_push($result, [
                    "label" => $item->label,
                    "color" => $item->color
                ]);
            }
        } else {
            $temp = array();
            for ($i = 0; $i < $desiredUpload; $i++) {
                $random = array_random($upload, 1)[0];
                while (in_array($random->_id, $temp)) {
                    $random = array_random($upload, 1);
                }
                array_push($result, [
                    "label" => $random->label,
                    "color" => $random->color
                ]);
            }
        }
        $desiredStyle = 20 - count($result);
        if (count($style) < $desiredStyle) {
            $result = array_merge($result, $style);
        } else {
            $temp = array();
            for ($i = 0; $i < $desiredStyle; $i++) {
                $random = array_random($style, 1);
                while (in_array($random[0]["_id"], $temp)) {
                    $random = array_random($style, 1);
                }
                array_push($result, [
                    "label" => $random[0]["label"],
                    "color" => $random[0]["color"]
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
        $desiredPost = round($preferences[0] / 5);
        $desiredLike = round($preferences[1] / 5);
        $desiredFollowing = round($preferences[2] / 5);
        $result = array();
        $post_count = count($posts);
        if ($post_count < $desiredPost) {
            $result = array_merge($posts, $result);
        } else {
            $result = array_random($posts, $desiredPost);
            $post_count = $desiredPost;
        }
        $likecount = round((20 - ($post_count)) / 2);
        $result = array_merge(array_random($likes, $likecount), $result);
        $followingCount = 20 - $post_count - $likecount;
        $result = array_merge(array_random($followingPosts, $followingCount), $result);
        return $result;
    }
}
