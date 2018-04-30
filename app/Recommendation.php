<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Recommendation extends Eloquent
{
    protected $collection = 'recommendations';
    protected $connection = 'mongodb';
}
