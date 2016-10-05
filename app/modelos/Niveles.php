<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Niveles extends Model
{
    //
    protected $table = 'niveles';

    public function alumnos(){
    	return $this->hasMany('App\modelos\alumnos');
    }

    public function materias_has_niveles(){
    	return $this->hasMany('App\modelos\MateriasHasNiveles');
    }

    public function newasistencia(){
        return $this->hasMany('App\modelos\Newasistencia');
    }
}
