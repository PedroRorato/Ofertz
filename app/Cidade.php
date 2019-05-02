<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $guard = 'admin';

    protected $fillable = [
        'nome', 'descricao', 'uf', 'foto_desktop', 'foto_mobile', 'status'
    ];

    public function empresas(){
    	return $this->hasMany(Empresa::class);
    }
}
