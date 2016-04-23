<?php

namespace App\Http\Controllers\registro;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//Carga de modelos

use App\User;
use App\modelos\Alumnos;
use App\modelos\Asistencia;
use App\modelos\Empleados;
use App\modelos\Materias;
use App\modelos\MateriasHasNiveles;
use App\modelos\Niveles;
use App\modelos\NivelesHasPeriodos;
use App\modelos\Periodos;
use App\modelos\Notas;
use App\modelos\PagoMatricula;
use App\modelos\PagoPension;
use App\modelos\PagoSalario;
use App\modelos\PrecioMatricula;
use App\modelos\PrecioPension;
use App\modelos\TipoUsuario;
use App\modelos\Users;

//Para validacion

use Validator;


class AutocompleteController extends Controller
{
	public function alumnos(){
		$alumnos=User::where('tipo_usuario_id','=',2)
			->where('name','LIKE','%'.$request->input('term').'%')
			->orWhere('lastname','LIKE','%'.$request->input('term').'%')
			->orWhere('identificacion','LIKE','%'.$request->input('term').'%')
			->get();
		$data=array();
		foreach ($alumnos as $alumno) {
			$data[]=['value'=>$alumno->id,'data'=>$alumno->name.' '.$alumno->lastname.', No.'.$alumno->identificacion];
		}
		$json=array("suggestions"=>$data);
		return response()->json($json);
	}
}