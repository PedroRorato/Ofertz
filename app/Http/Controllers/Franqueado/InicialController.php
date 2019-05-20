<?php

namespace App\Http\Controllers\Franqueado;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InicialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:franqueado');
    }

    public function index()
    {
        return view('dashboard.franqueado.index');
    }
}
