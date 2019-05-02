<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Empresa;
use App\Cidade;
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminEmpresasController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $empresas = new Empresa;
        $queries = [];
        $columns = [
            'status', 'cidade_id',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $empresas = $empresas->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $empresas = $empresas->whereRaw(" (`nome` like ? or `sobrenome` like ? or `email` like ? or `empresa` like ? ) ",[request('busca')."%", "%".request('busca')."%", request('busca')."%", "%".request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $empresas->get()->count();
        $empresas = $empresas->with('cidade');
        $empresas = $empresas->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        
        return view('dashboard.admin.empresas.index', compact('empresas', 'amount', 'columns', 'queries', 'cidades'));
    }


    public function create(){
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        return view('dashboard.admin.empresas.create', compact('cidades'));
    }


    public function store(Request $request){

        ////Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'empresa' => ['required', 'string', 'min:2', 'max:100'],
            'cnpj' => ['string', 'size:18'],
            'cidade' => ['integer', 'max:255'],
            'descricao' => ['string', 'max:255'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'sobrenome' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'min:3', 'max:255', 'unique:empresas'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'genero' => ['required', 'alpha', 'max:6'],
            'nascimento' => ['string', 'size:10'],
            'telefone' => ['required', 'string', 'size:14'],
        ]);

        //S3
        $s3 = new AuxiliarController;
        $filename = $s3->s3($request->file('foto'), 'ofertz/e/');


        ////Create
        Empresa::create([
            'foto' => $filename,
            'empresa' => request('empresa'),
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

        ////Return
        return redirect('/admin/empresas')->withMessage("Empresa criada com sucesso!");
    }


    public function show($id){
        $empresa = Empresa::findOrFail($id);
        //Lista de cidades
        $cidades = Cidade::where('status', '=', 'ATIVO')->get();
        return view('dashboard.admin.empresas.show', compact('empresa', 'cidades'));
    }


    public function update(Request $request, $id){
        
        $empresa = Empresa::findOrFail($id);
        if (null !== request('password')) {
           //Validation
            request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $empresa->password = Hash::make(request('password'));
            $empresa->save();

            //Redirect
            return redirect('/admin/empresas/'.$id)->withMessage("Senha alterada com sucesso!");
        } elseif($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
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
            $filename = $s3->s3($request->file('foto'), 'ofertz/empresas/');

            //Update
            $empresa->foto = $filename;
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->cidade_id = request('cidade');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = request('nascimento');
            $empresa->telefone = request('telefone');
            $empresa->status = request('status');
            $empresa->save();

            //Redirect
            return redirect('/admin/empresas/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'empresa' => ['required', 'string', 'min:2', 'max:100'],
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
            $empresa->empresa = request('empresa');
            $empresa->cnpj = request('cnpj');
            $empresa->cidade_id = request('cidade');
            $empresa->descricao = request('descricao');
            $empresa->nome = request('nome');
            $empresa->sobrenome = request('sobrenome');
            $empresa->email = request('email');
            $empresa->genero = request('genero');
            $empresa->nascimento = request('nascimento');
            $empresa->telefone = request('telefone');
            $empresa->status = request('status');
            $empresa->save();

            //Redirect
            return redirect('/admin/empresas/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $empresa = Empresa::findOrFail($id);
        
        //Update
        $empresa->status = "EXCLUIDO";
        $empresa->save();

        //Redirect
        return redirect('/admin/empresas')->withMessage("Empresa excluída com sucesso!");
        
    }
}