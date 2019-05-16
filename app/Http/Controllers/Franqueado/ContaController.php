<?php

namespace App\Http\Controllers\Franqueado;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\Franqueado;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class ContaController extends Controller
{

    public function __construct(){
        $this->middleware('auth:franqueado');
    }

    public function show(){
        $franqueado = Franqueado::findOrFail(auth()->id());
        //Lista de cidades
        return view('dashboard.franqueado.conta.show', compact('franqueado'));
    }


    public function update(Request $request){

        //Auxiliar
        $auxiliar = new AuxiliarController;
        
        $franqueado = Franqueado::findOrFail(auth()->id());
        if (null !== request('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $franqueado->password = Hash::make(request('password'));
            $franqueado->save();

            //Redirect
            return redirect('/franqueado/conta')->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
            ]);
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/franqueados/', 300, 300);

            //Update
            $franqueado->foto = $filename;
            $franqueado->nome = request('nome');
            $franqueado->sobrenome = request('sobrenome');
            $franqueado->email = request('email');
            $franqueado->save();

            //Redirect
            return redirect('/franqueado/conta')->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
            ]);

            //Update
            $franqueado->nome = request('nome');
            $franqueado->sobrenome = request('sobrenome');
            $franqueado->email = request('email');
            $franqueado->save();

            //Redirect
            return redirect('/franqueado/conta')->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy(){

        $franqueado = Franqueado::findOrFail(auth()->id());
        
        //Update
        $franqueado->status = "EXCLUIDO";
        $franqueado->save();

        //Logout
        Auth::logout();

        //Redirect
        return redirect('/franqueado/login')->withMessage("Conta excluída com sucesso!");
        
    }
}
