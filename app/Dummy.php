<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Dummy extends Eloquent
{
    protected $fillable = ['data1', 'data2', 'data3', 'data4'];
    protected $collection = 'temp_data';
    protected $connection = 'mongodb';
}
