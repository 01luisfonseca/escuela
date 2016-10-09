<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Newasistencia extends Model
{
    //
    protected $table = 'newasistencia';

    public function alumnos(){
    	return $this->belongsTo('App\modelos\Alumnos');
    }
    
    public function periodos(){
    	return $this->belongsTo('App\modelos\Periodos');
    }

    public function authdevice(){
        return $this->belongsTo('App\modelos\Authdevice');
    }
}
