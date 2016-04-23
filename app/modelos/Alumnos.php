<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Alumnos extends Model
{
    //
    protected $table = 'alumnos';

    public function pago_pension(){
    	return $this->hasMany('App\modelos\PagoPension');
    }

    public function pago_matricula(){
    	return $this->hasMany('App\modelos\PagoMatricula');
    }

    public function pago_otro(){
        return $this->hasMany('App\modelos\PagoOtros');
    }

    public function users(){
    	return $this->belongsTo('App\modelos\Users');
    }

    public function niveles(){
    	return $this->belongsTo('App\modelos\Niveles');
    }

    public function asistencia(){
        return $this->hasMany('App\modelos\Asistencia');
    }

    public function notas(){
        return $this->hasMany('App\modelos\Notas');
    }
}
