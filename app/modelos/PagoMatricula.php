<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class PagoMatricula extends Model
{
    //
    protected $table = 'pago_matricula';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }
}
