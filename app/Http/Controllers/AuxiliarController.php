<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class AuxiliarController extends Controller
{
	public function s3($foto, $place){
        	//get filename with extension
                $filenamewithextension = $foto->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $foto->getClientOriginalExtension();
                //filename to store
                $filenametostore = $place.md5(time()).'.'.$extension;
                //Upload File to s3
                Storage::disk('s3')->put($filenametostore, fopen($foto, 'r+'), 'public');

                return $filenametostore;
	}

        public function s32($original, $foto, $place){
                //get filename with extension
                $filenamewithextension = $original->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $original->getClientOriginalExtension();
                //filename to store
                $filenametostore = $place.md5(time()).'.'.$extension;
                //Upload File to s3
                Storage::disk('s3')->put($filenametostore, $foto, 'public');

                return $filenametostore;
        }

}