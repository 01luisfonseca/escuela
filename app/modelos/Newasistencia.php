<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Newasistencia extends Model
{
    //
    protected $table = 'newasistencia';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }

    public function niveles(){
    	return $this->belongsTo('App\modelos\Niveles');
    }

    public function periodos(){
    	return $this->belongsTo('App\modelos\Periodos');
    }
}
