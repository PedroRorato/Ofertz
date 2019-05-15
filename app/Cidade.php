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

    public function eventos(){
        return $this->hasMany(Evento::class);
    }

    public function franqueados(){
    	return $this->hasMany(Franqueado::class);
    }

    public function produtos(){
        return $this->hasMany(Produto::class);
    }

    public function usuarios(){
        return $this->hasMany(User::class);
    }

}
