<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Google\Cloud\Vision\VisionClient;
use function Sodium\add;
use Vision\Vision;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class FaceController extends Controller
{
    public function face(){
        if(!request('imageId') || !request('type')){
            return response()->json([
                "error" => [
                    "message" => "Missing information",
                    "code" => 1
                ]
            ]);
        }
        if(!file_exists(public_path('images') . DIRECTORY_SEPARATOR . request('imageId') . '.' .request('type'))){
            return response()->json([
                "error" => [
                    "message" => "Image not found",
                    "code" => 3
                ]
            ]);
        }
        //Check if image is already tested.
        $flag = DB::table('images')->where('imageId',request('imageId'))->select('hasFace')->value('hasFace');
        if (!empty($flag)) {
            if ($flag == 0){
                return response()->json([
                    "error" => [
                        "message" => "No face detected",
                        "code" => 4
                    ]
                ]);
            }else{
                return response()->json([
                    "success" => [
                        "message" => "Face detected",
                        "count" => $flag,
                        "code" => 5
                    ]
                ]);
            }
        }
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . storage_path('app' . DIRECTORY_SEPARATOR . 'google.json'));
        $vision = new VisionClient();
        $location = public_path('images') . DIRECTORY_SEPARATOR . request('imageId') . '.' . request('type');
        $image = $vision->image(file_get_contents($location), ['FACE_DETECTION']);
        $result = $vision->annotate($image);
        $faces = $result->faces();
        if ( $faces == null){
            DB::table('images')->where('imageId',request('imageId'))->update([
               "hasFace" => "0"
            ]);
            return response()->json([
                "error" => [
                    "message" => "No face detected",
                    "code" => 4
                ]
            ]);
        }else{
            DB::table('images')->where('imageId',request('imageId'))->update([
                "hasFace" => count($faces)
            ]);
            return response()->json([
                "success" => [
                    "message" => "Face detected",
                    "count" => count($faces),
                    "code" => 5
            ]
            ]);
        }
    }

    public function test(){
        $imageAddress = request('imageId') . "." . request('type');
        if(!file_exists(public_path('images') . DIRECTORY_SEPARATOR . $imageAddress)){
            return response()->json([
                "error" => [
                    "message" => "Image not found",
                    "code" => 3
                ]
            ]);
        }
        //Detect Face
        $vision = new Vision(
            env('CLOUD_VISION_KEY'),
            [
                // See a list of all features in the table below
                // Feature, Limit
                new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100),
            ]
        );

        $imagePath = public_path('images') . DIRECTORY_SEPARATOR . $imageAddress;
        $response = $vision->request(
        // See a list of all image loaders in the table below
            new \Vision\Request\Image\LocalImage($imagePath)
        );

        $faces = $response->getFaceAnnotations();
        $maxX = 0;
        $maxY = 0;
        $minX = 0;
        $minY = 0;
        if(count($faces) != 1){
            return response()->json([
                "labels" => "Not allowed",
                "colors" => "Face count is " . count($faces)
            ]);
        }
        foreach ($faces[0]->getBoundingPoly()->getVertices() as $vertex) {
            if($maxX < $vertex->getX()){
                $minX = $maxX;
                $maxX = $vertex->getX();
            }
            if($maxY < $vertex->getY()){
                $minY = $maxY;
                $maxY = $vertex->getY();
            }
        }

        $width = ($maxX-$minX) * 2;
        $startX = floor($minX - $width /4);
        $startY = $maxY;
        $height = ($maxY-$minY) * 2;
        $image_height = Image::make(public_path('images') . DIRECTORY_SEPARATOR . $imageAddress)->height();
        $image_width = Image::make(public_path('images') . DIRECTORY_SEPARATOR . $imageAddress)->width();
        if($startX < 0 ) {$startX = 0;};
        if($height > $image_height - $maxY){
            $height = $image_height - $maxY;
        }
        if($width > $image_width - $minX){
            $width = $image_width - $minX /2 ;
        }
        $image = Image::make(public_path('images') . DIRECTORY_SEPARATOR . $imageAddress)->crop(floor($width),floor($height),floor($startX),floor($startY));
        $fileName = request('imageId') . ".jpg";
        $image->save(public_path('cropped') . DIRECTORY_SEPARATOR . $fileName);
        $vision = new Vision(
            env('CLOUD_VISION_KEY'),
            [
                // See a list of all features in the table below
                // Feature, Limit
                new \Vision\Feature(\Vision\Feature::IMAGE_PROPERTIES, 100),
            ]
        );
        $imagePath = public_path('cropped') . DIRECTORY_SEPARATOR . $fileName;
        $response = $vision->request(
        // See a list of all image loaders in the table below
            new \Vision\Request\Image\LocalImage($imagePath)
        );
        $colors = $response->getImagePropertiesAnnotation()->getDominantColors();
        $red = $colors[0]->getColor()->getRed();
        $green = $colors[0]->getColor()->getGreen();
        $blue = $colors[0]->getColor()->getBlue();
        $vision = new Vision(
            env('CLOUD_VISION_KEY'),
            [
                // See a list of all features in the table below
                // Feature, Limit
                new \Vision\Feature(\Vision\Feature::LABEL_DETECTION, 100),
            ]
        );
        $imagePath = public_path('cropped') . DIRECTORY_SEPARATOR . $fileName;
        $response = $vision->request(
        // See a list of all image loaders in the table below
            new \Vision\Request\Image\LocalImage($imagePath)
        );
        $labels = $response->getLabelAnnotations();
        $ignoredWords = [
            "black","fashion","button","outwear","clothing","design","shoulder","neck","joint","sleeve","formal wear","collar","fashion model"
        ];
        $counter = 0;
        $temp = [];
        foreach ($labels as $label){
            if(!in_array($label->getDescription(),$ignoredWords) && $counter < 3){
                array_push($temp,$label->getDescription());
                $counter++;
            }
        }
        return response()->json([
            "labels" => $temp,
            "colors" => "rgb($red, $green, $blue)"
        ]);
    }
}
