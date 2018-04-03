<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $job = (new CloudVision(request('userId')));
        dispatch($job);
        return [
            'success' => [
                "message" => 'Cloth detection is requested, it is going to work background because it will take a while.',
                "code" => 5
            ],
        ];
    }

    public static function magic($imageId, $part, $userId)
    {
        $startTime = VisionController::getTime();
        $imagePath = public_path('images') . DIRECTORY_SEPARATOR . $imageId . ".jpg";
        if (!file_exists($imagePath)) {
            return response()->json([
                "error" => [
                    "message" => "Image not found",
                    "code" => 3
                ]
            ]);
        }
        //Retrieving image details from the database.
        $result = DB::table('images')->select(['red' . $part . " AS red", 'green' . $part . " AS green", 'blue' . $part
            . " AS blue", 'labels' . $part . " AS labels", 'time' . $part . " AS time"])->where('imageId', $imageId)
            ->where('isValid', true)->first();
        // To specifically check if that part is checked before or not.
        if (!empty($result) && $result->red != null) {
            return [
                'success' => [
                    "message" => 'Image analyzed.',
                    "code" => 5
                ],
                "labels" => $result->labels,
                "colors" => "rgb($result->red, $result->green, $result->blue)",
                "time" => $result->time
            ];
        }

        $vertexes = VisionController::detectFace($imagePath, $imageId, $userId);
        if ($vertexes == null) {
            return 0;
        }
        list($startX, $startY, $width, $height) = VisionController::calculate($vertexes, $imagePath, $part);
        list($labels, $red, $green, $blue) = VisionController::detectArea($imagePath, $width, $height, $startX, $startY, $part, $imageId);
        $seconds = (VisionController::getTime() - $startTime) / 1000;
        //Now that we have everything, we can update the image data in the database.
        DB::table('images')->where('imageId', $imageId)->update([
            'isValid' => true,
            'red' . $part => $red,
            'green' . $part => $green,
            'blue' . $part => $blue,
            'labels' . $part => implode(',', $labels),
            'time' . $part => $seconds
        ]);
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
            PhotoController::remove($imageId, $userId);
            return null;
        }
        DB::table('images')->select('imageId', $imageId)->update([
            'isValid' => 1
        ]);
        return $faces[0]->getBoundingPoly()->getVertices();
    }

    private static function getTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float)$usec + (float)$sec) * 1000);
    }
}
