<?php

namespace App\Http\Controllers\Empresa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class ContaController extends Controller
{

    public function __construct(){
        $this->middleware('auth:empresa');
    }

    public function show(){
        $empresa = Empresa::findOrFail(auth()->id());
        $data = NULL;
        if (isset($empresa->nascimento)) {
            $partesData = explode("-", $empresa->nascimento);
            $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        }
        //Lista de cidades
        return view('dashboard.empresa.conta.show', compact('data', 'empresa'));
    }


    public function update(Request $request){

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
        
        $empresa = Empresa::findOrFail(auth()->id());
        if (null !== request('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $empresa->password = Hash::make(request('password'));
            $empresa->save();

            //Redirect
            return redirect('/empresa/conta')->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            //Validation
            request()->validate([
                'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
                'telefone' => ['required', 'string', 'size:14'],
            ]);
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/empresas/', 300, 300);

            //Update
            $empresa->foto = $filename;
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = $data;
            $empresa->telefone = request('telefone');
            $empresa->save();

            //Redirect
            return redirect('/empresa/conta')->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
                'telefone' => ['required', 'string', 'size:14'],
            ]);

            //Update
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = $data;
            $empresa->telefone = request('telefone');
            $empresa->save();

            //Redirect
            return redirect('/empresa/conta')->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy(){

        $empresa = Empresa::findOrFail(auth()->id());
        
        //Update
        $empresa->status = "EXCLUIDO";
        $empresa->save();

        //Logout
        Auth::logout();

        //Redirect
        return redirect('/empresa/login')->withMessage("Conta excluída com sucesso!");
        
    }
}
