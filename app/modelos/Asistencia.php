<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    //
    protected $table = 'asistencia';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }

    public function niveles_has_periodos(){
    	return $this->belongsTo('App\modelos\NivelesHasPeriodos');
    }
}
