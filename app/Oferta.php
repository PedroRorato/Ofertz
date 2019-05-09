<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $fillable = [
        'preco', 'validade', 'status', 'produto_id', 'empresa_id', 'cidade_id', 'observacao'
    ];

    public function cidade(){
        return $this->belongsTo(Cidade::class);
    }

    public function empresa(){
    	return $this->belongsTo(Empresa::class);
    }

    public function produto(){
    	return $this->belongsTo(Produto::class);
    }

}
