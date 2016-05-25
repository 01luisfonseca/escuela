<?php

namespace app\Helpers;

use App\Helpers\Contracts\NoOrphanRegistersContract;

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
use App\modelos\PagoSalario;
use App\modelos\PrecioMatricula;
use App\modelos\PrecioPension;
use App\modelos\TipoUsuario;
use App\modelos\Users;

class NoOrphanRegisters implements NoOrphanRegistersContract
{

    public function getLimpiarHuerfanos(){
		$resultados='Resultados de limpieza de huerfanos: ';
		$resultados.=' '.$this->eliminarAlumnosHuerfanos().' alumnos eliminados, ';
		$resultados.=' '.$this->eliminarEmpleadosHuerfanos().' empleados eliminados, ';
		$resultados.=' '.$this->eliminarMateriasHasNivelesHuerfanos().' niveles-materias eliminadas, ';
		$resultados.=' '.$this->eliminarPeriodosHasNivelesHuerfanos().' niveles-materias-periodos eliminados, ';
		$resultados.=' '.$this->eliminarAsistenciasHuerfanos().' asistencias eliminadas, ';
		$resultados.=' '.$this->eliminarIndicadoresHuerfanos().' indicadores eliminadas, ';
		$resultados.=' '.$this->eliminarTiposHuerfanos().' tipos de nota eliminadas. ';
		//$resultados.=' '.$this->eliminarNotasHuerfanos().' notas eliminadas. ';
		
		return $resultados;
	}

	public function getLimpiarHuerfanosLiviano(){
		$resultados=$this->eliminarAlumnosHuerfanos();
		$resultados+=$this->eliminarEmpleadosHuerfanos();
		$resultados+=$this->eliminarMateriasHasNivelesHuerfanos();
		$resultados+=$this->eliminarPeriodosHasNivelesHuerfanos();
		$resultados+=$this->eliminarAsistenciasHuerfanos();
		$resultados+=$this->eliminarIndicadoresHuerfanos();
		$resultados+=$this->eliminarTiposHuerfanos();
		$resultados+=$this->eliminarNotasHuerfanos();
		return $resultados;
	}

	public function eliminarMateriasHasNivelesHuerfanos(){
		$elementos=MateriasHasNiveles::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayMateria($elemento->materias_id) ||
				!$this->hayNivel($elemento->niveles_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
			if ($elemento->empleados_id!=0) {
				if(!$this->hayEmpleado($elemento->empleados_id)){
					$especial=MateriasHasNiveles::find($elemento->id);
					$especial->empleados_id=0;
					$especial->save();
				}
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=MateriasHasNiveles::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}//Verificado

	public function eliminarPeriodosHasNivelesHuerfanos(){
		$elementos=NivelesHasPeriodos::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayMateriasHasNiveles($elemento->materias_has_niveles_id) ||
				!$this->hayPeriodo($elemento->periodos_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=NivelesHasPeriodos::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}//Verificado

	public function eliminarAsistenciasHuerfanos(){
		$elementos=Asistencia::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayNivelesHasPeriodos($elemento->niveles_has_periodos_id) ||
				!$this->hayAlumno($elemento->alumnos_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=Asistencia::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}//Verificado

	public function eliminarIndicadoresHuerfanos(){
		$elementos=Indicadores::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayNivelesHasPeriodos($elemento->niveles_has_periodos_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=Indicadores::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}

	public function eliminarTiposHuerfanos(){
		$elementos=TipoNota::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayIndicadores($elemento->indicadores_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=TipoNota::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}

	public function eliminarNotasHuerfanos($rango=500){
		$obj=Notas::select('id')->where('id','>',0)->orderBy('id','asc')->get();
		$registros=$obj[0]->id;
		$numero=0;
		for ($i=0; $i*$rango < $registros; $i++) { 
			$numero+=$this->eliminarNotasHuerfanosPorRango($i*$rango,($i+1)*$rango);
			usleep(200000);
		}
		return $numero;
	}

	private function eliminarNotasHuerfanosPorRango($idBajo=1,$idAlto=200){
		$elementos=Notas::where('id','<',$idAlto)->where('id','>=',$idBajo)->get();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayTipoNotas($elemento->tipo_nota_id) ||
				!$this->hayAlumno($elemento->alumnos_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=Notas::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}

	public function eliminarAlumnosHuerfanos(){
		$elementos=Alumnos::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayUsuario($elemento->users_id) ||
				!$this->hayNivel($elemento->niveles_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=Alumnos::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}

	public function eliminarEmpleadosHuerfanos(){
		$elementos=Empleados::all();
		$marcado=array();
		$eliminados=0;
		foreach ($elementos as $elemento) {
			if (
				!$this->hayUsuario($elemento->users_id)
				) 
			{
				$marcado[]=$elemento->id;
			}
		}
		$marcado=array_unique($marcado);
		$eliminados=count($marcado);
		if($eliminados>0){
			foreach ($marcado as $seleccionado) {
				$eliminado=Empleados::find($seleccionado);
				$eliminado->delete();
			}
		}
		return $eliminados;
	}

	//Funciones generales de la clase

	public function hayIndicadores($id){
		return $this->esUtil(Indicadores::find($id));
	}
	public function hayTipoNotas($id){
		return $this->esUtil(TipoNota::find($id));
	}
	public function hayMateriasHasNiveles($id){
		return $this->esUtil(MateriasHasNiveles::find($id));
	}
	public function hayNivelesHasPeriodos($id){
		return $this->esUtil(NivelesHasPeriodos::find($id));
	}
	public function hayPeriodo($id){
		return $this->esUtil(Periodos::find($id));
	}
	public function hayMateria($id){
		return $this->esUtil(Materias::find($id));
	}
	public function hayNivel($id){
		return $this->esUtil(Niveles::find($id));
	}
	public function hayUsuario($id){
		return $this->esUtil(Users::find($id));
	}
	public function hayAlumno($id){
		return $this->esUtil(Alumnos::find($id));
	}
	public function hayEmpleado($id){
		return $this->esUtil(Empleados::find($id));
	}
	public function esUtil($variable){
		if(!is_object($variable) || is_null($variable) ){
			return false;
		}
		return true;
	}

}
