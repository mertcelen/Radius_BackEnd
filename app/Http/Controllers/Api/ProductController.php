<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    public function main()
    {
        ;
        $array = DB::connection('mysql_clothes')->table('product')->get()->toArray();
        $pretty = Array();
        foreach ($array as $product) {
            array_push($pretty, [
                'color' => $product->COLORID,
                'type' => $product->TYPEID,
                'image' => $product->IMAGE,
                'link' => $product->LINK,
                'brand' => $product->BRANDID
            ]);
        }
        return $pretty;
    }

    public function get()
    {
        if (!request()->has('type') && !request()->has('color')) {
            return Array();
        }
        $array = DB::connection('mysql_clothes')->table('product')
            ->where('TYPEID', request('type'))->where('COLORID', request('color'))
            ->get()->toArray();
        $pretty = Array();
        foreach ($array as $product) {
            array_push($pretty, [
                'color' => request('color'),
                'type' => request('type'),
                'image' => $product->IMAGE,
                'link' => $product->LINK,
                'brand' => $product->BRANDID
            ]);
        }
        return $pretty;
    }

    public function add()
    {
        if (!request()->has('type') || !request()->has('color')
            || !request()->has('brand') || !request()->has('link')
            || !request()->has('image') || strlen(request('image')) == 0
            || strlen(request('link') == 0)){
            return [
                'error' => [
                    "message" => 'Missing information(s).',
                    "code" => 4
                ]
            ];
        }
        $imageId = str_random(16);
        while (DB::connection('mysql_clothes')->table('product')
                ->where('IMAGE', $imageId)->exists() == true) {
            $imageId = str_random(16);
        }

        try{
            DB::connection('mysql_clothes')->table('product')->insert([
                'BRANDID' => request('brand'),
                'COLORID' => request('color'),
                'IMAGE' => $imageId,
                'LINK' => request('link'),
                'TYPEID' => request('type')
            ]);
        }catch (\Exception $e){
            return [
                'error' => [
                    "message" => $e->getMessage(),
                    "code" => 4
                ]
            ];
        }
        Image::make(request('image'))->save(public_path('products')
            . DIRECTORY_SEPARATOR . $imageId . '.jpg');
        return [
            'success' => [
                "message" => 'Product added.',
                "code" => 5
            ]
        ];
    }
}
