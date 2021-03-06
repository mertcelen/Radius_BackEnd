<?php

namespace App\Http\Controllers\Api;

use App\Faagram\Post;
use App\Http\Controllers\Controller;
use App\Log;
use App\User;
use Vision\Vision;
use Intervention\Image\ImageManagerStatic as Image;

class VisionController extends Controller
{
    public static function magic($imageId, $part, $userId)
    {
        $imagePath = storage_path('images') . DIRECTORY_SEPARATOR . $imageId . ".jpg";
        $vertexes = VisionController::detectFace($imagePath);
        if ($vertexes == null) {
            \App\Image::where('imageId', $imageId)->update([
                'enabled' => false
            ]);
            return false;
        }
        try {
            list($startX, $startY, $width, $height) = VisionController::calculate($vertexes, $imagePath, $part);
            list($label, $red, $green, $blue) = VisionController::detectArea($imagePath, $width, $height, $startX, $startY, $part, $imageId);
            $color = VisionController::rgbToText(array($red, $green, $blue));
        } catch (\Exception $e) {
            Log::add("vision", $e->getMessage(), $userId);
            return false;
        }
        $user = User::where('_id', $userId)->first();
        if ($user["type"] == 2) {
            $newPost = new Post();
            $newPost->userId = $user["faagramId"];
            $newPost->label = $label;
            $newPost->color = $color;
            $newPost->like_count = 0;
            $newPost->imageId = $imageId;
            $newPost->save();
        } else {
            \App\Image::where('imageId', $imageId)->update([
                'enabled' => true,
                'part' . $part => [
                    'color' => $color,
                    'label' => $label
                ]
            ]);
        }
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
//        unlink($path);
        $colors = $response->getImagePropertiesAnnotation()->getDominantColors();
        $red = $colors[0]->getColor()->getRed();
        $green = $colors[0]->getColor()->getGreen();
        $blue = $colors[0]->getColor()->getBlue();
        $labels = $response->getLabelAnnotations();
        $ignoredWords = [
            "footwear",
            "fashion model",
            "fashion",
            "sunglasses",
            "tights",
            "leg",
            "shoulder",
            "girl",
            "thigh",
            "joint",
            "textile",
            "leggings",
            "spandex",
            "eyewear",
            "human",
            "shoe",
            "standing",
            "road",
            "active",
            "knee",
            "muscle",
            "abdomen",
            "hip",
            "calf",
            "clothing",
            "socialite",
            "supermodel",
            "catwalk",
            "pattern",
            "red",
            "costume",
            "white",
            "sleeve",
            "neck",
            "trunk",
            "denim",
            "material",
            "outerwear",
            "yellow",
            "trench",
            "cocktail",
            "professional",
            "top",
            "haute",
            "flooring",
            "runway",
            "gown",
            "cobalt",
            "electric",
            "pocket",
            "blue",
            "product",
            "leather",
            "hood",
            "hoodie",
            "polar",
            "zipper",
            "sweatshirt",
            "puffer",
            "woolen",
            "button",
            "green",
            "bridal",
            "wedding",
            "day",
            "black",
            "little",
            "design",
            "tartan",
            "plaid",
            "text",
            "orange",
            "font",
            "logo",
            "brand",
            "long",
            "symbol",
            "jersey",
            "sports",
            "angle",
            "collar",
            "magenta",
            "bermuda",
            "trunks",
            "swim",
            "swimsuit",
            "underpants",
            "undergarment",
            "skort",
            "tuxedo",
            "gentleman",
            "sweater",
            "vision",
            "glasses",
            "photo",
            "fun",
            "snapshot",
            "winter",
            "fur",
            "street",
            "brown",
            "walking",
            "beauty",
            "hairstyle",
            "model",
            "hair",
            "beige",
            "headgear",
            "cap",
            "blond",
            "beanie",
            "car",
            "sitting",
            "browns",
            "handbag",
            "bag",
            "peach",
            "purple",
            "violet",
            "businessperson",
            "plant",
            "grass",
            "infrastructure",
            "recreation",
            "asphalt",
            "pedestrian",
            "polo",
            "carpet",
            "pink",
            "competition",
            "smile",
            "cool",
            "health",
            "lady",
            "summer",
            "tree",
            "path",
            "trail",
            "vacation",
            "wood",
            "leisure",
            "forest",
            "outdoor",
            "park",
            "furniture",
            "interior",
            "table",
            "water",
            "flower",
            "woody",
            "cherry",
            "spring",
            "blossom",
            "swimwear",
            "lingerie",
            "beach",
            "sea",
            "sky",
            "rock",
            "horizon",
            "coast",
            "terrain",
            "ocean",
            "cliff",
            "mountain",
            "sunlight",
            "sunrise",
            "calm",
            "evening",
            "sand",
            "hill",
            "sunset",
            "landscape",
            "cloud",
            "physical",
            "happiness",
            "sun",
            "swimming",
            "arm",
            "hand",
            "one",
            "brassiere",
            "swimmer",
            "back",
            "tv",
            "polka",
            "polka dot",
            "rose",
            "floristry",
            "boutique",
            "vehicle",
            "motor",
            "automotive",
            "luxury",
            "personal",
            "executive",
            "wheel",
            "mercedes",
            "family",
            "city",
            "mid",
            "sport",
            "sedan",
            "tire",
            "compact",
            "chair",
            "military",
            "army",
            "camouflage",
            "soldier",
            "gray",
            "aqua",
            "maroon",
            "fashion accessory",
            "active undergarment",
            "fashion design",
            "active undergarment",
            "haute couture",
            "fashion show",
            "cobalt blue",
            "electric blue",
            "active pants",
            "leather jacket",
            "polar fleece",
            "bridal party dress",
            "bridal clothing",
            "wedding dress",
            "little black dress",
            "active shirt",
            "long sleeved t shirt",
            "long sleeve t shirt",
            "sports uniform",
            "product design",
            "bermuda shorts",
            "active shorts",
            "swim brief",
            "human leg",
            "swimsuit bottom",
            "dress shirt",
            "long hair",
            "vision care",
            "human hair color",
            "black hair",
            "brown hair",
            "hair coloring",
            "photo shoot",
            "string instruments",
            "necktie",
            "string instrument",
            "clothing",
            "t shirt",
            "polo shirt",
            "product",
            "purple",
            "outerwear",
            "fashion",
            "gentleman",
            "cardigan",
            "sweater",
            "jersey",
            "sportswear",
            "long sleeved",
            "joint",
            "design",
            "pattern",
            "blouse",
            "hoodie",
            "scarf",
            "headgear",
            "beanie",
            "cap",
            "fur",
            "hat",
            "blue",
            "trousers",
            "businessperson",
            "child",
            "bridal party",
            "flower girl",
            "material",
            "wool",
            "black hair",
            "long hair",
            "brown hair",
            "little black",
            "car",
            "fun",
            "technology",
            "professional",
            "hair",
            "glasses",
            "person",
            "man",
            "facial hair",
            "vision care",
            "chin",
            "eyewear",
            "moustache",
            "forehead",
            "beard",
            "smile",
            "cool"
        ];
        $temp = "";
        foreach ($labels as $label) {
            if (!in_array($label->getDescription(), $ignoredWords)) {
                $temp = $label->getDescription();
                break;
            }
        }
        switch ($temp){
            case "jeans":
                $temp = "trousers";
                break;
            case "waist":
            case "miniskirt":
                $temp = "skirt";
                break;
            case "blouse":
                $temp = "shirt";
                break;
            case "t shirt":
            case "undershirt":
                $temp = "t-shirt";
                break;
            case "shorts":
                $temp = "short";
                break;
            case "blazer":
                $temp = "jacket";
                break;
            case "trench coat":
            case "overcoat":
                $temp = "coat";
                break;
            case "day dress":
            case "cocktail dress":
                $temp = "dress";
                break;
            case "formal wear":
                $temp = "suit";
                break;
            default:
                break;
        };
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
