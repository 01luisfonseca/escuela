<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class MateriasHasNiveles extends Model
{
    //
    protected $table = 'materias_has_niveles';

    public function niveles_has_periodos(){
    	return $this->hasMany('App\modelos\NivelesHasPeriodos');
    }

    public function materias(){
    	return $this->belongsTo('App\modelos\Materias');
    }

    public function empleados(){
    	return $this->belongsTo('App\modelos\Empleados');
    }

    public function niveles(){
    	return $this->belongsTo('App\modelos\Niveles');
    }
}
