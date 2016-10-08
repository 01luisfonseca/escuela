<?php

namespace App\Http\Controllers\registro;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\NoOrphanRegisters;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade;
use Barryvdh\DomPDF\PDF;
use Log;
use Illuminate\Support\Collection as Collection;

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
use App\modelos\TipoNota;
use App\modelos\Indicadores;
use App\modelos\PagoMatricula;
use App\modelos\PagoPension;
use App\modelos\PagoOtros;
use App\modelos\PagoSalario;
use App\modelos\PrecioMatricula;
use App\modelos\PrecioPension;
use App\modelos\TipoUsuario;
use App\modelos\Users;
use App\modelos\Meses;
use App\modelos\Newasistencia;
use App\modelos\Authdevice;

//Para validacion

use Validator;

class AsistenciaCtrl extends Controller
{
	public function getDeviceAsistencia($serial,$tarjeta){
		$alumno=Users::where('tarjeta',$tarjeta)
			->with(['alumnos'=>function($query){
				$query->with('niveles')->first();
			}])
			->first();
		//dd($alumno->alumnos[0]);
		if (!isset($alumno->alumnos[0]->niveles->nombre_nivel)) {
			return response('NoAlumno',404);
		}
		$periodos=Periodos::all();
		$hoy=Carbon::now();
		$device=Authdevice::where('serial',$serial)
			->first();
		$ind=0;
		$index=0;
		foreach ($periodos as $per) {
			$ini=Carbon::createFromFormat('Y-m-d',$per->fecha_ini);
			$fin=Carbon::createFromFormat('Y-m-d',$per->fecha_fin);
			if ($hoy->gte($ini) && $hoy->lte($fin)) {
				$index=$ind;
			}
			$ind++;
		}
		$asistencia=new Newasistencia;
		$asistencia->authdevice_id=$device->id;
		$asistencia->alumnos_id=$alumno->alumnos[0]->id;
		$asistencia->periodos_id=$periodos[$index]->id;
		$asistencia->save();
		return response('Asistio',200);
	}

	public function postAsistencia($serial,$tarjeta,Request $request){
		// En espera de cualquier modificacion.
	}

	public function getAsistencias($inicio=0){
		$asis=Newasistencia::where('id','!=',0)->skip($inicio)->take(50+$inicio)->get();
		return $asis->toJson();
	}

	public function getTarjetas($inicio=0){
		$tarj=Users::where('tarjetas','!=','')->skip($inicio)->take(50+$inicio)->get();
		return $tarj->toJson();
	}
}