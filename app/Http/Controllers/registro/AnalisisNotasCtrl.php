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

//Para validacion

use Validator;

class AnalisisNotasCtrl extends Controller
{
	public function getAnNotas(){
		return view('registro/annotas');
	}

	public function obtenerNivelesPeriodos($anio){
		$niveles=Niveles::with(['materias_has_niveles'=>function($query){
			$query->where('materias_id','<>','38')->with(['materias','niveles_has_periodos']);//Solo especifico al Lusadi
		}])->get();
		return $niveles->toJson();
	}

	public function calcularPromedios(Request $request){
		$tabla=$request->input('ids');
		$arrayFinal=[];
		for ($i=0; $i < count($tabla)-1; $i++) { 
			$objeto=NivelesHasPeriodos::with(['indicadores'=>function($query){
				$query->with(['tipo_nota'=>function($query){
					$query->with(['notas']);
				}]);
			}])->where('id','=',$tabla[$i])->get();
			foreach ($objeto as $obj) {
				$acumObj=0;
				foreach ($obj->indicadores as $indicador) {
					$acumTipo=0;
					foreach ($indicador->tipo_nota as $tipo) {
						$acumNota=0;
						foreach ($tipo->notas as $nota) {
							$acumNota+=$nota->calificacion;
							//Log::info('Nota: '.$nota->calificacion);
						}
						$acNotDen=count($tipo->notas)!=0? count($tipo->notas) : 1;
						$acumTipo+=$acumNota/$acNotDen;
						//Log::info($acumTipo+' con notas:'.count($tipo->notas));
					}
					$acTipDen=count($indicador->tipo_nota)!=0? count($indicador->tipo_nota) : 1;
					$acumObj+=(($acumTipo/$acTipDen)*$indicador->porcentaje)/100;
					//Log::info('Promedio Tipo:'.$acumTipo/count($indicador->tipo_nota));
				}
				array_push($arrayFinal, ['id'=>$obj->id,'promedio'=>$acumObj]);
				//Log::info('Acumulado Periodo: '.$acumObj);
			}
		}
		//asort($arrayFinal);
		$entrega=Collection::make($arrayFinal);
		return $entrega->toJson();
	}

	public function descargaExcel($anio){
		$niveles=Niveles::with(['materias_has_niveles'=>function($query){
			$query->with(['materias','niveles_has_periodos'=>function($query){
				$query->with(['indicadores']);
			}]);
		}])->get();
		/*Excel::create('notas_'.$anio,function($excel){
			$excel->sheet('Notas',function($sheet){
				$sheet->row(1,['prueba']);
			});
		})->download('xlsx');*/
		return $niveles->toJson();
	}
}