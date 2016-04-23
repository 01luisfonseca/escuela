<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    //
    protected $table = 'notas';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }

    public function tipo_nota(){
    	return $this->belongsTo('App\modelos\TipoNota');
    }
}
