<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Vision\Vision;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class VisionController extends Controller
{
    public function magic()
    {
        $startTime = $this->getTime();
        $imagePath = public_path('images') . DIRECTORY_SEPARATOR . request('imageId') . "." . request('type');
        if (!file_exists($imagePath)) {
            return response()->json([
                "error" => [
                    "message" => "Image not found",
                    "code" => 3
                ]
            ]);
        }
        $vertexes = $this->detectFace($imagePath, request('imageId'));
        list($startX, $startY, $width, $height) = $this->calculate($vertexes, $imagePath, request('part'));
        list($labels, $red, $green, $blue) = $this->detectArea($imagePath, $width, $height, $startX, $startY, request('part'));
        $seconds = ($this->getTime() - $startTime) / 1000;
        return [
            'success' => [
                "message" => 'Image analyzed.',
                "code" => 5
            ],
            "labels" => $labels,
            "colors" => "rgb($red, $green, $blue)",
            "time" => $seconds
        ];
    }

    private function detectArea($imagePath, $width, $height, $startX, $startY, $part)
    {
        $image = Image::make($imagePath)->crop(floor($width), floor($height), floor($startX), floor($startY));
        $fileName = request('imageId') . '_' . $part . ".jpg";
        $image->save(public_path('cropped') . DIRECTORY_SEPARATOR . $fileName);

        //Second, detect image properties and detect labels
        $vision = new Vision(env('CLOUD_VISION_KEY'), [new \Vision\Feature(\Vision\Feature::IMAGE_PROPERTIES, 100),
            new \Vision\Feature(\Vision\Feature::LABEL_DETECTION, 100)]);
        $imagePath = public_path('cropped') . DIRECTORY_SEPARATOR . $fileName;
        $response = $vision->request(new \Vision\Request\Image\LocalImage($imagePath));
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

    private function calculate($vertexes, $imagePath, $part)
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

    private function detectFace($imagePath, $imageId)
    {
        $vision = new Vision(env('CLOUD_VISION_KEY'), [new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100),]);
        $response = $vision->request(new \Vision\Request\Image\LocalImage($imagePath));
        $faces = $response->getFaceAnnotations();
        if (count($faces) != 1) {
            DB::table('images')->select('imageId', $imageId)->update([
                'isValid' => false
            ]);
            return [
                "labels" => "Image contains more than 1 face.",
                'error' => [
                    "message" => 'Image contains more than 1 face.',
                    "code" => 4
                ]
            ];
        } else {
            DB::table('images')->select('imageId', $imageId)->update([
                'isValid' => true
            ]);
        }
        return $faces[0]->getBoundingPoly()->getVertices();
    }

    private function getTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float)$usec + (float)$sec) * 1000);
    }
}
