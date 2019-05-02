<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriasEvento extends Model
{
    protected $guard = 'admin';

    protected $fillable = [
        'nome', 'descricao', 'foto', 'user_id'
    ];
}
