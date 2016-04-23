<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Indicadores extends Model
{
    //
    protected $table = 'indicadores';

    public function tipo_nota(){
    	return $this->hasMany('App\modelos\TipoNota');
    }

    public function niveles_has_periodos(){
    	return $this->belongsTo('App\modelos\NivelesHasPeriodos');
    }
}
