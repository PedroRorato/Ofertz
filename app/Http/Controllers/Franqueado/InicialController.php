<?php

namespace App\Http\Controllers\Franqueado;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InicialController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:franqueado');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.franqueado.index');
    }
}
