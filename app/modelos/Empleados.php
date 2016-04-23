<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    //
    protected $table = 'empleados';

    public function materias_has_niveles(){
    	return $this->hasMany('App\modelos\MateriasHasNiveles');
    }

    public function pago_salario(){
    	return $this->hasMany('App\modelos\PagoSalario');
    }

    public function users(){
    	return $this->belongsTo('App\User');
    }
}
