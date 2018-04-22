<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Vision\Vision;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Jobs\CloudVision;

class VisionController extends Controller
{
    /**
     * @api {post} /api/magic Cloth Detection
     * @apiName ClothDetection
     * @apiGroup Detection
     *
     * @apiParam {String} secret User' secret key.
     *
     * @apiSuccess {Array} success Success response with message and code.
     * @apiError   {Array} error Error response with message and code.
     */
    public function detect()
    {
        $images = \App\Image::where('userId', request('userId'))->where('enabled', true)->get();
        foreach ($images as $image) {
            for ($i = 1; $i <= 3; $i++) {
                $job = (new CloudVision(request('userId'), $image->imageId, (String)$i));
                dispatch($job);
            }
        }
        return [
            'success' => [
                "message" => 'Cloth detection is requested, it is going to work background because it will take a while.',
                "code" => 5
            ],
        ];
    }

    public static function magic($imageId, $part, $userId)
    {
        $imagePath = public_path('images') . DIRECTORY_SEPARATOR . $imageId . ".jpg";
        if (!file_exists($imagePath)) {
            return 0;
        }
        if (\App\Image::where('imageId', $imageId)->first()->enabled == false) {
            return 0;
        }
        if (!empty(\App\Image::where('imageId', $imageId)->where('part' . $part, 'exists', true)->get()->toArray())) {
            return 1;
        }
        $vertexes = VisionController::detectFace($imagePath, $imageId, $userId);
        if ($vertexes == null) {
            return 0;
        }
        list($startX, $startY, $width, $height) = VisionController::calculate($vertexes, $imagePath, $part);
        list($labels, $red, $green, $blue) = VisionController::detectArea($imagePath, $width, $height, $startX, $startY, $part, $imageId);
        $color = VisionController::rgbToText(array($red, $green, $blue));
        \App\Image::where('imageId', $imageId)->update([
            'part' . $part => [
                'color' => $color,
                'label' => implode(',', $labels)
            ]
        ]);
        return 1;
    }

    private static function detectArea($imagePath, $width, $height, $startX, $startY, $part, $imageId)
    {
        $image = Image::make($imagePath)->crop(floor($width), floor($height), floor($startX), floor($startY));
        $fileName = $imageId . '_' . $part . ".jpg";
        $image->save(public_path('cropped') . DIRECTORY_SEPARATOR . $fileName);
        //Second, detect image properties and detect labels
        $vision = new Vision(env('CLOUD_VISION_KEY'), [new \Vision\Feature(\Vision\Feature::IMAGE_PROPERTIES, 100),
            new \Vision\Feature(\Vision\Feature::LABEL_DETECTION, 100)]);
        $imagePath = public_path('cropped') . DIRECTORY_SEPARATOR . $fileName;
        $response = $vision->request(new \Vision\Request\Image\LocalImage($imagePath));
        unlink(public_path('cropped') . DIRECTORY_SEPARATOR . $fileName);
        $colors = $response->getImagePropertiesAnnotation()->getDominantColors();
        $red = $colors[0]->getColor()->getRed();
        $green = $colors[0]->getColor()->getGreen();
        $blue = $colors[0]->getColor()->getBlue();

        $labels = $response->getLabelAnnotations();
        $ignoredWords = [
            "black", "fashion", "button", "outwear", "clothing", "design", "shoulder", "neck", "joint", "sleeve", "formal wear", "collar", "fashion model"
        ];
        $counter = 0;
        $temp = [];
        foreach ($labels as $label) {
            if (!in_array($label->getDescription(), $ignoredWords) && $counter < 3) {
                array_push($temp, $label->getDescription());
                $counter++;
            }
        }
        return array($temp, $red, $green, $blue);
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

    private static function detectFace($imagePath, $imageId, $userId)
    {
        $vision = new Vision(env('CLOUD_VISION_KEY'), [new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100),]);
        $response = $vision->request(new \Vision\Request\Image\LocalImage($imagePath));
        $faces = $response->getFaceAnnotations();
        if (empty($faces) || count($faces) != 1) {
            \App\Image::where('imageId', $imageId)->update([
                'enabled' => false
            ]);
            return null;
        }

        return $faces[0]->getBoundingPoly()->getVertices();
    }

    private static function rgbToText($color)
    {

        $array['white'] = array(255, 255, 255);
        $array['silver'] = array(191, 191, 191);
        $array['gray'] = array(127.5, 127.5, 127.5);
        $array['black'] = array(0, 0, 0);
        $array['red'] = array(255, 0, 0);
        $array['maroon'] = array(127.5, 0, 0);
        $array['yellow'] = array(255, 255, 0);
        $array['olive'] = array(127.5, 127.5, 0);
        $array['lime'] = array(0, 255, 0);
        $array['green'] = array(0, 255, 0);
        $array['aqua'] = array(0, 255, 255);
        $array['teal'] = array(0, 127.5, 127.5);
        $array['blue'] = array(0, 0, 255);
        $array['navy'] = array(0, 0, 127.5);
        $array['fuchsia'] = array(255, 0, 255);
        $array['purple'] = array(127.5, 0, 127.5);

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
