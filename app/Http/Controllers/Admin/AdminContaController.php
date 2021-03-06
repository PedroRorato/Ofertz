<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\Admin;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminContaController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function show(){
        $admin = Admin::findOrFail(auth()->id());
        //Lista de cidades
        return view('dashboard.admin.conta.show', compact('admin'));
    }


    public function update(Request $request){

        //Auxiliar
        $auxiliar = new AuxiliarController;
        
        $admin = Admin::findOrFail(auth()->id());
        if (null !== request('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $admin->password = Hash::make(request('password'));
            $admin->save();

            //Redirect
            return redirect('/admin/conta')->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255', 'unique:admins,email,'.auth()->id()],
            ]);
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/admins/', 300, 300);

            //Update
            $admin->foto = $filename;
            $admin->nome = request('nome');
            $admin->sobrenome = request('sobrenome');
            $admin->email = request('email');
            $admin->save();

            //Redirect
            return redirect('/admin/conta')->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255', 'unique:admins,email,'.auth()->id()],
            ]);

            //Update
            $admin->nome = request('nome');
            $admin->sobrenome = request('sobrenome');
            $admin->email = request('email');
            $admin->save();

            //Redirect
            return redirect('/admin/conta')->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy(){

        $admin = Admin::findOrFail(auth()->id());
        
        //Update
        $admin->status = "EXCLUIDO";
        $admin->save();

        //Logout
        Auth::logout();

        //Redirect
        return redirect('/admin/login')->withMessage("Conta excluída com sucesso!");
        
    }
}
