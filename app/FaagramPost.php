<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class FaagramPost extends Eloquent
{
    protected $collection = 'faagram_post';
    protected $connection = 'mongodb';
    static $colors = array();
    static $types = array();
    public function __construct($id,array $attributes = [])
    {
        parent::__construct($attributes);
        $this->id = $id;
        $this->label = array_random(FaagramPost::$types,1)[0]->name;
        $this->color = array_random(FaagramPost::$colors,1)[0]->name;
        $this->save();
    }

    public static function boot()
    {
        $types = DB::connection('mysql_clothes')->table('type')->get()->toArray();
        $colors = DB::connection('mysql_clothes')->table('color')->get()->toArray();
        FaagramPost::$colors = $colors;
        FaagramPost::$types = $types;
    }
}
