<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    //
    protected $table = 'tipo_usuario';

    public function usuarios(){
    	$this->hasMany('App\modelos\Users');
    }
}
