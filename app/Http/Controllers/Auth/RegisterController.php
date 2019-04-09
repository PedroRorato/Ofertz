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
    protected $redirectTo = '/redireciona';

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
            'name' => ['required', 'alpha', 'min:3', 'max:255'],
            'surname' => ['required', 'alpha', 'min:3', 'max:255'],
            'tipo' => ['required', 'alpha', 'max:7'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'genero' => ['required', 'alpha', 'max:6'],
            'nascimento' => ['required', 'string', 'size:10'],
            'cidade' => ['required', 'integer', 'max:4'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
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
        if ($data['tipo'] == 'USUARIO') {
            return User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'tipo' => 'USUARIO',
                'email' => $data['email'],
                'genero' => $data['genero'],
                'password' => Hash::make($data['password']),
                
            ]);
        } else{
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'tipo' => 'usuario',
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return $user;
        }
        
    }
}
