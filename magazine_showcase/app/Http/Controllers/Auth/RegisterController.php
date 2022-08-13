<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
    protected $redirectTo = '/manage-users';

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

        $email = explode("@", $data['email']);
        $student_email =  "gmail.com";

        if ($email[1] == $student_email){
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'faculty' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
        }else{
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $email = explode("@", $data['email']);
        $student_email =  "gmail.com";

        if ($email[1] == $student_email){
            $role = 'student';
        }else{
            $role = 'guest';
        }

        if ($student_email){

            $user_create = User::create([
                'name' => $data['name'],
                'gander' => $data['gander'],
                'user_type' => $role,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // custom codes
            $user_id = User::where('email','=', $data['email'])->value('id');
            $faculty_id = $data['faculty'];

            $user = User::find($user_id);
            $user->user_faculty()->attach($faculty_id);

            // die($user_id.'-'.$faculty_id);
            // custom codes

            return $user_create;

        }else{
            return User::create([
                'name' => $data['name'],
                'gander' => $data['gander'],
                'user_type' => $role,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }
}
