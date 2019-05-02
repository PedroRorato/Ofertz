<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{

    protected $fillable = [
        'foto', 'nome', 'validade', 'empresa_id', 'cidade_id', 'descricao'
    ];

    public function cidade(){
        return $this->belongsTo(Cidade::class);
    }

    public function empresa(){
    	return $this->belongsTo(Empresa::class);
    }
}
