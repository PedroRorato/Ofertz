<?php

namespace App\Http\Controllers\Auth;

use App\Cidade;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UsuarioRegisterController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('guest:usuario');
    }

    public function showRegisterForm(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Retorno
        return view('auth.cadastro-usuario', compact('cidades'));
    }

    public function register(Request $request){

        $this->validate($request, [
            'nome' => ['required', 'string', 'min:3', 'max:255'],
            'sobrenome' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->where(function ($query) {
                return $query->where('status', 'ATIVO')->orWhere('status', 'INATIVO');
            })],
            'genero' => ['required', 'alpha', 'max:6'],
            'nascimento' => ['required', 'string', 'size:10'],
            'cidade' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        //Criar usuário
        User::create([
            'nome' => request('nome'),
            'email' => request('email'),
            'sobrenome' => request('sobrenome'),
            'email' => request('email'),
            'genero' => request('genero'),
            'cidade_id' => request('cidade'),
            'password' => Hash::make(request('password')),
        ]);

        //Logar
        Auth::guard('usuario')->attempt(['email' => $request->email, 'password' => $request->password]);

        //Return
        return redirect('/');
    }
}
