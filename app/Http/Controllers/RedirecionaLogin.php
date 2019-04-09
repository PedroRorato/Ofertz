<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RedirecionaLogin extends Controller
{

    public function index()
    {
    	if(Auth::check()) 
    	{
    		echo "logado ";
    		$user = Auth::user();
    		if ($user->tipo == "USUARIO") 
            {
    			echo "USUARIO";
    		} 
            elseif ($user->tipo == "EMPRESA") 
            {
                echo "EMPRESA";
            }
            elseif ($user->tipo == "FRANQUEADO") 
            {
                echo "FRANQUEADO";
            }
            elseif ($user->tipo == "ADMIN") 
            {
                echo "ADMIN";
            }
            else{
    			echo " ERRO";
    		}
    	} 
    	else
    	{
    		echo "guest";
    	}
    }
}
