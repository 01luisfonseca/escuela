<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class PagoPension extends Model
{
    //
    protected $table = 'pago_pension';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }
    
    public function meses(){
    	return $this->belongsTo('App\modelos\Meses');
    }
}
