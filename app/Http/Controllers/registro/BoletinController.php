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

//Para validacion

use Validator;

class BoletinController extends Controller
{
	public function index(){
		return view('registro/boletines');
	}
	public function getNiveles(){
		$objeto=Niveles::where('id','<>',0)->orderBy('nombre_nivel','asc')->get();
		return $objeto->toJson();
	}
	public function getAlumnos($niveles_id){
		$objeto=Alumnos::select('alumnos.id','users.name','users.lastname')
			->join('users','alumnos.users_id','=','users.id')
			->where('niveles_id','=',$niveles_id)
			->orderBy('users.lastname','asc')->get();
		return $objeto->toJson();
	}
	public function getPeriodos($niveles_id){
		$objeto=Periodos::where('id','<>',0)->orderBy('nombre_periodo','asc')->get();
		return $objeto->toJson();
	}
	public function obtenerPuesto($niveles_id,$per_id){
		$periodos_analizados=0;
		$promedio=array();
		$per=array();
		$tempor1=array();
		$alu=null;
		$alumnos=Alumnos::where('niveles_id','=',$niveles_id)->get();//Obtener todos los alumnos del salon
		$periodos=Periodos::all();
		foreach ($alumnos as $alumno) {
			foreach ($periodos as $periodo) {
				$per['r'.$periodo->id]=0;
			}
			$alu=$this->notasAlumno($alumno->id);
			$materias=0;
			foreach ($alu->niveles->materias_has_niveles as $materia) {
				if ($materia->materias->nombre_materia!='ASISTENCIA') {
					$materias++;
					if($materia->niveles_has_periodos->count()>0){
						foreach ($materia->niveles_has_periodos as $periodo) {
							$acumindic=0;
							if($periodo->periodos_id<=$per_id){
								foreach ($periodo->indicadores as $indicador) {
									$temporal=0;
									foreach ($indicador->tipo_nota as $value) {
										if($value->notas->count()>0){
											$temporal+=$value->notas[0]->calificacion;
										}else{
											$temporal+=0;//Problema de los niños que no están en las notas. Solucion es 0 en la nota
										}													
									}
									$opt=$indicador->tipo_nota->count()>0? $temporal/$indicador->tipo_nota->count():0;
									$acumindic+=$opt*$indicador->porcentaje/100;//Resultados por indicador
								}
								$per['r'.$periodo->periodos_id]+=$acumindic;
							}
						}
						
					}
				}
			}
			foreach ($per as $key => $value) {
				$per[$key]=$materias>0?$per[$key]/$materias:0;
			}
			$promedio[$alu->id]=array();
			$promedio[$alu->id]=['id'=>$alu->id,'periodos'=>$per];
			$per=[];
			$tempor1[$alu->id]=array();
			$tempor1[$alu->id]=0;
		}
		foreach ($periodos as $periodo) {
			if ($periodo->id<=$per_id) {
				$periodos_analizados++;
			}
			foreach ($promedio as $key => $value) {
				$tempor1[$key]=$value['periodos']['r'.$periodo->id];
			}
			arsort($tempor1);
			$x=1;
			foreach ($tempor1 as $key => $value) {
				$promedio[$key]=array_merge($promedio[$key],['p'.$periodo->id=>$x]);
				$x++;
			}
			$tempor1=[];
		}
		$contador=0;
		foreach ($promedio as $key => $value) {
			$acumperiodo=0;
			foreach ($value['periodos'] as $valueint) {
				$acumperiodo+=$valueint;
				$contador++;
			}
			$promedio[$key]=array_merge($promedio[$key],['rt'=> $acumperiodo/$contador]);
		}
		foreach ($promedio as $key => $value) {
			$tempor1[$key]=$value['rt'];
		}
		arsort($tempor1);
		$x=1;
		foreach ($tempor1 as $key => $value) {
			$promedio[$key]=array_merge($promedio[$key],['pt'=>$x]);
			$x++;
		}
		$tempor1=[];
		return collect($promedio);
	}
	private function notasAlumno($alumnos_id){
		$alumno=Alumnos::with(['users','niveles'=>function($query) use($alumnos_id){
			$query->with(['materias_has_niveles'=>function($query) use($alumnos_id){
				$query->with(['materias',
					'empleados'=>function($query){
						$query->with('users');
					},
					'niveles_has_periodos'=>function ($query) use($alumnos_id) {
					$query->with(["indicadores"=>function($query) use($alumnos_id){
						$query->with(['tipo_nota'=>function($query) use($alumnos_id) {
							$query->with(['notas'=>function($query) use($alumnos_id) {
								$query->where('alumnos_id','=',$alumnos_id);
							}]);
						}])->orderBy('nombre','asc');
					}])->orderBy('periodos_id','asc');
				}]);
			}]);
		}])->find($alumnos_id);
		return $alumno;
	}
	public function getBoletin($alumnos_id,$periodos_id){
		$periodo=Periodos::find($periodos_id);
		$periodosAll=Periodos::all();
		$alumno=$this->notasAlumno($alumnos_id);
		return view('registro/modeloboletin',[
			'alumno'=>$alumno,
			'periodo'=>$periodo,
			'periodos_id'=>$periodos_id,
			'matrix'=>$this->obtenerPuesto($alumno->niveles->id,$periodos_id),
			'periodosAll'=>$periodosAll]);
	}
}