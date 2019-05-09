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
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();

        //Return
        return view('dashboard.admin.usuarios.index', compact('usuarios', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        //Return
        return view('dashboard.admin.usuarios.create', compact('cidades'));
    }


    public function store(Request $request){

        ////Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'usuario' => ['required', 'string', 'min:2', 'max:100'],
            'cnpj' => ['string', 'size:18'],
            'cidade' => ['integer', 'max:255'],
            'descricao' => ['string', 'max:255'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:3', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'genero' => ['required', 'alpha', 'max:6'],
            'nascimento' => ['string', 'size:10'],
            'telefone' => ['required', 'string', 'size:14'],
        ]);

        //S3
        $s3 = new AuxiliarController;
        $filename = $s3->s3($request->file('foto'), 'ofertz/e/');

        //Create
        User::create([
            'foto' => $filename,
            'usuario' => request('usuario'),
            'cnpj' => request('cnpj'),
            'cidade_id' => request('cidade'),
            'descricao' => request('descricao'),
            'nome' => request('nome'),
            'sobrenome' => request('sobrenome'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'genero' => request('genero'),
            'nascimento' => request('nascimento'),
            'telefone' => request('telefone'),
            'status' => 'ATIVO',
        ]);

        //Return
        return redirect('/admin/usuarios')->withMessage("usuario criada com sucesso!");
    }


    public function show($id){
        $usuario = User::findOrFail($id);
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        return view('dashboard.admin.usuarios.show', compact('usuario', 'cidades'));
    }


    public function update(Request $request, $id){
        
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
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'usuario' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'cidade' => ['integer', 'max:255'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
                'nascimento' => ['string', 'size:10'],
                'telefone' => ['required', 'string', 'size:14'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);
            
            //S3
            $s3 = new AuxiliarController;
            $filename = $s3->s3($request->file('foto'), 'ofertz/usuarios/');

            //Update
            $usuario->foto = $filename;
            $usuario->usuario = request('usuario');
            $usuario->cnpj = request('cnpj');
            $usuario->cidade_id = request('cidade');
            $usuario->descricao = request('descricao');
            $usuario->nome = request('nome');
            $usuario->sobrenome = request('sobrenome');
            $usuario->email = request('email');
            $usuario->genero = request('genero');
            $usuario->nascimento = request('nascimento');
            $usuario->telefone = request('telefone');
            $usuario->status = request('status');
            $usuario->save();

            //Redirect
            return redirect('/admin/usuarios/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'usuario' => ['required', 'string', 'min:2', 'max:100'],
                'cnpj' => ['string', 'size:18'],
                'cidade' => ['integer', 'max:255'],
                'descricao' => ['string', 'max:255'],
                'nome' => ['required', 'string', 'min:2', 'max:100'],
                'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'genero' => ['required', 'alpha', 'max:6'],
                'nascimento' => ['string', 'size:10'],
                'telefone' => ['required', 'string', 'size:14'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //Update
            $usuario->usuario = request('usuario');
            $usuario->cnpj = request('cnpj');
            $usuario->cidade_id = request('cidade');
            $usuario->descricao = request('descricao');
            $usuario->nome = request('nome');
            $usuario->sobrenome = request('sobrenome');
            $usuario->email = request('email');
            $usuario->genero = request('genero');
            $usuario->nascimento = request('nascimento');
            $usuario->telefone = request('telefone');
            $usuario->status = request('status');
            $usuario->save();

            //Redirect
            return redirect('/admin/usuarios/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $usuario = User::findOrFail($id);
        
        //Update
        $usuario->status = "EXCLUIDO";
        $usuario->save();

        //Redirect
        return redirect('/admin/usuarios')->withMessage("usuario excluída com sucesso!");
        
    }
}
