<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CategoriasProduto;
use Illuminate\Support\Facades\Storage;
use Auth;

class AdminCategoriasProdutoController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $categorias = new CategoriasProduto;
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
        
        return view('dashboard.admin.categorias-produto.index', compact('categorias', 'amount', 'columns', 'queries'));
    }

    public function create(){
        return view('dashboard.admin.categorias-produto.create');
    }


    public function store(Request $request){

        ////Validation
        request()->validate([
            'nome' => ['required', 'string', 'min:2', 'max:32'],
            'descricao' => ['required', 'string', 'max:255'],
            'foto' => ['required', 'max:10000'],
        ]);


        ////S3
        //get filename with extension
        $filenamewithextension = $request->file('foto')->getClientOriginalName();
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        //get file extension
        $extension = $request->file('foto')->getClientOriginalExtension();
        //filename to store
        $filenametostore = 'ofertz/categorias-produto/'.md5(time()).'.'.$extension;
        //Upload File to s3
        Storage::disk('s3')->put($filenametostore, fopen($request->file('foto'), 'r+'), 'public');
     

        ////Create
        CategoriasProduto::create([
            'nome' => request('nome'),
            'descricao' => request('descricao'),
            'foto' => $filenametostore,
            'users_id' => Auth::user()->id,
        ]);


        ////Return
        return redirect('/admin/categorias-produto')->withMessage("Categoria de produto criada com sucesso!");
    }


    public function show($id){
        $categoria = CategoriasProduto::findOrFail($id);
        return view('dashboard.admin.categorias-produto.show', compact('categoria'));
    }


    public function update(Request $request, $id){
        
        $categoria = CategoriasProduto::findOrFail($id);
        if($request->hasFile('foto')) {
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:32'],
                'descricao' => ['required', 'string', 'max:255'],
                'foto' => ['required', 'max:10000'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //get filename with extension
            $filenamewithextension = $request->file('foto')->getClientOriginalName();
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            //get file extension
            $extension = $request->file('foto')->getClientOriginalExtension();
            //filename to store
            $filenametostore = 'ofertz/categorias-produto/'.md5(time()).'.'.$extension;
            //Upload File to s3
            Storage::disk('s3')->put($filenametostore, fopen($request->file('foto'), 'r+'), 'public');

            //Update
            $categoria->nome = request('nome');
            $categoria->descricao = request('descricao');
            $categoria->foto = $filenametostore;
            $categoria->status = request('status');
            $categoria->save();

            //Redirect
            return redirect('/admin/categorias-produto/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{
            //Validation
            request()->validate([
                'nome' => ['required', 'string', 'min:2', 'max:32'],
                'descricao' => ['required', 'string', 'max:255'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //Update
            $categoria->nome = request('nome');
            $categoria->descricao = request('descricao');
            $categoria->status = request('status');
            $categoria->save();

            //Redirect
            return redirect('/admin/categorias-produto/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $categoria = CategoriasProduto::findOrFail($id);
        
        //Update
        $categoria->status = "EXCLUIDO";
        $categoria->save();

        //Redirect
        return redirect('/admin/categorias-produto')->withMessage("Administrador excluído com sucesso!");
        
    }
}
