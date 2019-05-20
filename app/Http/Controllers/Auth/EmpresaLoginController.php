<?php

namespace App\Http\Controllers\Auth;

use App\Empresa;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use DB;

class EmpresaLoginController extends Controller
{
	public function __construct(){
		$this->middleware('guest:empresa');
	}

    public function showLoginForm(){
        return view('auth.login-empresa');
    }

    public function login(Request $request){

    	$this->validate($request, [
    		'email' => 'required|email',
    		'password' => 'required'
    	]);

        //Checar status
        $empresas = DB::table('empresas')->where('email', '=', request('email'))->get();
        foreach ($empresas as $empresa) {
            if($empresa->status == 'ATIVO' || $empresa->status == 'INATIVO') {
                if (Auth::guard('empresa')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return redirect()->intended('/empresa');
                }
                return redirect()->back()->withInput($request->only('email'))->withMessage("Dados Incorretos!");
            }

            if($empresa->status == 'PENDENTE'){
                return redirect()->back()->withInput($request->only('email'))->withMessage("Sua solicitação ainda está sendo avaliada por nossa equipe! Em breve você receberá um email confirmando a inscrição!");
            }
        }


        //Return
        return redirect()->back()->withInput($request->only('email'))->withMessage("Dados Incorretos!");

    }
}
