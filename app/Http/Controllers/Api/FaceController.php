<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Google\Cloud\Vision\VisionClient;
use Illuminate\Support\Facades\DB;

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
}
