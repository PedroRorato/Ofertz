<?php

namespace App\Http\Controllers\Franqueado;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\User;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:franqueado');
    }


    public function index(Request $request){

        //Filters
        $usuarios = new User;
        $queries = [];
        //Cidade Específica
        $usuarios = $usuarios->where('cidade_id', '=', Auth::user()->cidade_id)->where('status', '!=', 'EXCLUIDO');
        //Queries
        if (request()->has('busca') && request('busca') != null) {
            $usuarios = $usuarios->whereRaw(" (`nome` like ? or `sobrenome` like ? or `email` like ? ) ", ["%".request('busca')."%", "%".request('busca')."%", "%".request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $usuarios->get()->count();
        $usuarios = $usuarios->with('cidade');
        $usuarios = $usuarios->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );

        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();

        //Return
        return view('dashboard.franqueado.usuarios.index', compact('usuarios', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.franqueado.usuarios.create', compact('cidades'));
    }


    public function store(Request $request){

        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Validação da data
        $data = NULL;
        if (request('nascimento')) {
            $data = $auxiliar->validaNascimento(request('nascimento'));
            if (!$data) {
                return redirect()->back()->withInput()->with('data', 'Há algo errado com a data');
            }
        }

        if($request->hasFile('foto')){
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
                'genero' => ['required', 'alpha', 'max:6'],
            ]);

            //cropS3
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/usuarios/', 300, 300);

            //Create
            User::create([
                'foto' => $filename,
                'cidade_id' => Auth::user()->cidade_id,
                'nome' => request('nome'),
                'sobrenome' => request('sobrenome'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'genero' => request('genero'),
                'nascimento' => $data,
            ]);
        } else{
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
                'genero' => ['required', 'alpha', 'max:6'],
            ]);

            //Create
            User::create([
                'cidade_id' => Auth::user()->cidade_id,
                'nome' => request('nome'),
                'sobrenome' => request('sobrenome'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'genero' => request('genero'),
                'nascimento' => $data,
            ]);
        }

        //Return
        return redirect('/franqueado/usuarios')->withMessage("Usuário criada com sucesso!");
    }


    public function show($id){
        $usuario = User::findOrFail($id);
        //Verifica status
        abort_if($usuario->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($usuario->cidade_id != Auth::user()->cidade_id, 403);
        //Tratar data
        $data = NULL;
        if (isset($usuario->nascimento)) {
            $partesData = explode("-", $usuario->nascimento);
            $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        }
        return view('dashboard.franqueado.usuarios.show', compact('usuario', 'data'));
    }


    public function update(Request $request, $id){

        $usuario = User::findOrFail($id);
        //Verifica status
        abort_if($usuario->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($usuario->cidade_id != Auth::user()->cidade_id, 403);
        //Auxiliar
        $auxiliar = new AuxiliarController;
        //Validação da data
        $data = NULL;
        if (request('nascimento')) {
            $data = $auxiliar->validaNascimento(request('nascimento'));
            if (!$data) {
                return redirect()->back()->withInput()->with('data', 'Há algo errado com a data');
            }
        }
        
        if (null !== request('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $usuario->password = Hash::make(request('password'));
            $usuario->save();

            //Redirect
            return redirect('/franqueado/usuarios/'.$id)->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
            ]);
            
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/usuarios/', 300, 300);

            //Update
            $usuario->foto = $filename;
            $usuario->nome = request('nome');
            $usuario->sobrenome = request('sobrenome');
            $usuario->email = request('email');
            $usuario->genero = request('genero');
            $usuario->nascimento = $data;
            $usuario->save();

            //Redirect
            return redirect('/franqueado/usuarios/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
            ]);

            //Update
            $usuario->nome = request('nome');
            $usuario->sobrenome = request('sobrenome');
            $usuario->email = request('email');
            $usuario->genero = request('genero');
            $usuario->nascimento = $data;
            $usuario->save();

            //Redirect
            return redirect('/franqueado/usuarios/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $usuario = User::findOrFail($id);
        //Verifica status
        abort_if($usuario->status == 'EXCLUIDO', 404);
        //Verifica proprietário
        abort_if($usuario->cidade_id != Auth::user()->cidade_id, 403);
        //Update
        $usuario->status = "EXCLUIDO";
        $usuario->save();

        //Redirect
        return redirect('/franqueado/usuarios')->withMessage("Usuário excluída com sucesso!");
        
    }
}
