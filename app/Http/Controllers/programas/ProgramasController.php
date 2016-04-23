<?php

namespace App\Http\Controllers\programas;

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

//Para validacion

use Validator;


class ProgramasController extends Controller
{
    
    public function index(){
    	return view('programas/programas');
    }//Probado y funcionando


    /*Funciones para nivel*/

    public function getNivelCrear(){
    	return view('programas/nivelcrear',['principal'=>Niveles::all()]);
    }//Probado y funcionando.

    private function busquedaniv(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Niveles::where('nombre_nivel','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('descripcion','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            return view('programas/nivelcrear',['respuesta'=>1,'result'=>$result,'principal'=>Niveles::all()]);
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearniv(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'nombre_nivel' => 'required|min:3|max:50|unique:niveles,nombre_nivel'
        ]);
        if(!$validacion->fails()){
            $nivel=new Niveles;
            $nivel->nombre_nivel=$request->input('nombre_nivel');
            $nivel->descripcion=$request->input('descripcion');
            $nivel->save();
            $resultado='Registro creado exitosamente';
        }
        return back()->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando

    public function postNivelCrear(Request $request){
        if($request->has('busqueda')){
            return $this->busquedaniv($request);
        }else{
            return $this->crearniv($request);
        }
    }//Llega post de crear y de buscar //Probado y funcionando

    public function getNivelEditar(Request $request){
        if($request->has('nombre_nivel')){
            $nivel=Niveles::find($request->id);
            if($request->has('eliminar')){
                $noOrphan=new NoOrphanRegisters;
                $nivel->delete();
                return redirect()->route('crear_nivel')->withFlashMessage('Nivel eliminado. '.$noOrphan->getLimpiarHuerfanos());
            }else{
                $resultado='No se ha modificado el registro. Vuelva a intentarlo';
                $validacion=Validator::make($request->all(),[
                    'nombre_nivel' => 'required|min:3|max:50'
                ]);
                if(!$validacion->fails()){
                    $nivel->nombre_nivel=$request->nombre_nivel;
                    $nivel->descripcion=$request->descripcion;
                    $nivel->save();
                    $resultado='Registro modificado';
                    return redirect()->route('crear_nivel')->withFlashMessage($resultado);
                }
                return redirect()->route('crear_nivel')->withFlashMessage($resultado)->withErrors($validacion);
            }
        }else{
            $result=Niveles::find($request->input('id'));
            return view('programas/niveleditar',['accion'=>$request->input('accion'),'result'=>$result]);
        }
    }//Probado y funcionando

    public function putNivelEditar(Request $request){
    	return redirect()->route('programas');
    }

    public function deleteNivelEditar(Request $request){
    	return redirect()->route('programas');
    }

    /*Fin para Funciones para nivel*/

    /*Funciones para materia*/

    public function getMateriaCrear(){
    	return view('programas/materiacrear',['principal'=>Materias::all()]);
    }//Probado y funcionando

    private function busquedamat(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Materias::where('nombre_materia','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('descripcion','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            return view('programas/materiacrear',['respuesta'=>1,'result'=>$result,'principal'=>Materias::all()]);
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearmat(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'nombre_materia' => 'required|min:3|max:50|unique:materias,nombre_materia'
        ]);
        if(!$validacion->fails()){
            $objeto=new Materias;
            $objeto->nombre_materia=$request->input('nombre_materia');
            $objeto->descripcion=$request->input('descripcion');
            $objeto->save();
            $resultado='Registro creado exitosamente';
        }
        return back()->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando

    public function postMateriaCrear(Request $request){
    	if($request->has('busqueda')){
            return $this->busquedamat($request);
        }else{
            return $this->crearmat($request);
        }
    }//Probado y funcionando

    public function getMateriaEditar(Request $request){
    	if($request->has('nombre_materia')){
            $objeto=Materias::find($request->id);
            if($request->has('eliminar')){
                $noOrphan=new NoOrphanRegisters;
                $objeto->delete();
                return redirect()->route('crear_materia')->withFlashMessage('Materia eliminada. '.$noOrphan->getLimpiarHuerfanos());
            }else{
                $resultado='No se ha modificado el registro. Vuelva a intentarlo';
                $validacion=Validator::make($request->all(),[
                    'nombre_materia' => 'required|min:3|max:50'
                ]);
                if(!$validacion->fails()){
                    $objeto->nombre_materia=$request->nombre_materia;
                    $objeto->descripcion=$request->descripcion;
                    $objeto->save();
                    $resultado='Registro modificado';
                    return redirect()->route('crear_materia')->withFlashMessage($resultado);
                }
                return redirect()->route('crear_materia')->withFlashMessage($resultado)->withErrors($validacion);
            }
        }else{
            $result=Materias::find($request->input('id'));
            return view('programas/materiaeditar',['accion'=>$request->input('accion'),'result'=>$result]);
        }
    }//Probado y funcionando

    public function putMateriaEditar(Request $request){
    	return redirect()->route('programas');
    }

    public function deleteMateriaEditar(Request $request){
    	return redirect()->route('programas');
    }

    /*Fin para Funciones para materia*/

    /*Funciones para periodo*/

    public function getPeriodoCrear(){
    	return view('programas/periodocrear',['principal'=>Periodos::all()]);
    }//Probado y funcionando

    private function busquedaper(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Periodos::where('nombre_periodo','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('descripcion','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            return view('programas/periodocrear',['respuesta'=>1,'result'=>$result,'principal'=>Periodos::all()]);
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearper(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'nombre_periodo' => 'required|min:3|max:50|unique:periodos,nombre_periodo'
        ]);
        if(!$validacion->fails()){
            $objeto=new Periodos;
            $objeto->nombre_periodo=$request->input('nombre_periodo');
            $objeto->fecha_ini=$request->input('fecha_ini');
            $objeto->fecha_fin=$request->input('fecha_fin');
            $objeto->descripcion=$request->input('descripcion');
            $objeto->save();
            $resultado='Registro creado exitosamente';
        }
        return back()->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando


    public function postPeriodoCrear(Request $request){
    	if($request->has('busqueda')){
            return $this->busquedaper($request);
        }else{
            return $this->crearper($request);
        }
    }//Probado y funcionando

    public function getPeriodoEditar(Request $request){
    	if($request->has('nombre_periodo')){
            $objeto=Periodos::find($request->id);
            if($request->has('eliminar')){
                $noOrphan=new NoOrphanRegisters;
                $objeto->delete();
                return redirect()->route('crear_periodo')->withFlashMessage('Periodo eliminado. '.$noOrphan->getLimpiarHuerfanos());
            }else{
                $resultado='No se ha modificado el registro. Vuelva a intentarlo';
                $validacion=Validator::make($request->all(),[
                    'nombre_periodo' => 'required|min:3|max:50'
                ]);
                if(!$validacion->fails()){
                    $objeto->nombre_periodo=$request->nombre_periodo;
                    $objeto->descripcion=$request->descripcion;
                    $objeto->fecha_ini=$request->fecha_ini;
                    $objeto->fecha_fin=$request->fecha_fin;
                    $objeto->save();
                    $resultado='Registro modificado';
                    return redirect()->route('crear_periodo')->withFlashMessage($resultado);
                }
                return redirect()->route('crear_periodo')->withFlashMessage($resultado)->withErrors($validacion);
            }
        }else{
            $result=Periodos::find($request->input('id'));
            return view('programas/periodoeditar',['accion'=>$request->input('accion'),'result'=>$result]);
        }
    }//Probado y funcionando

    public function putPeriodoEditar(Request $request){
    	return redirect()->route('programas');
    }

    public function deletePeriodoEditar(Request $request){
    	return redirect()->route('programas');
    }

    /*Fin para Funciones para periodo*/

    /*Funciones para plan*/

    public function getPlanCrear(){
    	return view('programas/plancrear',['nivel'=>Niveles::all()]);
    }

    private function respuestaEstandar($nivel_seleccionado,$materiainnivel_seleccionado){
        return view('programas/plancrear',[
                'nivel'=>Niveles::all(),
                'nivel_seleccionado'=>$nivel_seleccionado,
                'nivel_respuesta'=>Niveles::find($nivel_seleccionado),
                'materia'=>Materias::all(),
                'materiaInNivel'=>MateriasHasNiveles::where('niveles_id','=',$nivel_seleccionado)->get(),
                'periodo'=>Periodos::all(),
                'materiainnivel_seleccionado'=>$materiainnivel_seleccionado,
                'periodoinmateria'=>NivelesHasPeriodos::where('materias_has_niveles_id','=',$materiainnivel_seleccionado)->get()
                ]);
    }

    public function postPlanCrear(Request $request){
        if($request->has('nivel_id')){
            return $this->respuestaEstandar($request->input('nivel_id'),NULL);
        }
        if($request->has('materia_id')){
            $materiaennivel=new MateriasHasNiveles;
            $materiaennivel->niveles_id=$request->input('nivel_seleccionado');
            $materiaennivel->materias_id=$request->input('materia_id');
            $materiaennivel->save();
            return $this->respuestaEstandar($request->input('nivel_seleccionado'),NULL);
        }
        if($request->has('materiainnivelerase_id')){
            $materiaennivel=MateriasHasNiveles::find($request->input('materiainnivelerase_id'));
            $materiaennivel->delete();
            $periodoinmateria=NivelesHasPeriodos::where('materias_has_niveles_id','=',$request->input('materiainnivelerase_id'))->delete();
            $noOrphan=new NoOrphanRegisters;
            $noOrphan->getLimpiarHuerfanos();
            return $this->respuestaEstandar($request->input('nivel_seleccionado'),NULL);
        }
        if($request->has('materiainnivel_id')){
            $periodoinmateria=new NivelesHasPeriodos;
            $periodoinmateria->periodos_id=$request->input('periodo_id');
            $periodoinmateria->materias_has_niveles_id=$request->input('materiainnivel_id');
            $periodoinmateria->save();
            return $this->respuestaEstandar($request->input('nivel_seleccionado'),NULL);
        }
        if($request->has('materiainnivel_seleccionado')){
            return $this->respuestaEstandar($request->input('nivel_seleccionado'),$request->input('materiainnivel_seleccionado'));
        }
        if($request->has('periodoeliminar_id')){
            $periodoinmateria=NivelesHasPeriodos::find($request->input('periodoeliminar_id'));
            $periodoinmateria->delete();
            $noOrphan=new NoOrphanRegisters;
            $noOrphan->getLimpiarHuerfanos();
            return $this->respuestaEstandar($request->input('nivel_seleccionado'),NULL);
        }
    	return redirect()->route('crear_plan')->withFlashMessage('No se puede realizar acción sin la información suficiente');
    }

    public function getPlanEditar(){
    	return view('programas/planeditar');
    }

    public function putPlanEditar(Request $request){
    	return redirect()->route('programas');
    }

    public function deletePlanEditar(Request $request){
    	return redirect()->route('programas');
    }

    /*Fin para Funciones para plan*/

}
