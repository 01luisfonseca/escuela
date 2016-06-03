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

class AnalisisNotasCtrl extends Controller
{
	public function getAnNotas(){
		return view('registro/annotas');
	}

	public function descargaExcel($anio){
<<<<<<< HEAD
		$niveles=Niveles::with(['materias_has_niveles'=>function($query){
			$query->with(['materias','niveles_has_periodos'=>function($query){
				$query->with('periodos','indicadores.tipo_nota.notas');
			}]);
		}])->where('id','>',0)->get();//Pendiente la integración de todas las notas para el promedio.
		//Excel::create();
		return $niveles->toJson();
=======
		$niveles=Niveles::all();//Pendiente la integración de todas las notas para el promedio.
		return view('registro/annotas');
>>>>>>> 89c99588a414fda2c8240ee9b5e5d31a876a01ad
	}
}