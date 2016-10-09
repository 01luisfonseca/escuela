<?php

namespace App\modelos;

use Illuminate\Database\Eloquent\Model;

class Authdevice extends Model
{
    //
    protected $table = 'authdevice';

    public function newasistencia(){
    	return $this->hasMany('App\modelos\Newasistencia');
    }

}
