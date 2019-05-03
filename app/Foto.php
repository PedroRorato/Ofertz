<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $fillable = [
        'nome', 'url', 'status'
    ];

    public function produto(){
        return $this->belongsTo(Cidade::class);
    }
}
