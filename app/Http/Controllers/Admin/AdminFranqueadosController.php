<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cidade;
use App\Franqueado;
use Illuminate\Support\Facades\Hash;


class AdminFranqueadosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $franqueados = new Franqueado;
        $queries = [];
        $columns = [
            'status',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $franqueados = $franqueados->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $franqueados = $franqueados->whereRaw(" (`nome` like ? or `sobrenome` like ? or `email` like ? ) ",[request('busca')."%",request('busca')."%",request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        
        $amount = $franqueados->get()->count();
        $franqueados = $franqueados->with('cidade');
        $franqueados = $franqueados->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );

        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();

        //Return
        return view('dashboard.admin.franqueados.index', compact('franqueados', 'amount', 'columns', 'queries', 'cidades'));
    }

    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        //Return
        return view('dashboard.admin.franqueados.create', compact('cidades'));
    }


    public function store(Request $request){
        //Validation
        request()->validate([
            'nome' => ['required', 'string', 'min:2', 'max:255'],
            'sobrenome' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'min:3', 'max:255', 'unique:franqueados'],
            'cpf' => ['required', 'string', 'size:14'],
            'telefone' => ['required', 'string', 'size:14'],
            'cidade' => ['required', 'integer', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        //Create
        Franqueado::create([
            'nome' => request('nome'),
            'sobrenome' => request('sobrenome'),
            'email' => request('email'),
            'cpf' => request('cpf'),
            'telefone' => request('telefone'),
            'cidade_id' => request('cidade'),
            'password' => Hash::make(request('password')),
        ]);

        return redirect('/admin/franqueados')->withMessage("Elemento criado com sucesso!");
    }


    public function show($id){
        $franqueado = Franqueado::findOrFail($id);
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        return view('dashboard.admin.franqueados.show', compact('franqueado'));
    }


    public function update(Request $request, $id){
        
        $franqueado = Franqueado::findOrFail($id);
        if (null !== request('password')) {
           //Validation
            $attributes = request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $franqueado->password = Hash::make(request('password'));
            $franqueado->save();

            //Redirect
            return redirect('/admin/franqueados/'.$id)->withMessage("Senha alterada com sucesso!");
        } else{
            //Validation
            $attributes = request()->validate([
                'nome' => ['required', 'min:2', 'max:255'],
                'sobrenome' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //Update
            $franqueado->nome = request('nome');
            $franqueado->sobrenome = request('sobrenome');
            $franqueado->email = request('email');
            $franqueado->status = request('status');
            $franqueado->save();

            //Redirect
            return redirect('/admin/franqueados/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $franqueado = Franqueado::findOrFail($id);
        
        //Update
        $franqueado->status = "EXCLUIDO";
        $franqueado->save();

        //Redirect
        return redirect('/admin/franqueados')->withMessage("Franqueado excluído com sucesso!");
        
    }
}
