<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    protected $redirectTo = '/home';

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
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^\+[1-9]\d{7,14}$/',
                'unique:users,phone',
            ],

            'password' => [
                'required',
                'string',
                'min:10',
                'confirmed',
            ],

            'consent' => [
                'required',
                'accepted',
            ],
        ], [
            'phone.required' => 'WhatsApp/mobile number is required.',
            'phone.regex' => 'Please enter a valid international phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'password.min' => 'Password must be at least 10 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'consent.accepted' => 'You must agree before creating an account.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
