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

class AuthdeviceCtrl extends Controller
{
	public function getDevices(){
		$obj=Authdevice::all();
		return $obj->toJson();
	}

	public function getDevice($id){
		$obj=Authdevice::findOrFail($id);
		return $obj->toJson();
	}

	public function delDevice($id){
		$obj=Authdevice::findOrFail($id);
		$obj->delete();
		return response()->json(['mensaje' => 'Eliminado con éxito',"estado"=>true]);
	}

	public function modEstado($id,Request $request){
		$obj=Authdevice::find($id);
		$obj->estado=$request->input('estado');
		$obj->save();
		return response()->json(['mensaje' => 'Estado cambiado con éxito',"estado"=>true]);
	}

	public function setDevice(Request $request){
		return $this->setmodDevice(0,$request);
	}

	public function modDevice($id,Request $request){
		return $this->setmodDevice($id,$request);
	}

	private function setmodDevice($id=0,Request $request){
		$resultado='Operación rechazada por falta de información';
		$validacion=Validator::make($request->all(),[
            'nombre' => 'required',
            'serial'=>'required'
        ]);
        if ($validacion->fails()) {
            return response()->json(['mensaje' => $resultado,"estado"=>false]);
        }
		$obj=new Authdevice;
		if($id>0){
			$obj=Authdevice::find($id);
		}
		$obj->serial=$request->input('serial');
		if(!$request->input('descripcion') || is_null($request->input('descripcion'))){
			$obj->descripcion="";
		}else{
			$obj->descripcion=$request->input('descripcion');
		}
		$obj->nombre=$request->input('nombre');
		if($request->has('estado')){
			$obj->estado=$request->input('estado');
		}else{
			$obj->estado=true;
		}
		$obj->save();
		$resultado="Registro Creado";
		return response()->json(['mensaje' => $resultado,"estado"=>true]);
	}
}