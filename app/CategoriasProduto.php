<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriasProduto extends Model
{
    protected $guard = 'admin';

    protected $fillable = [
        'nome', 'descricao', 'foto', 'users_id'
    ];
}
