<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    public function main()
    {
        $products = Product::all();
        return $products;
    }

    public function get()
    {
        $products = Product::where('type', request('type'))->where('color', request('color'))->get();
        return $products;
    }

    public function add()
    {
        $imageId = str_random(16);
        while (!empty(Product::where('image', $imageId)->get()->toArray())) {
            $imageId = str_random(16);
        }
        $flag = Product::where('link',request('link'))->first();
        if($flag != null){
            return [
                'error' => [
                    "message" => 'Product already exist.',
                    "code" => 4
                ]
            ];
        }
        $product = new Product();
        $product->fill(request()->all());
        $product->image = $imageId;
        $product->save();
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
