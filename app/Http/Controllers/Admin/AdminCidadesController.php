<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Cidade;
use Illuminate\Support\Facades\Storage;
use Auth;

class AdminCidadesController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $cidades = new Cidade;
        $queries = [];
        $columns = [
            'status',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $cidades = $cidades->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $cidades = $cidades->whereRaw(" (`nome` like ? or `descricao` like ? ) ",[request('busca')."%","%".request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $cidades->get()->count();
        $cidades = $cidades->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        
        return view('dashboard.admin.cidades.index', compact('cidades', 'amount', 'columns', 'queries'));
    }


    public function create(){
        return view('dashboard.admin.cidades.create');
    }


    public function store(Request $request){

        ////Validation
        request()->validate([
            'nome' => ['required', 'string', 'min:2', 'max:32'],
            'descricao' => ['string', 'max:255'],
            'uf' => ['required', 'string', 'max:2'],
            'foto_desktop' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=1500,min_height=1000', 'max:10000'],
            'foto_mobile' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=500,min_height=800', 'max:10000'],
        ]);

        //S3
        $s3 = new AuxiliarController;
        $filename1 = $s3->s3($request->file('foto_desktop'), 'ofertz/cidades/');
        $filename2 = $s3->s3($request->file('foto_mobile'), 'ofertz/cidades/');
     
        ////Create
        Cidade::create([
            'nome' => request('nome'),
            'descricao' => request('descricao'),
            'uf' => request('uf'),
            'foto_desktop' => $filename1,
            'foto_mobile' => $filename2,
            'user_id' => Auth::user()->id,
        ]);

        ////Return
        return redirect('/admin/cidades')->withMessage("Cidade criada com sucesso!");
    }


    public function show($id){
        $cidade = Cidade::findOrFail($id);
        return view('dashboard.admin.cidades.show', compact('cidade'));
    }


    public function update(Request $request, $id){
        
        $cidade = Cidade::findOrFail($id);
        $s3 = new AuxiliarController;
        $i = 0;
        $columns = [
            'foto_desktop', 'foto_mobile',
        ];
        //Validation
        request()->validate([
            'nome' => ['required', 'string', 'min:2', 'max:32'],
            'descricao' => ['string', 'max:255'],
            'uf' => ['required', 'string', 'max:2'],
            'foto_desktop' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=1500,min_height=1000', 'max:10000'],
            'foto_mobile' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=500,min_height=800', 'max:10000'],
            'status' => ['required', 'alpha', 'min:3', 'max:20'],
        ]);

        //Imagem
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $filename = $s3->s3($request->file($column), 'ofertz/cidades/');
                $cidade->$column = $filename;
            }
        }

        //Update
        $cidade->nome = request('nome');
        $cidade->uf = request('uf');
        $cidade->descricao = request('descricao');
        $cidade->status = request('status');
        $cidade->save();

        //Redirect
        return redirect('/admin/cidades/'.$id)->withMessage("Edição realizada com sucesso!");
    }


    public function destroy($id){

        $cidade = Cidade::findOrFail($id);
        
        //Update
        $cidade->status = "EXCLUIDO";
        $cidade->save();

        //Redirect
        return redirect('/admin/cidades')->withMessage("Categoria excluída com sucesso!");
        
    }
}
