<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\BioData;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'regex:/(^[A-Za-z0-9]+$)+/', 'max:100', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'regex:/^(?=.{6,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/'],
            'dob'=> ['required', 'date', 'before:today'],
            "nationality" => 'required|string|max:200',
            "mobile_number" => 'required|string|max:100',
            "bio" => 'string|max:10000'
        ];

        $customMessages = [
            'required' => 'The :attribute field is required.',
            'username.regex' => 'The username can only allow numbers and alphabets. Kindly check and try again',
            'password.regex' => 'The provided password must contain at least 1 uppercase letter, 1 lowercase letter, 1 symbol and 1 number with a minimum length of 6 characters.'
        ];

        return Validator::make($data, $rules, $customMessages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Create bio info for a user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function createBio(array $data, $user_id)
    {
        return BioData::create([
            'dob' => $data['dob'],
            'nationality' => $data['nationality'],
            'mobile_number' => $data['mobile_number'],
            'bio' => $data['bio'],
            'user_id' => $user_id
        ]);
    }
}
