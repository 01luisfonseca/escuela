<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    //
    protected $table = 'materias';

    public function materias_has_niveles(){
    	return $this->hasMany('App\modelos\MateriasHasNiveles');
    }
}
