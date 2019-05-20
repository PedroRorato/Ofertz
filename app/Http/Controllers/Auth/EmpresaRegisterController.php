<?php

namespace App\Http\Controllers\Auth;

use App\Cidade;
use App\Empresa;
use App\Http\Controllers\AuxiliarController;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmpresaRegisterController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('guest:empresa');
    }

    public function showRegisterForm(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Retorno
        return view('auth.cadastro-empresa', compact('cidades'));
    }

    public function register(Request $request){

        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Valida data
        $data = NULL;
        if (request('nascimento')) {
            $data = $auxiliar->validaNascimento(request('nascimento'));
            if (!$data) {
                return redirect()->back()->withInput()->with('data', 'Só são permitidas datas passadas');
            }
        }

        $this->validate($request, [
            'cidade' => ['required', 'integer'],
            'cnpj' => ['string', 'size:18'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('empresas')->where(function ($query) {
                return $query->where('status', 'ATIVO')->orWhere('status', 'INATIVO');
            })],
            'empresa' => ['required', 'string', 'min:3', 'max:255'],
            'genero' => ['required', 'alpha', 'max:6'],
            'nascimento' => ['required', 'string', 'size:10'],
            'nome' => ['required', 'string', 'min:3', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'sobrenome' => ['required', 'string', 'min:3', 'max:255'],
            'telefone' => ['required', 'string', 'size:14'],
        ]);

        //Criar usuário
        Empresa::create([
            'cidade_id' => request('cidade'),
            'cnpj' => request('cnpj'),
            'email' => request('email'),
            'empresa' => request('empresa'),
            'genero' => request('genero'),
            'nascimento' => $data,
            'nome' => request('nome'),
            'password' => Hash::make(request('password')),
            'sobrenome' => request('sobrenome'),
            'status' => 'PENDENTE',
            'telefone' => request('telefone'),
        ]);

        //Return
        return redirect('/empresa/cadastro')->withMessage("Sua solicitação está sendo avaliada por nossa equipe! Em breve você receberá um email confirmando a inscrição!");//
    }
}

