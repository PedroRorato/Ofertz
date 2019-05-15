<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;
use File;


class AuxiliarController extends Controller
{
    public function categoriasArray($categorias){
        $array_pertence[] = 0;
        foreach ($categorias as $pertence) {
            $array_pertence[] = $pertence->id;
        }
        return $array_pertence;
    }

    public function cropS3($foto, $points, $place, $width, $height){
        //Tratamento pontos
        $partes = explode(",", $points);
        $lado = (int)$partes[2] - (int)$partes[0];
        //Gerar nome
        $nome = md5(time()) . $foto->getClientOriginalExtension();
        //Criar imagem
        $image = Image::make($foto)->crop($lado, $lado, (int)$partes[0], (int)$partes[1])->resize($width, $height)->save($nome);
        //S3
        $filename = $this->s32($foto, $image, $place);
        //Deleta Arquivo
        File::delete($nome);
        //Return
        return $filename;
    }

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
        //Return
        return $filenametostore;
	}

    public function s32($original, $foto, $place){
        $filenamewithextension = $original->getClientOriginalName();
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        $extension = $original->getClientOriginalExtension();
        $filenametostore = $place.md5(time()).'.'.$extension;
        Storage::disk('s3')->put($filenametostore, $foto, 'public');
        return $filenametostore;
    }

    public function validaNascimento($data){
        $partes = explode("/", $data);
        $data = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
        $date = strtotime($data);
        if (time() < $date || !checkdate ( $partes[1], $partes[0], $partes[2] )) {
            return false;
        } else{
            return $data;
        }
    }

    public function validaDataTempo($data){
        $partes = explode("/", $data);
        $data = $partes[2] . '-' . $partes[1] . '-' . $partes[0] . 'T' . request('time');
        $date = strtotime($data);
        if (time() > $date) {
            return false;
        } else{
            return $data;
        }
    }

    public function verificaData($data){
        //Data Painel
        $date_now = new DateTime();
        $date_validade    = new DateTime($data);
        $editar = true;
        if($date_now > $date_validade){
            $editar = false;
        }
        return $editar;
    }

}