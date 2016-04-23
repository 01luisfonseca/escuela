<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class NivelesHasPeriodos extends Model
{
    //
    protected $table = 'niveles_has_periodos';

    public function asistencia(){
    	return $this->hasMany('App\modelos\Asistencia');
    }

    public function materias_has_niveles(){
    	return $this->belongsTo('App\modelos\MateriasHasNiveles');
    }

    public function periodos(){
    	return $this->belongsTo('App\modelos\Periodos');
    }
    public function indicadores(){
        return $this->hasMany('App\modelos\Indicadores');
    }
}
