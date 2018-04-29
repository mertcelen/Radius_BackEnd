<?php

namespace App\Http\Controllers\Api;

use App\Faagram\Post;
use App\Http\Controllers\Controller;
use App\User;
use Vision\Vision;
use Intervention\Image\ImageManagerStatic as Image;

class VisionController extends Controller
{
    public static function magic($imageId, $part, $userId)
    {
        $imagePath = storage_path('images') . DIRECTORY_SEPARATOR . $imageId . ".jpg";
        $vertexes = VisionController::detectFace($imagePath);
        if ($vertexes == null){
            \App\Image::where('imageId',$imageId)->update([
                'enabled' => false
            ]);
            return false;
        }
        list($startX, $startY, $width, $height) = VisionController::calculate($vertexes, $imagePath, $part);
        list($label, $red, $green, $blue) = VisionController::detectArea($imagePath, $width, $height, $startX, $startY, $part, $imageId);
        $color = VisionController::rgbToText(array($red, $green, $blue));
        \App\Image::where('imageId', $imageId)->update([
            'enabled' => true,
            'part' . $part => [
                'color' => $color,
                'label' => $label
            ]
        ]);
        $newPost = new Post();
        $newPost->userId = User::find($userId)->first()->faagramId;
        $newPost->label = $label;
        $newPost->color = $color;
        $newPost->like_count = 0;
        $newPost->imageId = $imageId;
        $newPost->save();
        return true;
    }

    private static function detectFace($imagePath)
    {
        $vision = new Vision(env('CLOUD_VISION_KEY'), [new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100)]);
        $response = $vision->request(new \Vision\Request\Image\LocalImage($imagePath));
        $faces = $response->getFaceAnnotations();
        if (count($faces) != 1) {
            return null;
        }
        return $faces[0]->getBoundingPoly()->getVertices();
    }

    private static function calculate($vertexes, $imagePath, $part)
    {
        $maxX = 0;
        $maxY = 0;
        $minX = 0;
        $minY = 0;
        foreach ($vertexes as $vertex) {
            if ($maxX < $vertex->getX()) {
                $minX = $maxX;
                $maxX = $vertex->getX();
            }
            if ($maxY < $vertex->getY()) {
                $minY = $maxY;
                $maxY = $vertex->getY();
            }
        }
        $width = ($maxX - $minX) * 2;
        $startX = floor($minX - $width / 4);
        //Part 1 is only upper, part 2 is lower, part 3 is full body.
        switch ($part) {
            case 1:
                $startY = $maxY;
                $height = ($maxY - $minY) * 2;
                break;
            case 2:
                $startY = $maxY + ($maxY - $minY) * 2;
                $height = ($maxY - $minY) * 2;
                break;
            default:
                $startY = $maxY;
                $height = ($maxY - $minY) * 4;
                break;
        }
        $image_height = Image::make($imagePath)->height();
        $image_width = Image::make($imagePath)->width();
        if ($startX < 0) {
            $startX = 0;
        };
        if ($height > $image_height - $startY) {
            $height = $image_height - $startY;
        }
        if ($width > $image_width - $minX) {
            $width = $image_width - $minX / 2;
        }
        return array($startX, $startY, $width, $height);
    }

    private static function detectArea($imagePath, $width, $height, $startX, $startY, $part, $imageId)
    {
        $image = Image::make($imagePath)->crop(floor($width), floor($height), floor($startX), floor($startY));
        $fileName = $imageId . '_' . $part . ".jpg";
        $path = public_path('cropped') . DIRECTORY_SEPARATOR . $fileName;
        $image->save($path);
        //Second, detect image properties and detect labels
        $vision = new Vision(env('CLOUD_VISION_KEY'), [new \Vision\Feature(\Vision\Feature::IMAGE_PROPERTIES, 100),
            new \Vision\Feature(\Vision\Feature::LABEL_DETECTION, 100)]);
        $response = $vision->request(new \Vision\Request\Image\LocalImage($path));
        unlink($path);
        $colors = $response->getImagePropertiesAnnotation()->getDominantColors();
        $red = $colors[0]->getColor()->getRed();
        $green = $colors[0]->getColor()->getGreen();
        $blue = $colors[0]->getColor()->getBlue();
        $labels = $response->getLabelAnnotations();
        $ignoredWords = [
            "black", "fashion", "button", "outwear", "clothing", "design", "shoulder",
            "neck", "joint", "sleeve", "formal wear", "collar", "fashion model",
            "polka dot", "pattern", "white", "product"
        ];
        $temp = "";
        foreach ($labels as $label) {
            if (!in_array($label->getDescription(), $ignoredWords)) {
                $temp = $label->getDescription();
                break;
            }
        }
        return array($temp, $red, $green, $blue);
    }

    private static function rgbToText($color)
    {

        $array['white'] = array(255, 255, 255);
        $array['beige'] = array(245, 245, 220);
        $array['gray'] = array(127.5, 127.5, 127.5);
        $array['black'] = array(0, 0, 0);
        $array['red'] = array(255, 0, 0);
        $array['maroon'] = array(127.5, 0, 0);
        $array['yellow'] = array(255, 255, 0);
        $array['green'] = array(0, 255, 0);
        $array['aqua'] = array(0, 255, 255);
        $array['blue'] = array(0, 0, 255);
        $array['purple'] = array(127.5, 0, 127.5);
        $array['pink'] = array(255, 102, 178);

        $deviation = PHP_INT_MAX;
        $colorName = '';
        foreach ($array as $title => $current) {
            $curDev = VisionController::compareColors($current, $color);
            if ($curDev < $deviation) {
                $deviation = $curDev;
                $colorName = $title;
            }
        }
        return $colorName;
    }

    private static function compareColors($colorA, $colorB)
    {
        return abs($colorA[0] - $colorB[0]) + abs($colorA[1] - $colorB[1]) + abs($colorA[2] - $colorB[2]);
    }
}
