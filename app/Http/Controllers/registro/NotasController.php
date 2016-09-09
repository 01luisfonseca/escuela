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

    public function getMateriasHasNiveles(){ //Funcion usada para la versión vieja
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

    public function getNivelesAuth(){ // Funciones para la versión nueva, para niveles.
        if(auth()->user()->tipo_usuario_id==4){
            $empleado=Empleados::where('users_id','=',auth()->user()->id)->first();
            $materias_in_niveles=MateriasHasNiveles::where('empleados_id','=',$empleado->id)->with('niveles')->get();
            $nivelesArr= array();
            foreach ($materias_in_niveles as $materia) {
                if(isset($materia->niveles->id)){
                    // Agregamos todos los items de nivel existentes
                    $nivelesArr[]=['id'=>$materia->niveles->id, 'nombre_nivel'=>$materia->niveles->nombre_nivel];
                }
            }
            $niveles=collect($nivelesArr);
            $sorted=$niveles->sortBy('nombre_nivel'); // Organiza por nombre
            $niveles=$sorted->unique(); // Elimina repetidos
            return $niveles->toJson();

        }elseif (auth()->user()->tipo_usuario_id>4) {
            $niveles=Niveles::select('id','nombre_nivel')->where('id','>',0)->orderBy('nombre_nivel')->get();
            return $niveles->toJson();
        }
    }

    public function getMateriasAuth($nivelId){ // Funcion nueva para buscar solo materias
        $materias_in_niveles=[];
        if(auth()->user()->tipo_usuario_id==4){
            // Carga las materias del empleado
            $empleado=Empleados::where('users_id','=',auth()->user()->id)->first();
            $materias_in_niveles=MateriasHasNiveles::where('empleados_id','=',$empleado->id)->where('niveles_id','=',$nivelId)->with('materias')->get();
        }elseif (auth()->user()->tipo_usuario_id>4) {
            // Carga las materias
            $materias_in_niveles=MateriasHasNiveles::where('niveles_id','=',$nivelId)->with('materias')->get();
        }else{
            return response()->json(['Alerta'=>'No tiene permisos suficientes'],401);
        }
        $materiasArr= array();
        foreach ($materias_in_niveles as $materia) {
            if(isset($materia->niveles->id)){
                // Agregamos todos los items de materia existentes
                $materiasArr[]=['id'=>$materia->id, 'nombre_materia'=>$materia->materias->nombre_materia];
            }
        }
        $materias=collect($materiasArr);
        $sorted=$materias->sortBy('nombre_materia'); // Organiza por nombre
        $materias=$sorted->unique(); // Elimina repetidos
        //dd($materias);
        return $materias->toJson();
    }

    public function getPeriodosPorMateria($matId){ // Nueva funcion de busqueda de periodos
        $objeto=NivelesHasPeriodos::with('periodos')->where('materias_has_niveles_id','=',$matId)->get();
        $periodosArr=array();
        foreach ($objeto as $periodo) {
            if(isset($periodo->periodos->id)){
                // Agregamos todos los items de periodo existentes
                $periodosArr[]=['id'=>$periodo->id, 'nombre_periodo'=>$periodo->periodos->nombre_periodo];
            }
        }
        $periodos=collect($periodosArr);
        $sorted=$periodos->sortBy('nombre_periodo'); // Organiza por nombre
        $periodos=$sorted->unique(); // Elimina repetidos
        //dd($periodos);
        return $periodos->toJson();
    }

    public function getNivelesHasPeriodos($id){ // Funcion vieja de busqueda de periodos
        $objeto=NivelesHasPeriodos::with('periodos')->where('materias_has_niveles_id','=',$id)->get();
        return $objeto->toJson();
    }

    public function getNewIndicadores($periodoId){// Nueva funcion de indicadores 
        $indicadoresRaw=Indicadores::with(['tipo_nota'=>function($query1){
                $query1->orderBy('id','asc')->with('notas');
            }])->where('niveles_has_periodos_id',$periodoId)->get();
        $periodo=NivelesHasPeriodos::with('materias_has_niveles.niveles.alumnos.users')->where('id',$periodoId)->first();
        $alumnos=array();
        foreach ($periodo->materias_has_niveles->niveles->alumnos as $alumno) {
            // Rellenamos la tabla del alumno y las notas
            $alumnos[]=[
                'id'=>$alumno->id,
                'users_id'=>$alumno->users_id,
                'name'=>$alumno->users->name,
                'lastname'=>$alumno->users->lastname,
                'tipo_nota'=>[]
            ]; // Acá vamos. Falta crear tabla de indicadores y dentro los alumnos, y demtro de alumnos el tipo de notas.
        }
        // Para ordenar los registros de alumno. Es quien da el orden de los nombres.
        $temp=collect($alumnos);
        $sorted=$temp->sortBy('lastname');
        $alumnos=$sorted->all();
        $indicadores=array();
        // Truco para deshacerme de las llaves personalizadas
        $tempo=[];
        foreach ($alumnos as $key => $value) {
            $tempo[]=$value;
        }
        // Creo la tabla principal de indicadores
        foreach ($indicadoresRaw as $indicador) {
            // Asignamos información básica del indicador y alumnos
            $indicadores[]=[
                'id'=>$indicador->id,
                'nombre'=>$indicador->nombre,
                'descripcion'=>$indicador->descripcion,
                'porcentaje'=>$indicador->porcentaje,
                'tipo_nota'=>array(),
                'alumnos'=>$tempo,
                ];
        }
        $i=0;
        // Se recargan los tipos de notas y los alumnos con sus notas
        foreach ($indicadoresRaw as $indicador) {
            foreach ($indicador->tipo_nota as $tipo) {
                // Revisamos cada nota del indicador
                foreach ($tipo->notas as $nota) {
                    // Revisamos cada alumno
                    foreach ($indicadores[$i]['alumnos'] as &$alumno) {
                        // Verificamos que cada nota tenga un alumno. Al encontrarlo ingresamos la nota en los tipos de nota propios del alumno.
                        if($nota->alumnos_id==$alumno['id']){
                            array_push($alumno['tipo_nota'], ['id'=>$tipo->id,'notas_id'=>$nota->id,'cal'=>$nota->calificacion]);
                        }
                        unset($alumno); // Requerido para el foreach con apuntamiento
                    }
                }
                // Agregamos los tipos de nota del indicador
                array_push($indicadores[$i]['tipo_nota'], ['id'=>$tipo->id,'nombre'=>$tipo->nombre,'descripcion'=>$tipo->descripcion]);
            }
            $i++;
        }
        $indicadoresObj=collect($indicadores);
        //dd($indicadoresObj);
        return $indicadoresObj->toJson();
    }

    public function getIndicadores($id){ // Funcion vieja de obtener indicadores
        $nivelId=0;
        $creado=false;
        try{
             $objeto=NivelesHasPeriodos::with([
                    'indicadores'=>function($query){
                        $query->orderBy('id','asc');
                        $query->with(['tipo_nota'=>function($query){
                            $query->orderBy('id','asc');
                            $query->with(['notas'=>function($query){
                                $query->orderBy('id','asc');
                                $query->with(['alumnos'=>function($query){
                                    $query->with(['users'=>function($query){
                                        $query->select('id','name','lastname');
                                    }]);
                                }]);
                            }]);
                        }]);
                    },
                    'periodos',
                    'materias_has_niveles',
                    'materias_has_niveles.materias',
                    'materias_has_niveles.niveles'
                ])->where('id','=',$id)->get();
            $nivelId=$objeto[0]->materias_has_niveles->niveles->id;
            /*$alumnos=Alumnos::where('niveles_id','=',$nivelId)->get();
            //dd($objeto);
            /*foreach ($alumnos as $alumno) {
                foreach ($objeto[0]->indicadores as $indicador) {
                    foreach ($indicador->tipo_nota as $tipo) {
                        $encontrado=false;
                        $tipoNotaId=$tipo->id;
                        foreach ($tipo->notas as $nota) {
                            if ($alumno->id==$nota->alumnos->id) {
                                $encontrado=true;
                            }
                        }
                        if (!$encontrado) {
                            $this->setNotaAlumno($tipo->id,$alumno->id)?Log::info('Creado alumno nuevo: '.$alumno->id.'; tipo de nota: '.$tipo->id):Log::info('Error de creación de nota de alumno nuevo: '.$alumno->id.'; tipo de nota: '.$tipo->id);
                            $creado=true;
                        }
                    }
                }
            }
            if(!$creado){
                return $objeto->toJson();
            }
            return $this->getIndicadores($id);*/
            return $objeto->toJson();
            
        }catch(Exception $e){
            Log::info('Ha pasado un error en GetIndicadores');
        }
       
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
        $countNotasElim=0;
        $countTiposElim=0;
        $objeto=Indicadores::with('tipo_nota.notas')->find($id);
        if(is_object($objeto)){
            //Se borran todas las notas relacionadas con el indicador.
            foreach ($objeto->tipo_nota as $tipo) {
                foreach ($tipo->notas as $nota) {
                    $notaelim=Notas::find($nota->id);
                    if(is_object($notaelim)){
                        $notaelim->delete();
                        $countNotasElim++;
                    }
                }
                $tipoelim=TipoNota::find($tipo->id);
                if (is_object($tipoelim)) {
                    $tipoelim->delete();
                    $countTiposElim++;
                }
            }
            $objeto->delete();
            $mensaje="Se ha borrado el registro y se han borrado ".$countTiposElim.' tipos de notas, y '.$countNotasElim.' notas';
            $estado=true;
        }
        $noOrphan=new NoOrphanRegisters;
        return response()->json(['mensaje' => $mensaje,"estado"=>$estado]);
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
        $resultado='Tipo de nota creado exitosamente. ';
        $resultado.=$this->setNotaBasica($objeto->id);
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
    
    public function setNotaAlumno($tipoNotaId,$alumnoId){
        if ($tipoNotaId && $alumnoId) {
            $objetos=new Notas;
            $objetos->tipo_nota_id=$tipoNotaId;
            $objetos->nombre_nota='ND';
            $objetos->descripcion='';
            $objetos->calificacion=0;
            $objetos->alumnos_id=$alumnoId;
            $objetos->save();
            return true;
        }
        return false;
    }

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
    
    public function getAlumnosEnNivel($idNiveles){
        $objeto=Alumnos::where('niveles_has_periodos.id','=',$idNivelesHasPeriodos)->orderBy('users.lastname','asc')->get();
        return $objeto->toJson();
    }
    
    public function rellenarAlumnosNuevosNivel(Request $request){
        $objeto=Alumnos::where('niveles_has_periodos.id','=',$idNivelesHasPeriodos)->orderBy('users.lastname','asc')->get();
        return $objeto->toJson();
    }

    public function delTipoNotas($id){
        $estado=false;
        $mensaje="No se ha borrado el registro.";
        $objeto=TipoNota::with('notas')->find($id);
        $countNotasElim=0;
        if(is_object($objeto)){
            foreach ($objeto->notas as $nota) {
                $notDel=Notas::find($nota->id);
                if (is_object($notDel)) {
                    $notDel->delete();
                    $countNotasElim++;
                }
            }
            $objeto->delete();
            $mensaje="Se ha borrado el registro. También se eliminaron ".$countNotasElim.' notas.';
            $estado=true;
        }
        $noOrphan=new NoOrphanRegisters;
        return response()->json(['mensaje' => $mensaje,"estado"=>$estado]);
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
