<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Image extends Eloquent
{
    protected $collection = 'images';
    protected $connection = 'mongodb';
}
