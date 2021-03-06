<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuxiliarController;
use App\Foto;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminFotosController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $fotos = new Foto;
        $queries = [];
        $columns = [
            'status',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $fotos = $fotos->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $fotos = $fotos->whereRaw(" (`nome` like ? ) ", "%".request('busca')."%");
            $queries['busca'] = request('busca');
        }
        //Contagem
        $amount = $fotos->get()->count();
        $fotos = $fotos->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        
        return view('dashboard.admin.fotos.index', compact('fotos', 'amount', 'columns', 'queries'));
    }


    public function create(){
        //Lista de cidades
        return view('dashboard.admin.fotos.create');
    }


    public function store(Request $request){
        
        //Validation
        request()->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'points' => ['required', 'string'],
        ]);

        //Crop S3
        $auxiliar = new AuxiliarController;
        $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/fotos/');

        //Create
        Foto::create([
            'url' => $filename,
            'nome' => request('nome'),
        ]);

        //Return
        return redirect('/admin/fotos')->withMessage("Foto adicionada com sucesso!");
    }


    public function show($id){
        $foto = Foto::findOrFail($id);

        //Return
        return view('dashboard.admin.fotos.show', compact('foto'));
    }


    public function update(Request $request, $id){
        
        $foto = Foto::findOrFail($id);

        //Validation
        request()->validate([
            'foto' => ['image', 'mimes:jpeg,jpg,png', 'dimensions:min_width=300,min_height=300', 'max:10000'],
            'nome' => ['required', 'string', 'min:2', 'max:100'],
            'status' => ['required', 'alpha', 'min:3', 'max:20'],
            'points' => ['string'],
        ]);

        if($request->hasFile('foto')) {

            //Crop S3
            $auxiliar = new AuxiliarController;
            $filename = $auxiliar->cropS3($request->file('foto'), request('points'), 'ofertz/fotos/');

            //Update
            $foto->url = $filename;
            $foto->nome = request('nome');
            $foto->status = request('status');
            $foto->save();

            //Redirect
            return redirect('/admin/fotos/'.$id)->withMessage("Edição realizada com sucesso!");
        }else{

            //Update
            $foto->nome = request('nome');
            $foto->status = request('status');
            $foto->save();

            //Redirect
            return redirect('/admin/fotos/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $foto = Foto::findOrFail($id);
        
        //Update
        $foto->status = "EXCLUIDO";
        $foto->save();

        //Redirect
        return redirect('/admin/fotos')->withMessage("Foto excluída com sucesso!");
        
    }
}
