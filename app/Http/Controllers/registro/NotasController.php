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

class NotasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('registro/registrorest');
    }

    public function getMateriasHasNiveles(){
        if(auth()->user()->tipo_usuario_id==4){
            $empleado=Empleados::where('users_id','=',auth()->user()->id)->first();
            $profe=MateriasHasNiveles::with('niveles','materias')
                ->where('empleados_id','=',$empleado->id)
                ->orderBy('niveles_id','asc')
                ->get();
            return $profe->toJson();
        }elseif (auth()->user()->tipo_usuario_id>4) {
            $materias=MateriasHasNiveles::with('niveles','materias')->where('id','<>',0)->orderBy('niveles_id','asc')->get();
            return $materias->toJson();
        }
    }

    public function getNivelesHasPeriodos($id){
        $objeto=NivelesHasPeriodos::with('periodos')->where('materias_has_niveles_id','=',$id)->get();
        return $objeto->toJson();
    }

    public function getIndicadores($id){
        $objeto=NivelesHasPeriodos::with([
                    'indicadores'=>function($query){
                        $query->orderBy('id','asc');
                        $query->with(['tipo_nota'=>function($query){
                            $query->orderBy('id','asc');
                            $query->with(['notas'=>function($query){
                                $query->orderBy('id','asc');
                                $query->with('alumnos.users');
                            }]);
                        }]);
                    },
                    'periodos',
                    'materias_has_niveles',
                    'materias_has_niveles.materias',
                    'materias_has_niveles.niveles'
                ])
            ->where('id','=',$id)
            ->get();
        return $objeto->toJson();
    }
    
    public function getOnlyIndicadores($nivPerid){
        $objeto=Indicadores::with(['periodos',
                    'materias_has_niveles',
                    'materias_has_niveles.materias',
                    'materias_has_niveles.niveles'
                    ])
            ->where('niveles_has_periodos_id','=',$nivPerid)
            ->get();
        return $objeto->toJson();
    }

    public function setIndicadores($nivelesInPeriodosId,Request $request){
        $estado=false;
        $resultado='No se ha creado el registro.';
        $validacion=Validator::make($request->all(),[
            'nombre' => 'required',
            'porcentaje'=>'required'
        ]);
        if ($validacion->fails()) {
            return response()->json(['mensaje' => $resultado.' Falta información.',"estado"=>$estado]);
        }
        $valida2=NivelesHasPeriodos::find($nivelesInPeriodosId);
        if (!isset($valida2->id)) {
            return response()->json(['mensaje' => $resultado.' No existe el periodo en el nivel.',"estado"=>$estado]);
        }
        $objeto=new Indicadores;
        $objeto->nombre=$request->input('nombre');
        $objeto->porcentaje=$request->input('porcentaje');
        if (is_null($request->input('descripcion'))) {
            $objeto->descripcion=" ";
            }else{
                $objeto->descripcion=$request->input('descripcion');
            }
        $objeto->niveles_has_periodos_id=$nivelesInPeriodosId;
        $objeto->save();
        $estado=true;
        $resultado='Indicador creado exitosamente.';
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

    public function borrarIndicador($id){
        $estado=false;
        $mensaje="No se ha borrado el registro.";
        $objeto=Indicadores::find($id);
        if(is_object($objeto)){
            $objeto->delete();
            $mensaje="Se ha borrado el registro.";
            $estado=true;
        }
        $noOrphan=new NoOrphanRegisters;
        return response()->json(['mensaje' => $mensaje.$noOrphan->getLimpiarHuerfanos(),"estado"=>$estado]);
    }

    public function actIndicadores($id,Request $request){
        $estado=false;
        $resultado='No se ha actualizado el registro.';
        $validacion=Validator::make($request->all(),[
            'nombre' => 'required',
            'porcentaje'=>'required'
        ]);
        if ($validacion->fails()) {
            return response()->json(['mensaje' => $resultado.' Falta información.',"estado"=>$estado]);
        }
        $objeto=Indicadores::find($id);
        $objeto->nombre=$request->input('nombre');
        $objeto->porcentaje=$request->input('porcentaje');
        if (is_null($request->input('descripcion'))) {
            $objeto->descripcion=" ";
            }else{
                $objeto->descripcion=$request->input('descripcion');
            }
        $objeto->save();
        $estado=true;
        $resultado='Indicador creado exitosamente.';
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

    public function setTipoNotas($indicadoresId,Request $request=null){
        $estado=false;
        $resultado='No se ha creado el registro.';
        $valida2=Indicadores::find($indicadoresId);
        if (!isset($valida2->id)) {
            return response()->json(['mensaje' => $resultado.' No existe el periodo en el nivel.',"estado"=>$estado]);
        }
        $objeto=new TipoNota;
        if (is_null($request->input('nombre'))) {
            $objeto->nombre='No definido';
        }else{
            $objeto->nombre=$request->input('nombre');
        }
        if (is_null($request->input('descripcion'))) {
            $objeto->descripcion=" ";
        }else{
            $objeto->descripcion=$request->input('descripcion');
        }
        $objeto->indicadores_id=$indicadoresId;
        $objeto->save();
        $estado=true;
        $resultado='Indicador creado exitosamente.';
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

    public function actTipoNotas($tipoId,Request $request){
        $estado=false;
        $resultado='No se ha actualizado el registro.';
        $validacion=Validator::make($request->all(),['nombre' => 'required']);
        if ($validacion->fails()) {
            return response()->json(['mensaje' => $resultado.' Falta información.',"estado"=>$estado]);
        }
        $tipo=TipoNota::find($tipoId);
        $tipo->nombre=$request->input('nombre');
        if (is_null($request->input('descripcion'))) {
            $tipo->descripcion=" ";
        }else{
            $tipo->descripcion=$request->input('descripcion');
        };
        $tipo->save();
        $estado=true;
        $resultado='Tipo de nota actualizado correctamente.';
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

    public function setNotas($tipoNotaId,Request $request=null){}

    public function setNotaBasica($tipoNotaId,Request $request=null){
        $estado=false;
        $resultado='No se ha creado el registro.';
        $numero=0;
        $valida2=TipoNota::find($tipoNotaId);
        if (!isset($valida2->id)) {
            return response()->json(['mensaje' => $resultado.' No existe el tipo de nota en el indicador.',"estado"=>$estado]);
        }
        $tipos=TipoNota::with('indicadores.niveles_has_periodos.materias_has_niveles.niveles')->where('id','=',$tipoNotaId)->first();
        $alumnos=Alumnos::select('alumnos.id','users.name','users.lastname')
            ->join('users','alumnos.users_id','=','users.id')
            ->where('niveles_id','=',$tipos->indicadores->niveles_has_periodos->materias_has_niveles->niveles->id)
            ->orderBy('users.lastname','asc')
            ->get();
        foreach ($alumnos as $alumno) {
            $objetos=new Notas;
            $objetos->tipo_nota_id=$tipoNotaId;
            $objetos->nombre_nota='ND';
            $objetos->descripcion='';
            $objetos->calificacion=0;
            $objetos->alumnos_id=$alumno->id;
            $objetos->save();
            $numero++;  
        }
        $resultado=$numero.' notas creadas exitosamente.';
        $estado=true;
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);   
    }

    public function getAlumnosConNotas($idNivelesHasPeriodos){
        $objeto=Alumnos::select('alumnos.id','users.name','users.lastname')
            ->join('users','alumnos.users_id','=','users.id')
            ->join('niveles','alumnos.niveles_id','=','niveles.id')
            ->join('materias_has_niveles','niveles.id','=','materias_has_niveles.niveles_id')
            ->join('niveles_has_periodos','materias_has_niveles.id','=','niveles_has_periodos.materias_has_niveles_id')
            ->with('notas.tipo_nota.indicadores')
            ->where('niveles_has_periodos.id','=',$idNivelesHasPeriodos)->orderBy('users.lastname','asc')->get();
        return $objeto->toJson();
    }
    
    public function getAlumnosConNotasAnterior($idNivelesHasPeriodos){
        $objeto=Alumnos::select('alumnos.id','users.name','users.lastname')
            ->join('users','alumnos.users_id','=','users.id')
            ->join('niveles','alumnos.niveles_id','=','niveles.id')
            ->join('materias_has_niveles','niveles.id','=','materias_has_niveles.niveles_id')
            ->join('niveles_has_periodos','materias_has_niveles.id','=','niveles_has_periodos.materias_has_niveles_id')
            ->with('notas.tipo_nota.indicadores')
            ->where('niveles_has_periodos.id','=',$idNivelesHasPeriodos)->orderBy('users.lastname','asc')->get();
        return $objeto->toJson();
    }

    public function delTipoNotas($id){
        $estado=false;
        $mensaje="No se ha borrado el registro.";
        $objeto=TipoNota::find($id);
        if(is_object($objeto)){
            $objeto->delete();
            $mensaje="Se ha borrado el registro.";
            $estado=true;
        }
        $noOrphan=new NoOrphanRegisters;
        return response()->json(['mensaje' => $mensaje.$noOrphan->getLimpiarHuerfanos(),"estado"=>$estado]);
    }

    public function actNotas($id,$cal){
        $estado=false;
        $resultado='No se ha actualizado el registro.';
        $objeto=Notas::find($id);
        if (!is_object($objeto) || is_null($cal) || $cal>10 || $cal<0) {
            return response()->json(['mensaje' => $resultado.' Falta información.',"estado"=>$estado]);
        }
        $objeto->calificacion=$cal;
        $objeto->save();
        $estado=true;
        $resultado='Indicador creado exitosamente.';
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

}
