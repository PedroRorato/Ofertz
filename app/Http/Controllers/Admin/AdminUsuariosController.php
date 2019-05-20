<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use App\User;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsuariosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $usuarios = new User;
        $queries = [];
        $columns = [
            'status', 'cidade_id',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $usuarios = $usuarios->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
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
        return view('dashboard.admin.usuarios.index', compact('usuarios', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        //Return
        return view('dashboard.admin.usuarios.create', compact('cidades'));
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
        

        //Validation
        request()->validate([
            'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'cidade' => ['integer', 'max:255'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:3', 'max:255', Rule::unique('users')->where(function ($query) {
                return $query->where('status', 'ATIVO')->orWhere('status', 'INATIVO');
            })],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'genero' => ['required', 'alpha', 'max:6'],
        ]);

        //cropS3
        $filename = NULL;
        if($request->hasFile('foto')){
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/usuarios/', 300, 300);
        }
        
        //Create
        User::create([
            'foto' => $filename,
            'cidade_id' => request('cidade'),
            'nome' => request('nome'),
            'sobrenome' => request('sobrenome'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'genero' => request('genero'),
            'nascimento' => $data,
        ]);

        //Return
        return redirect('/admin/usuarios')->withMessage("Usuário criada com sucesso!");
    }


    public function show($id){
        $usuario = User::findOrFail($id);
        //Tratar data
        $data = NULL;
        if (isset($usuario->nascimento)) {
            $partesData = explode("-", $usuario->nascimento);
            $data = $partesData[2].'/'.$partesData[1].'/'.$partesData[0];
        }
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->orderBy('nome', 'asc')->get();
        return view('dashboard.admin.usuarios.show', compact('usuario', 'cidades', 'data'));
    }


    public function update(Request $request, $id){

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
        
        $usuario = User::findOrFail($id);
        if (null !== request('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $usuario->password = Hash::make(request('password'));
            $usuario->save();

            //Redirect
            return redirect('/admin/usuarios/'.$id)->withMessage("Senha alterada com sucesso!");
        } 

        //Validation
        request()->validate([
            'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'cidade' => ['integer', 'max:255'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:3', 'max:255', Rule::unique('users')->ignore($id)->where(function ($query) {
                return $query->where('status', 'ATIVO')->orWhere('status', 'INATIVO');
            })],
            'genero' => ['required', 'alpha', 'max:6'],
            'status' => ['required', 'alpha', 'min:3', 'max:20'],
        ]);

        //Tem foto
        if($request->hasFile('foto')) {
            //cropS3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/usuarios/', 300, 300);
            //Update
            $usuario->foto = $filename;
        }

        //Update
        $usuario->cidade_id = request('cidade');
        $usuario->nome = request('nome');
        $usuario->sobrenome = request('sobrenome');
        $usuario->email = request('email');
        $usuario->genero = request('genero');
        $usuario->nascimento = $data;
        $usuario->status = request('status');
        $usuario->save();

        //Redirect
        return redirect('/admin/usuarios/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $usuario = User::findOrFail($id);
        
        //Update
        $usuario->status = "EXCLUIDO";
        $usuario->save();

        //Redirect
        return redirect('/admin/usuarios')->withMessage("Usuário excluída com sucesso!");
        
    }
}
