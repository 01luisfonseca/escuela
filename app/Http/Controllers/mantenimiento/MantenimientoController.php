<?php

namespace App\Http\Controllers\mantenimiento;

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

//Para limpieza
use App\Helpers\NoOrphanRegisters;


class MantenimientoController extends Controller
{
	public function getLimpiarHuerfanosTotal(){
		$noOrphan= new NoOrphanRegisters;
		return view('mantenimientosimple',['limpiados'=>$noOrphan->getLimpiarHuerfanosLiviano()]);
	}
	public function getLimpiarNotasRango($idBajo,$idAlto){
		$noOrphan= new NoOrphanRegisters;
		return $noOrphan->eliminarNotasHuerfanosPorRango($idBajo,$idAlto);
	}
	public function highRegistroNotas(){
		$obj=Notas::select('id')->where('id','>','0')->orderBy('id','desc')->first();
		return $obj->id;
	}
	public function autoLlenarNotas($id){
		$noOrphan= new NoOrphanRegisters;
		return $noOrphan->autoLlenarAlumno($id);
	}

	public function usuariosRecientes($rango=200){
		$alumnos=Alumnos::orderBy('created_at','desc')
			->with(['users'=> function($query){
				$query->select('id','name','lastname');
			}])->select('id','users_id')->take($rango)->get();
		return $alumnos->toJson();
	}
	
}