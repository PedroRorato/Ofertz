<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Empresa extends Authenticatable
{
    use Notifiable;

    protected $guard = 'empresa';

    protected $fillable = [
        'nome', 'sobrenome', 'email', 'password', 'genero', 'nascimento', 'cidade_id', 'cnpj', 'status', 'telefone', 'empresa', 'foto'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cidade(){
        return $this->belongsTo(Cidade::class);
    }

    public function eventos(){
        return $this->hasMany(Evento::class);
    }

    public function produtos(){
        return $this->hasMany(Produto::class);
    }
}
