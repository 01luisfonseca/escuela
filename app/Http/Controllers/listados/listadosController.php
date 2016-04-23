<?php

namespace App\Http\Controllers\listados;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Helpers\NoOrphanRegisters;
use Maatwebsite\Excel\Facades\Excel;
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


class listadosController extends Controller
{
    
    public function index()
    {
        return view('listados/listados');
    }

    public function getAlumnos(){
        return view('listados/alumnos');
    }

    public function getNiveles(){
        return Niveles::orderBy('nombre_nivel','asc')->get()->toJson();
    }

    public function getAlumnosPorNivel($id){
        return Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion','users.direccion','users.telefono','users.acudiente')
                        ->join('users','alumnos.users_id','=','users.id')
                        ->where('niveles_id','=',$id)
                        ->orderBy('users.lastname','asc')
                        ->get()->toJson();
    }

    public function exportarAlumnos($id){
        $nivel=Niveles::find($id);
        $objeto=Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion','users.direccion','users.telefono','users.acudiente')
            ->join('users','alumnos.users_id','=','users.id')
            ->where('niveles_id','=',$id)
            ->orderBy('users.lastname','asc')
            ->get();
        if($objeto->count()>0){
            Excel::create('Alumnos por nivel', function($excel) use ($objeto,$nivel){
                $excel->sheet('Nivel', function($sheet) use ($objeto,$nivel){ 
                    $sheet->row(1,['NUEVO COLEGIO LUSADI LTDA']);
                    $sheet->row(2,['LISTADO DE ALUMNOS POR NIVEL']);
                    $sheet->row(3,['NIT. 900.185.143-3']);
                    $sheet->row(4,['NIVEL:',$nivel->nombre_nivel]);
                    $sheet->row(5,['']);
                    $sheet->row(6,['IDENTIFICACION','APELLIDOS','NOMBRES','DIRECCION','TELEFONO','ACUDIENTE']);
                    $contador=7;//El siguiente despues de los títulos
                    foreach ($objeto as $value) {
                        $sheet->row($contador,[$value->identificacion,$value->lastname,$value->name,$value->direccion,$value->telefono,$value->acudiente]);
                        $contador++;
                    }
                });
            })->download('xlsx');
        }
    }

}
