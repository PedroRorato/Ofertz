<?php

namespace App\Http\Controllers\Empresa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InicialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:empresa');
    }

    public function index()
    {
        return view('dashboard.empresa.index');
    }
}
