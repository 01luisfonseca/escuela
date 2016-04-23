<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class PagoOtros extends Model
{
    //
    protected $table = 'pago_otro';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }
}
