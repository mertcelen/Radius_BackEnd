<?php

namespace App;

use App\Http\Controllers\Faagram\AssociateController;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $collection = 'users';
    protected $connection = 'mongodb';
    protected $fillable = [
        'name', 'email', 'password', 'gender'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function add($name, $email, $password)
    {
        $user = new self();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->gender = 0;
        $verification = str_random(64);
        while (User::where('verification', $verification)->exists() == true) {
            $verification = str_random(64);
        }
        $user->type = 1;
        $user->setup = false;
        $user->verification = $verification;
        $user->status = 0;
        $user->values = "50,50";
        $secret = str_random(64);
        while (User::where('secret', $secret)->exists() == true) {
            $secret = str_random(64);
        }
        $user->secret = $secret;
        $user->avatar = "default_avatar";
        $user->save();
        return $user;
    }

    public function isAdmin()
    {
        return $this->statusCheck(3);
    }

    protected function statusCheck($status = 0)
    {
        return $this->status === $status ? true : false;
    }

    public function isVerified()
    {
        return !$this->statusCheck(0);
    }

    public function isComplete(){
        return $this->setup;
    }
}
