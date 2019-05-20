<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UsuarioLoginController extends Controller
{
    public function __construct(){
        $this->middleware('guest:usuario');
    }

    public function showLoginForm(){
        //Retorno
        return view('auth.login-usuario');
    }

    public function login(Request $request){

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('usuario')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'ATIVO'])) {
            return redirect()->intended('/usuario');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withMessage("Dados incorretos!");
    }
}
