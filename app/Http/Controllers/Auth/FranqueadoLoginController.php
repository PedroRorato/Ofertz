<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class FranqueadoLoginController extends Controller
{
	public function __construct(){
		$this->middleware('guest:franqueado');
	}

    public function showLoginForm(){
        return view('auth.login-franqueado');
    }

    public function login(Request $request){

    	$this->validate($request, [
    		'email' => 'required|email',
    		'password' => 'required'
    	]);

    	if (Auth::guard('franqueado')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'ATIVO'])) {
    		return redirect()->intended('franqueado');
    	}

    	return redirect()->back()->withInput($request->only('email', 'remember'))->withMessage("Dados incorretos!");
    }
}
