<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class TipoNota extends Model
{
    //
    protected $table = 'tipo_nota';

    public function notas(){
    	return $this->hasMany('App\modelos\Notas');
    }

    public function indicadores(){
    	return $this->belongsTo('App\modelos\Indicadores');
    }
}
