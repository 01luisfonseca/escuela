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
use App\modelos\Generales;
use App\modelos\Asiserved;
use App\modelos\Tarjetas;

//Para validacion

use Validator;

class AsistenciaCtrl extends Controller
{
	public function getDeviceAsistencia($serial,$tarjeta){
		$servidor=Generales::where('nombre','Servidor principal')->first();
		if($servidor->valor==""){
			$alumno=Users::where('tarjeta',$tarjeta)
				->with(['alumnos'=>function($query){
					$query->with('niveles','users')->first();
				}])
				->first();
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
			//dd($alumno);
			$asistencia=new Newasistencia;
			$asistencia->authdevice_id=$device->id;
			$asistencia->alumnos_id=$alumno->alumnos[0]->id;
			$asistencia->name=$alumno->alumnos[0]->users->name;
			$asistencia->lastname=$alumno->alumnos[0]->users->lastname;
			$asistencia->periodos_id=$periodos[$index]->id;
			$asistencia->save();
		}else{

			/* Esta parte guarda en otra tabla la lista de asistencias parcialmente. */
			$card=Tarjetas::where('tarjeta',$tarjeta)->get();
			if (!isset($card->id)) {
				return response('NoAlumno',404);
			}
			$asistencia=new Asiserved;
			$asistencia->tarjeta=$tarjeta;
			$asistencia->lectora=$serial;
			$asistencia->save();
		}
		return response('Asistio',200);
	}

	public function postAsistencia($serial,$tarjeta,Request $request){
		// En espera de cualquier modificacion.
	}

	public function getAsistencias($inicio=0){
		$mostrados=50;
		$asis=Newasistencia::where('id','!=',0)
			->with('alumnos.niveles')
			->orderBy('created_at','desc')
			->skip($inicio)
			->take($mostrados+$inicio)
			->get();
		$registros=Newasistencia::all();
		return $asis->toJson();
	}

	public function getInfoAsis(){
		$asis=Newasistencia::all();
		$col=collect(['registros'=>$asis->count(),'mostrados'=>50]);
		return $col->toJson();
	}

	public function getTarjetas($inicio=0){
		$tarj=Users::where('tarjeta','!=','')->skip($inicio)->take(50+$inicio)->get();
		return $tarj->toJson();
	}

	public function getOnlyTarjetas(){
		$tarj=Users::where('tarjeta','!=','')->select('tarjeta')->get();
		return $tarj->toJson();
	}
}