<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Meses extends Model
{
    //
    protected $table = 'mes';

    public function pago_salario(){
    	return $this->hasMany('App\modelos\PagoSalario');
    }

    public function pago_pension(){
    	return $this->hasMany('App\modelos\PagoPension');
    }
}
