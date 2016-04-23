<?php

namespace App\Http\Controllers\registro;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\NoOrphanRegisters;

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
use App\modelos\PagoSalario;
use App\modelos\PrecioMatricula;
use App\modelos\PrecioPension;
use App\modelos\TipoUsuario;
use App\modelos\Users;

//Para validacion

use Validator;


class RegistroController extends Controller
{
    
    public function index(){
    	return view('registro/registro');
    }

    /*Funcion de Alumnos*/

    private function nivelesOrden(){
        return Niveles::where('nombre_nivel','<>','')->orderBy('nombre_nivel','ASC')->get();
    }

    private function homeAlumno($respuesta,$result){
        $alumnos=User::where('tipo_usuario_id','=',2)->orderBy('name','ASC')->get();
        return view('registro/alumnocrear',[
            'principal'=>$alumnos, 
            'actuales'=>$this->ultimosAlumnos(),
            'nivel'=>$this->nivelesOrden(),
            'respuesta'=>$respuesta,
            'result'=>$result,
            ]);
    }

    public function getAlumnoCrear(){
        return $this->homeAlumno(NULL,NULL);
    }//Probado y funcionando

    private function ultimosAlumnos(){
        return Alumnos::orderBy('updated_at','DESC')->take(10)->get();
    }//Probado y funcionando

    private function busquedaalm(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion',
                'niveles.nombre_nivel','niveles.descripcion')->join('users','alumnos.users_id','=','users.id')
                ->join('niveles','alumnos.niveles_id','=','niveles.id')
                ->where('niveles.nombre_nivel','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('niveles.descripcion','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.name','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.identificacion','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.lastname','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            return $this->homeAlumno(1,$result);
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearalm(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'niveles_id' => 'required',
            'users_id' => 'required'
        ]);
        if(!$validacion->fails()){
            $objeto=new Alumnos;
            $objeto->users_id=$request->input('users_id');
            $objeto->niveles_id=$request->input('niveles_id');
            $objeto->pension=$request->input('pension');
            $objeto->descripcion_pen=$request->input('descripcion_pen');
            $objeto->matricula=$request->input('matricula');
            $objeto->descripcion_mat=$request->input('descripcion_mat');
            $objeto->save();
            $resultado='Registro creado exitosamente';
        }
        return back()->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando

    public function postAlumnoCrear(Request $request){
        if($request->has('busqueda')){
            return $this->busquedaalm($request);
        }else{
            return $this->crearalm($request);
        }
    }//Llega post de crear y de buscar //Probado y funcionando

    private function modificaralm(Request $request=null,$alumno_id=null){
        $resultado='No se ha modificado el registro. Vuelva a intentarlo';
        $validacion=null;
        $verifica=false;
        $objeto=null;
        if (isset($request)) {
            $validacion=Validator::make($request->all(),[
                'niveles_id' => 'required',
                'users_id' => 'required'
            ]);
            if (!$validacion->fails()) {
                $objeto=Alumnos::find($request->input('id'));
                $verifica=true;
            }
        }elseif (isset($alumno_id) && $alumno_id) {
            $objeto=Alumnos::find($alumno_id);
            if (isset($objeto) && $objeto->count>0) {
                $verifica=true;
            }
        }
        if ($verifica) {
            $objeto->users_id=$request->input('users_id');
            $objeto->niveles_id=$request->input('niveles_id');
            $objeto->pension=$request->input('pension');
            $objeto->descripcion_pen=$request->input('descripcion_pen');
            $objeto->matricula=$request->input('matricula');
            $objeto->descripcion_mat=$request->input('descripcion_mat');
            $objeto->save();
            $resultado='Registro de alumno actualizado';
        }
        return redirect()->route('crear_alumno')->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando

    private function eliminaralm(Request $request){
        $noOrphan=new NoOrphanRegisters;
        $objeto=Alumnos::find($request->input('id'));
        $objeto->delete();
        $resultado='Se eliminó al estudiante correctamente. ';
        return redirect()->route('crear_alumno')->withFlashMessage($resultado.$noOrphan->getLimpiarHuerfanos());
    }//Probado y funcionando

    public function editarAlumno($id){
        return $this->getAlumnoEditar(null,$id);
    }

    public function getAlumnoEditar(Request $request=null,$alumnos_id=null){
        if (!is_null($request)) {
            if($request->has('modificar')){
                return $this->modificaralm($request);
            }
            if($request->has('eliminar')){
                return $this->eliminaralm($request);
            }
            if($request->has('accion')){
                $result=Alumnos::find($request->input('id'));
                $accion=$request->input('accion');
                return view('registro/alumnoeditar',['accion'=>$accion,'result'=>$result,'nivel'=>$this->nivelesOrden()]);
            }   
        }elseif (isset($alumnos_id) && $alumnos_id) {
            $result=Alumnos::find($alumnos_id);
            $accion=0;
            if (!is_null($result)) {
                if ($result->count()>0) {
                    return view('registro/alumnoeditar',['accion'=>$accion,'result'=>$result,'nivel'=>$this->nivelesOrden()]);
                }
            }
            return back()->withFlashMessage('No se encuentra el alumno');
        }
        return redirect()->route('crear_alumno');
    }//Probado y funcionando

    public function getAlumnoActual($alumnos_id){
        return $this->getAlumnoEditar(null,$alumnos_id);
    }

    public function deleteAlumnoEditar(Request $request){
        return redirect()->route('registro');
    }

    /*Fin Funcion de Alumnos*/

    /*Funciones para profesor*/

    private function homeProfesor($nivel_seleccionado,$materiainnivel_seleccionado){
        return view('registro/profesorcrear',[
                'nivel'=>Niveles::all(),
                'nivel_seleccionado'=>$nivel_seleccionado,
                'nivel_respuesta'=>Niveles::find($nivel_seleccionado),
                'materiaInNivel'=>MateriasHasNiveles::where('niveles_id','=',$nivel_seleccionado)->get(),
                'materiaInNivelId'=>MateriasHasNiveles::find($materiainnivel_seleccionado),
                'materiainnivel_seleccionado'=>$materiainnivel_seleccionado,
                'empleado'=>Empleados::all(),
                'materiaInNivelConProfe'=>MateriasHasNiveles::with('materias','empleados','niveles')->where('empleados_id','>','0')->orderBy('niveles_id','DESC')->get(),
                'materiaInNivelSinProfe'=>MateriasHasNiveles::with('materias','empleados','niveles')->where('empleados_id','=','0')->orderBy('niveles_id','DESC')->get()
                ]);
    }

    public function getProfesorCrear(){
        return $this->homeProfesor(NULL,NULL);
    }

    public function postProfesorCrear(Request $request){

        if($request->input('nivel_id')){
            return $this->homeProfesor($request->input('nivel_id'),NULL);
        }

        if($request->input('materiainnivel_id')){
            $resultado='Seleccione una materia válida';
            $validacion=Validator::make($request->all(),[
                'materiainnivel_id' => 'required'
            ]);
            if($validacion->fails()){
                return back()->withFlashMessage($resultado)->withErrors($validacion);
            }
            return $this->homeProfesor($request->input('nivel_seleccionado'),$request->input('materiainnivel_id'));
        }

        if($request->input('empleados_id')){
            $resultado='No se modifican registros';
            $validacion=Validator::make($request->all(),[
            'empleados_id' => 'required'
            ]);
            if(!$validacion->fails()){
                $objeto=MateriasHasNiveles::find($request->input('materiainnivel_seleccionado'));
                $objeto->empleados_id=$request->input('empleados_id');
                $objeto->save();
                $resultado="Registro modificado";
            }
            return back()->withFlashMessage($resultado)->withErrors($validacion);
        }

        return redirect()->route('crear_profesor')->withFlashMessage('No se puede realizar acción sin la información suficiente');
    }

    public function getProfesorEditar(Request $request){
        $objeto=MateriasHasNiveles::find($request->input('id'));
        $objeto->empleados_id=0;
        $objeto->save();
        return $this->homeProfesor(NULL,NULL);

    }

    public function putProfesorEditar(Request $request){
        return redirect()->route('registro');
    }

    public function deleteProfesorEditar(Request $request){
        return redirect()->route('registro');
    }

    /*Fin para Funciones para profesor*/

    /*Funciones para asistencia*/

    private function homeAsistencia($nivel_seleccionado,$materiainnivel_seleccionado,$periodoinmateria_seleccionado,$mensajefinal){
        return view('registro/asistenciacrear',[
                'nivel'=>Niveles::all(),
                'nivel_seleccionado'=>$nivel_seleccionado,
                'nivel_respuesta'=>Niveles::find($nivel_seleccionado),
                'materiaInNivel'=>MateriasHasNiveles::where('niveles_id','=',$nivel_seleccionado)->get(),
                'materiaInNivelId'=>MateriasHasNiveles::find($materiainnivel_seleccionado),
                'materiainnivel_seleccionado'=>$materiainnivel_seleccionado,
                'periodoinmateria_seleccionado'=>$periodoinmateria_seleccionado,
                'periodoInMateria'=>NivelesHasPeriodos::where('materias_has_niveles_id','=',$materiainnivel_seleccionado)->get(),
                'periodoInMateriaId'=>NivelesHasPeriodos::find($periodoinmateria_seleccionado),
                'mensajefinal'=>$mensajefinal,
                'actuales'=>Asistencia::where('niveles_has_periodos_id','=',$periodoinmateria_seleccionado)->orderBy('created_at','DESC')->take(100)->get()
                ]);
    }

    public function getAsistenciaCrear(Request $request){
    	return $this->homeAsistencia(NULL,NULL,NULL,NULL);
    }

    public function postAsistenciaCrear(Request $request){

        if($request->input('nivel_id')){
            return $this->homeAsistencia($request->input('nivel_id'),NULL,NULL,NULL);
        }

        if($request->input('materiainnivel_id')){
            $resultado='Seleccione una materia válida';
            $validacion=Validator::make($request->all(),[
                'materiainnivel_id' => 'required'
            ]);
            if($validacion->fails()){
                return back()->withFlashMessage($resultado)->withErrors($validacion);
            }
            return $this->homeAsistencia($request->input('nivel_seleccionado'),$request->input('materiainnivel_id'),NULL,NULL);
        }

        if($request->input('periodoinmateria_id')){
            $resultado='No se modifican registros';
            $validacion=Validator::make($request->all(),[
                'periodoinmateria_id' => 'required'
            ]);
            if($validacion->fails()){
                return back()->withFlashMessage($resultado)->withErrors($validacion);
            }
            return $this->homeAsistencia($request->input('nivel_seleccionado'),$request->input('materiainnivel_seleccionado'),$request->input('periodoinmateria_id'),NULL);
        }

        if($request->input('asistenciacode')){
            $usercode=0;
            $resultado='Asistencia erronea. No se modifican registros';
            $validacion=Validator::make($request->all(),[
            'asistenciacode' => 'required'
            ]);
            if($validacion->fails()){
                $resultado='El campo de asistencia es obligatorio';
            }else{
                 $validacion2=Validator::make($request->all(),[
                'asistenciacode' => 'regex:/\([\d]+\(/' //Expresión regular de asistencia
                ]);
                 if($validacion2->fails()){
                    $resultado='El código de asistencia no tiene el formato requerido';
                 }else{
                    $usercode=trim($request->input('asistenciacode'),"(");
                    $result=Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion',
                        'niveles.nombre_nivel')
                        ->join('users','alumnos.users_id','=','users.id')
                        ->join('niveles','alumnos.niveles_id','=','niveles.id')
                        ->where('users.identificacion','=',$usercode)
                        ->first();
                    if(isset($result) && $result->id){
                        $asist= new Asistencia;
                        $asist->niveles_has_periodos_id=$request->input('periodoinmateria_seleccionado');
                        $asist->alumnos_id=$result->id;
                        $asist->save();
                        $resultado='Alumno: '.$result->name.' '.$result->lastname.'. Asistencia registrada en nivel '.$result->nombre_nivel.'.';
                    }else{
                        $resultado='El codigo del alumno no está registrado en el nivel. Ajuste en sección alumnos';
                    }
                 }
            }
            return $this->homeAsistencia($request->input('nivel_seleccionado'),$request->input('materiainnivel_seleccionado'),$request->input('periodoinmateria_seleccionado'),$resultado);
        }

        return redirect()->route('crear_asistencia')->withFlashMessage('No se puede realizar acción sin la información suficiente');
    }

    public function getAsistenciaEditar(){
    	return view('registro/asistenciaeditar');
    }

    public function putAsistenciaEditar(Request $request){
    	return redirect()->route('registro');
    }

    public function deleteAsistenciaEditar(Request $request){
    	return redirect()->route('registro');
    }

    /*Fin para Funciones para asistencia*/

    /*Funciones para rendimiento*/

    private function homeRendimiento($nivel_seleccionado,$materiainnivel_seleccionado,$periodoinmateria_seleccionado,$mensajefinal){
        return view('registro/rendimientocrear',[
                'nivel'=>Niveles::all(),
                'nivel_seleccionado'=>$nivel_seleccionado,
                'nivel_respuesta'=>Niveles::find($nivel_seleccionado),
                'materiaInNivel'=>MateriasHasNiveles::where('niveles_id','=',$nivel_seleccionado)->get(),
                'materiaInNivelId'=>MateriasHasNiveles::find($materiainnivel_seleccionado),
                'materiainnivel_seleccionado'=>$materiainnivel_seleccionado,
                'periodoinmateria_seleccionado'=>$periodoinmateria_seleccionado,
                'periodoInMateria'=>NivelesHasPeriodos::where('materias_has_niveles_id','=',$materiainnivel_seleccionado)->get(),
                'periodoInMateriaId'=>NivelesHasPeriodos::find($periodoinmateria_seleccionado),
                'mensajefinal'=>$mensajefinal,
                'alumnosInNivel'=>Alumnos::where('niveles_id','=',$nivel_seleccionado)->get(),
                'actuales'=>Notas::where('niveles_has_periodos_id','=',$periodoinmateria_seleccionado)->orderBy('created_at','DESC')->take(100)->get()
                ]);
    }

    public function getRendimientoCrear(Request $request){
        return $this->homeRendimiento(NULL,NULL,NULL,NULL);
    }

    public function postRendimientoCrear(Request $request){

        if($request->input('nivel_id')){
            return $this->homeRendimiento($request->input('nivel_id'),NULL,NULL,NULL);
        }

        if($request->input('materiainnivel_id')){
            $resultado='Seleccione una materia válida';
            $validacion=Validator::make($request->all(),[
                'materiainnivel_id' => 'required'
            ]);
            if($validacion->fails()){
                return back()->withFlashMessage($resultado)->withErrors($validacion);
            }
            return $this->homeRendimiento($request->input('nivel_seleccionado'),$request->input('materiainnivel_id'),NULL,NULL);
        }

        if($request->input('periodoinmateria_id')){
            $resultado='No se modifican registros';
            $validacion=Validator::make($request->all(),[
                'periodoinmateria_id' => 'required'
            ]);
            if($validacion->fails()){
                return back()->withFlashMessage($resultado)->withErrors($validacion);
            }
            return $this->homeRendimiento($request->input('nivel_seleccionado'),$request->input('materiainnivel_seleccionado'),$request->input('periodoinmateria_id'),NULL);
        }

        if(is_array($request->calificacion)){
            
            $usercode=0;
            $resultado='Nota erronea. No se modifican registros';
            $validacion=Validator::make($request->all(),[
            'nombre_nota' => 'required'
            ]);
            if(!$validacion->fails()){
                foreach($request->calificacion as $llave => $calificacion){
                    $nota= new Notas;
                    $nota->nombre_nota=$request->input('nombre_nota');
                    $nota->descripcion=$request->input('descripcion');
                    $nota->niveles_has_periodos_id=$request->input('periodoinmateria_seleccionado');
                    $nota->alumnos_id=$llave; 
                    $nota->calificacion=$calificacion;
                    $nota->save();
                }
                
                $resultado='Se registran '.count($request->calificacion).' notas.';
            }
            return $this->homeRendimiento($request->input('nivel_seleccionado'),$request->input('materiainnivel_seleccionado'),$request->input('periodoinmateria_seleccionado'),$resultado);

        }

        return redirect()->route('crear_rendimiento')->withFlashMessage('No se puede realizar acción sin la información suficiente');
    }

    public function getRendimientoEditar(){
        return view('registro/asistenciaeditar');
    }

    public function putRendimientoEditar(Request $request){
        return redirect()->route('registro');
    }

    public function deleteRendimientoEditar(Request $request){
        return redirect()->route('registro');
    }

    /*Fin para Funciones para rendimiento*/

    /*Funciones para estudiantil*/

    private function respuestaEstudiantil($nivel_seleccionado,$materia_seleccionado=null,$periodoinmateria_seleccionado=null){
        return view('registro/estudiantilcrear',[
                'nivel'=>Niveles::all(),
                'nivel_seleccionado'=>$nivel_seleccionado,
                'nivel_respuesta'=>Niveles::find($nivel_seleccionado),
                'materiaInNivel'=>MateriasHasNiveles::where('niveles_id','=',$nivel_seleccionado)->get(),
                'materia_seleccionado'=>$materia_seleccionado,
                'materia_respuesta'=>MateriasHasNiveles::with('materias')->find($materia_seleccionado),
                'periodoInMateria'=>NivelesHasPeriodos::where('materias_has_niveles_id','=',$materia_seleccionado)->get(),
                'periodoinmateria_seleccionado'=>$periodoinmateria_seleccionado,
                'periodoinmateria_respuesta'=>NivelesHasPeriodos::with('periodos')->find($periodoinmateria_seleccionado),
                'result'=>Alumnos::with(['users','nota'=>function($query) use ($periodoinmateria_seleccionado){
                        $query->where('niveles_has_periodos_id','=',$periodoinmateria_seleccionado);
                    },'asistencia'=>function($query) use ($periodoinmateria_seleccionado){
                        $query->where('niveles_has_periodos_id','=',$periodoinmateria_seleccionado);
                    }])->where('niveles_id','=',$nivel_seleccionado)
                    ->get()
                ]);
    }

    public function getEstudiantilCrear(){
    	return view('registro/estudiantilcrear');
    }

    public function postEstudiantilCrear(Request $request){
        if ($request->has('nivel_id')) {
            return $this->respuestaEstudiantil($request->input('nivel_id'),NULL,NULL);
        }
        if ($request->has('materia_id')) {
            return $this->respuestaEstudiantil($request->input('nivel_seleccionado'),$request->input('materia_id'),NULL);
        }
        if ($request->has('periodoinmateria_id')) {
            return $this->respuestaEstudiantil($request->input('nivel_seleccionado'),$request->input('materia_seleccionado'),$request->input('periodoinmateria_id'));
        }
        if ($request->has('periodoinmateria_seleccionado')) {
            return $this->respuestaEstudiantil($request->input('nivel_seleccionado'),$request->input('materia_seleccionado'),$request->input('periodoinmateria_seleccionado'));
        }
    }

    public function getEstudiantilEditar(){
    	return view('registro/estudiantileditar');
    }

    public function putEstudiantilEditar(Request $request){
    	return redirect()->route('registro');
    }

    public function deleteEstudiantilEditar(Request $request){
    	return redirect()->route('registro');
    }

    public function getNivelesEstudiante(){
        $niveles=Alumnos::with('niveles')->where('users_id','=',auth()->user()->id)->orderBy('created_at','desc')->get();
        return $niveles->toJson();
    }

    public function getActividad($alumnos_id){
        $temp=Alumnos::find($alumnos_id);
        $alumno=Niveles::with(['materias_has_niveles'=>function($query) use($alumnos_id){
            $query->with(['materias',
               'empleados'=>function($query){
                    $query->with('users');
                },
               'niveles_has_periodos'=>function ($query) use($alumnos_id) {
                    $query->with(['periodos',"indicadores"=>function($query) use($alumnos_id){
                        $query->with(['tipo_nota'=>function($query) use($alumnos_id) {
                            $query->with(['notas'=>function($query) use($alumnos_id) {
                                $query->where('alumnos_id','=',$alumnos_id);
                            }]);
                        }])->orderBy('nombre','asc');
                    }])->orderBy('periodos_id','asc');
                }]);
            }])->find($temp->niveles_id);
        return $alumno->toJson();
    }

    /*Fin para Funciones para estudiantil*/
}
