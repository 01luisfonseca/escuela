<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //
    protected $table = 'users';

    public function tipo_usuario(){
    	return $this->belongsTo('App\modelos\TipoUsuario');
    }

    public function alumnos(){
    	return $this->hasMany('App\modelos\Alumnos');
    }

    public function empleados(){
    	return $this->hasMany('App\modelos\Empleados');
    }

}
