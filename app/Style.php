<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Style extends Eloquent
{
    protected $collection = 'styles';
    protected $connection = 'mongodb';
}
