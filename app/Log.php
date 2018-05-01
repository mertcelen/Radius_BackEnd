<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Log extends Eloquent
{
    protected $collection = 'logs';
    protected $connection = 'mongodb';

    public static function add($process,$message,$user){
        $log = new self();
        $log->process = $process;
        $log->message = $message;
        $log->user = $user;
        $log->save();
    }
}
