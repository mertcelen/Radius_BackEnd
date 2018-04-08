<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendVerification;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $token = str_random(64);
        while(DB::table('users')->where('secret',$token)->exists() == true){
            $token = str_random(64);
        }
        $verification = (String)rand(1000,9999);
        while(DB::table('users')->where('verification',$verification)->exists() == true){
            $verification = (String)rand(1000,9999);
        }
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->verification = $verification;
        $user->secret = $token;
        $user->save();
        $id = DB::table('users')->where('email',$user->email)->select('id')->value('id');
        DB::table('standart_users')->insert([
            'user_id' => $id
        ]);

        //Send Verification Email
        $email = new SendVerification($user->email,$verification);
        $this->dispatch($email);
        return $user;
    }
}