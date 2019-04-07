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
    		echo "logado";
    		$user = Auth::user();
    		if ($user->tipo == "pedro") {
    			echo "pedro";
    		} else{
    			echo "outro";
    		}
    	} 
    	else
    	{
    		echo "guest";
    	}
    }
}
