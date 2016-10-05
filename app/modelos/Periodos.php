<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Periodos extends Model
{
    //
    protected $table = 'periodos';

    public function niveles_has_periodos(){
    	return $this->hasMany('App\modelos\NivelesHasPeriodos');
    }

    public function newasistencia(){
        return $this->hasMany('App\modelos\Newasistencia');
    }
}
