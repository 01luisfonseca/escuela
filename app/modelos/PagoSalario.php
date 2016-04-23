<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class PagoSalario extends Model
{
    //
    protected $table = 'pago_salario';

    public function empleados(){
    	return $this->belongsTo('App\modelos\Empleados');
    }
    
    public function meses(){
    	return $this->belongsTo('App\modelos\Meses');
    }
}
