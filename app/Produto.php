<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
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

    public function categorias(){
    	return $this->belongsToMany(CategoriasProduto::class, "produtos_categoria_produtos");
    }
}
