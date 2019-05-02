<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\CategoriasEvento;
use Illuminate\Support\Facades\Storage;
use Auth;

class AdminCategoriasEventoController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $categorias = new CategoriasEvento;
        $queries = [];
        $columns = [
            'status',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $categorias = $categorias->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $categorias = $categorias->whereRaw(" (`nome` like ? or `descricao` like ? ) ",[request('busca')."%","%".request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        
        $amount = $categorias->get()->count();
        $categorias = $categorias->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        
        return view('dashboard.admin.categorias-evento.index', compact('categorias', 'amount', 'columns', 'queries'));
    }


    public function create(){
        return view('dashboard.admin.categorias-evento.create');
    }


    public function store(Request $request){

        //Validation
        request()->validate([
            'nome' => ['required', 'string', 'min:2', 'max:32'],
            'descricao' => ['string', 'max:255'],
            'foto' => ['required', 'max:10000'],
        ]);

        //S3
        $s3 = new AuxiliarController;
        $filename = $s3->s3($request->file('foto'), 'ofertz/categorias-evento/');
     
        //Create
        CategoriasEvento::create([
            'nome' => request('nome'),
            'descricao' => request('descricao'),
            'foto' => $filename,
            'user_id' => Auth::user()->id,
        ]);

        ////Return
        return redirect('/admin/categorias-evento')->withMessage("Categoria de produto criada com sucesso!");
    }


    public function show($id){
        $categoria = CategoriasEvento::findOrFail($id);
        return view('dashboard.admin.categorias-evento.show', compact('categoria'));
    }


    public function update(Request $request, $id){
        
        $categoria = CategoriasEvento::findOrFail($id);
        if($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:32'],
                'descricao' => ['string', 'max:255'],
                'foto' => ['required', 'max:10000'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //S3
            $s3 = new AuxiliarController;
            $filename = $s3->s3($request->file('foto'), 'ofertz/categorias-evento/');

            //Update
            $categoria->nome = request('nome');
            $categoria->descricao = request('descricao');
            $categoria->foto = $filename;
            $categoria->status = request('status');
            $categoria->save();

            //Redirect
            return redirect('/admin/categorias-evento/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:32'],
                'descricao' => ['string', 'max:255'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //Update
            $categoria->nome = request('nome');
            $categoria->descricao = request('descricao');
            $categoria->status = request('status');
            $categoria->save();

            //Redirect
            return redirect('/admin/categorias-evento/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $categoria = CategoriasEvento::findOrFail($id);
        
        //Update
        $categoria->status = "EXCLUIDO";
        $categoria->save();

        //Redirect
        return redirect('/admin/categorias-evento')->withMessage("Categoria excluída com sucesso!");
        
    }
}
