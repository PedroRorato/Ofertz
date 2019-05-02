<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Support\Facades\Hash;


class AdminAdminsController extends Controller
{

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(Request $request){

        //Filters
        $admins = new Admin;
        $queries = [];
        $columns = [
            'status',
        ];
        foreach ($columns as $column) {
            if (request()->has($column)) {
                $admins = $admins->where($column, 'like', request($column));
                $queries[$column] = request($column);
            }
        }
        if (request()->has('busca') && request('busca') != null) {
            $admins = $admins->whereRaw(" (`nome` like ? or `sobrenome` like ? or `email` like ? ) ",[request('busca')."%",request('busca')."%",request('busca')."%"]);
            $queries['busca'] = request('busca');
        }
        
        $amount = $admins->get()->count();
        $admins = $admins->orderBy('nome', 'asc')->paginate(25)->appends($queries,
            ['amount' => $amount]
        );
        
        return view('dashboard.admin.admins.index', compact('admins', 'amount', 'columns', 'queries'));
    }

    public function create(){
        return view('dashboard.admin.admins.create');
    }


    public function store(Request $request){
        //Validation
        request()->validate([
            'nome' => ['required', 'min:2', 'max:255'],
            'sobrenome' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'min:3', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        //Create
        Admin::create([
            'nome' => request('nome'),
            'sobrenome' => request('sobrenome'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        return redirect('/admin/admins')->withMessage("Elemento criado com sucesso!");
    }


    public function show($id){
        $admin = Admin::findOrFail($id);
        return view('dashboard.admin.admins.show', compact('admin'));
    }


    public function update(Request $request, $id){
        
        $admin = Admin::findOrFail($id);
        if (null !== request('password')) {
           //Validation
            $attributes = request()->validate([
                'password' => ['required', 'string', 'min:5', 'confirmed'],
            ]);

            //Update
            $admin->password = Hash::make(request('password'));
            $admin->save();

            //Redirect
            return redirect('/admin/admins/'.$id)->withMessage("Senha alterada com sucesso!");
        } else{
            //Validation
            $attributes = request()->validate([
                'nome' => ['required', 'min:2', 'max:255'],
                'sobrenome' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'email', 'min:3', 'max:255'],
                'status' => ['required', 'alpha', 'min:3', 'max:20'],
            ]);

            //Update
            $admin->nome = request('nome');
            $admin->sobrenome = request('sobrenome');
            $admin->email = request('email');
            $admin->status = request('status');
            $admin->save();

            //Redirect
            return redirect('/admin/admins/'.$id)->withMessage("Edição realizada com sucesso!");
        }
    }


    public function destroy($id){

        $admin = Admin::findOrFail($id);
        
        //Update
        $admin->status = "EXCLUIDO";
        $admin->save();

        //Redirect
        return redirect('/admin/admins')->withMessage("Administrador excluído com sucesso!");
        
    }
}
