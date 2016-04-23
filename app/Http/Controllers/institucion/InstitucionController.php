<?php

namespace App\Http\Controllers\institucion;

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

class InstitucionController extends Controller
{
    
    public function index(){
    	return view('institucion/institucion');
    }//Probado y funcionando


    /*Funciones para empleado*/

    public function getEmpleadoCrear(){
        return view('institucion/empleadocrear',[
            'principal'=>User::where('tipo_usuario_id','<>',2)->get(), 
            'actuales'=>$this->ultimosEmpleados()]);
    }//Probado y funcionando

    private function ultimosEmpleados(){
        return Empleados::orderBy('updated_at','DESC')->take(10)->get();
    }//Probado y funcionando

    private function busquedaemp(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Empleados::select('empleados.*','users.name','users.lastname','users.identificacion')
                ->join('users','empleados.users_id','=','users.id')
                ->where('eps','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('arl','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('pension','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.name','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.identificacion','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.lastname','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            return view('institucion/empleadocrear',['respuesta'=>1,
                'result'=>$result,'principal'=>User::all(),'actuales'=>$this->ultimosEmpleados()]);
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearemp(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'user_id' => 'required|unique:empleados,users_id'
        ]);
        if(!$validacion->fails()){
            $empleado=new Empleados;
            $empleado->users_id=$request->input('user_id');
            $empleado->salario=$request->input('salario');
            $empleado->eps=$request->input('eps');
            $empleado->eps_val=$request->input('eps_val');
            $empleado->arl=$request->input('arl');
            $empleado->arl_val=$request->input('arl_val');
            $empleado->pension=$request->input('pension');
            $empleado->pension_val=$request->input('pension_val');
            $empleado->contrato_ini=$request->input('contrato_ini');
            $empleado->contrato_fin=$request->input('contrato_fin');
            $empleado->save();
            $resultado='Registro creado exitosamente';
        }
        return back()->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando

    public function postEmpleadoCrear(Request $request){
        if($request->has('busqueda')){
            return $this->busquedaemp($request);
        }else{
            return $this->crearemp($request);
        }
    }//Llega post de crear y de buscar //Probado y funcionando

    private function modificaremp(Request $request){
        $empleado=Empleados::find($request->input('id'));
        $resultado='No se ha modificado el registro. Vuelva a intentarlo';
        $validacion=Validator::make($request->all(),[
            'salario' => 'required',
            'eps' => 'required',
            'eps_val' => 'required',
            'arl' => 'required',
            'arl_val' => 'required',
            'pension' => 'required',
            'pension_val' => 'required'
        ]);
        if(!$validacion->fails()){
            $empleado->users_id=$request->input('users_id');
            $empleado->salario=$request->input('salario');
            $empleado->eps=$request->input('eps');
            $empleado->eps_val=$request->input('eps_val');
            $empleado->arl=$request->input('arl');
            $empleado->arl_val=$request->input('arl_val');
            $empleado->pension=$request->input('pension');
            $empleado->pension_val=$request->input('pension_val');
            $empleado->contrato_ini=$request->input('contrato_ini');
            $empleado->contrato_fin=$request->input('contrato_fin');
            $empleado->save();
            $resultado='Registro modificado';
        }
        return redirect()->route('crear_empleado')->withFlashMessage($resultado)->withErrors($validacion);
    }//Probado y funcionando

    private function eliminaremp(Request $request){
        $noOrphan=new NoOrphanRegisters;
        $empleado=Empleados::find($request->input('id'));
        $empleado->delete();
        return redirect()->route('crear_empleado')->withFlashMessage('Empleado eliminado. '.$noOrphan->getLimpiarHuerfanos());
    }//Probado y funcionando

    public function getEmpleadoEditar(Request $request){
        if($request->has('modificar')){
            return $this->modificaremp($request);
        }
        if($request->has('eliminar')){
            return $this->eliminaremp($request);
        }
        if($request->has('accion')){
            $result=Empleados::find($request->input('id'));
            return view('institucion/empleadoeditar',['accion'=>$request->input('accion'),'result'=>$result]);
        }
        return redirect()->route('crear_empleado');
    }//Probado y funcionando

    public function putEmpleadoEditar(Request $request){
        return redirect()->route('institucion');
    }

    public function deleteEmpleadoEditar(Request $request){
        return redirect()->route('institucion');
    }

    /*Fin para Funciones para empleado*/

    /*Funciones para pension*/

    public function getPensionCrear(){
    	return view('institucion/pensioncrear');
    }

    private function busquedapen(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion','niveles.nombre_nivel','niveles.descripcion')
                ->join('users','alumnos.users_id','=','users.id')
                ->join('niveles','alumnos.niveles_id','=','niveles.id')
                ->where('users.name','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.lastname','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.identificacion','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('niveles.nombre_nivel','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('niveles.descripcion','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            if ($result->count()>0) {
                return view('institucion/pensioncrear',['respuesta'=>1,'result'=>$result]);
            }
            return back()->withFlashMessage('No se encontraron registros');
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearpen(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'valor' => 'required',
            'numero_factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
        ]);
        if(!$validacion->fails()){
            $pago=new PagoPension;
            $pago->numero_factura=$request->input('numero_factura');
            $pago->alumnos_id=$request->input('alumnos_id');
            $pago->descripcion=$request->input('descripcion');
            $pago->valor=$request->input('valor');
            $pago->mes_id=$request->input('mes_id');
            $pago->faltante=$request->input('faltante');
            if ($request->input('faltante')<=0) {
                $pago->cancelado_at=Carbon::now();
            }
            $pago->save();
            $resultado='Pago creado exitosamente';
        }else{
            foreach ($validacion->errors() as $key) {
               $resultado.=', '.$key;
            }
        }
        return $this->pantallaPagoAlumno($request->input('alumnos_id'),'pension',$resultado);
    }//Probado y funcionando

    public function postPensionCrear(Request $request){
        if($request->has('modificar')){
            return $this->modPension($request);
        }
        if($request->has('eliminar')){
            return $this->elimPension($request);
        }
    	if($request->has('busqueda')){
            return $this->busquedapen($request);
        }else{
            return $this->crearpen($request);
        }
    }

    private function modPension(Request $request){
        $resultado='No se ha creado el registro';
        $tempo=PagoPension::find($request->input('id'));
        $mismaFactura=false;
        $datoval=null;
        if ($request->input('numero_factura')==$tempo->numero_factura) {
            $mismaFactura=true;
        }
        if ($mismaFactura) {
            $datoval=[
                'valor' => 'required'
            ];
        }else{
            $datoval=[
                'valor' => 'required',
                'numero_factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
            ];
        }
        $validacion=Validator::make($request->all(),$datoval);
        if(!$validacion->fails()){
            $pago=PagoPension::find($request->input('id'));
            if (!$mismaFactura) {
                $pago->numero_factura=$request->input('numero_factura');
            }
            $pago->alumnos_id=$request->input('alumnos_id');
            $pago->descripcion=$request->input('descripcion');
            $pago->valor=$request->input('valor');
            $pago->mes_id=$request->input('mes_id');
            $pago->faltante=$request->input('faltante');
            if ($request->input('faltante')<=0) {
                $pago->cancelado_at=Carbon::now();
            }
            $pago->save();
            $resultado='Pago modificado exitosamente';
        }
        return $this->pantallaPagoAlumno($request->input('alumnos_id'),'pension',$resultado);
    }

    private function elimPension(Request $request){
        $objeto=PagoPension::find($request->id);
        $usuario=$objeto->alumnos_id;
        $factura=$objeto->numero_factura;
        $objeto->delete();
        return $this->pantallaPagoAlumno($usuario,'pension','Pensión de la factura No.'.$factura.' eliminada');
    }

    public function pantallaPagoAlumno($id,$area,$mensajeInfo=null){
        $result=null;
        switch ($area) {
            case 'pension':
                $result=PagoPension::with('meses')->where('alumnos_id','=',$id)->get();
                break;
            case 'matricula':
                $result=PagoMatricula::where('alumnos_id','=',$id)->get();
                break;
            default:
                return 'Error en pantallaPagoAlumno';
                break;
        }
        return view('institucion/'.$area.'editar',[
            'alumno'=>Alumnos::find($id),
            'result'=>$result,
            'meses'=>Meses::all(),
            'mensajeResultado'=>$mensajeInfo
        ]);
    }

    public function getPensionEditar(Request $request){
        if ($request->has('accionPension')) {
            switch ($request->input('accionPension')) {
                case '1':
                    return $this->postPensionPrint($request);
                    break;
                case '2':
                    return $this->modelimPension($request);
                    break;
                case '3':
                    return $this->modelimPension($request);
                    break;
                default:
                    # code...
                    break;
            }
        }
    	return $this->pantallaPagoAlumno($request->input('id'),'pension');
    }

    public function getPensionEditarId($alumnos_id){
        return $this->pantallaPagoAlumno($alumnos_id,'pension');
    }

    public function modelimPension(Request $request){
        if ($request->has('modificar')) {
            return modPension($request);
        }
        if ($request->has('eliminar')) {
            return $this->elimPension($request);
        }
        return view('institucion/pensionactualizar',[
            'result'=>PagoPension::with('alumnos','alumnos.users')->where('id','=',$request->input('id'))->first(),
            'meses'=>Meses::all(),
            'accionpension'=>$request->input('accionPension')
        ]);
    }

    public function imprimePension($id){
        return $this->imprimePensionId($id);
    }

    public function postPensionPrint(Request $request,$id=null){
        return $this->imprimePensionId($request->input('id'));
    }

    private function imprimePensionId($selector){
        $result=PagoPension::with('alumnos','meses')->where('id','=',$selector)->first();
        $valor_pen=$result->valor;
        $mes_pen=$result->mes_id;
        return view('institucion/facturapensionmatricula',['tipo_factura'=>'pensión','valor_pen'=>$valor_pen,'mes_pen'=>$mes_pen,'result'=>$result]);
    
    }
    public function getPensionActual($alumnos_id){
    	return redirect()->route('institucion');
    }

    public function deletePensionEditar(Request $request){
    	return redirect()->route('institucion');
    }

    public function getFacturaPension($factura){
        $objeto=PagoPension::where('numero_factura','=',$factura)->first();
        return $objeto->toJson();
    }

    /*Fin para Funciones para pension*/

    /*Funciones para matricula*/

    public function getMatriculaCrear(){
    	return view('institucion/matriculacrear');
    }

    private function busquedamat(Request $request){
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:3|max:20'
        ]);
        if(!$validacion->fails()){
            $result=Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion','niveles.nombre_nivel','niveles.descripcion')
                ->join('users','alumnos.users_id','=','users.id')
                ->join('niveles','alumnos.niveles_id','=','niveles.id')
                ->where('users.name','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.lastname','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('users.identificacion','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('niveles.nombre_nivel','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('niveles.descripcion','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            if ($result->count()>0) {
                return view('institucion/matriculacrear',['respuesta'=>1,'result'=>$result]);
            }
            return back()->withFlashMessage('No se encontraron registros');
        }
        return back()->withErrors($validacion);
    }//Probado y funcionando

    private function crearmat(Request $request){
        $resultado='No se ha creado el registro';
        $validacion=Validator::make($request->all(),[
            'valor' => 'required',
            'numero_factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
        ]);
        if(!$validacion->fails()){
            $pago=new PagoMatricula;
            $pago->numero_factura=$request->input('numero_factura');
            $pago->alumnos_id=$request->input('alumnos_id');
            $pago->descripcion=$request->input('descripcion');
            $pago->valor=$request->input('valor');
            $pago->faltante=$request->input('faltante');
            if ($request->input('faltante')<=0) {
                $pago->cancelado_at=Carbon::now();
            }
            $pago->save();
            $resultado='Pago creado exitosamente';
        }
        return $this->pantallaPagoAlumno($request->input('alumnos_id'),'matricula',$resultado);
    }//Probado y funcionando

    public function postMatriculaCrear(Request $request){
    	if($request->has('modificar')){
            return $this->modMatricula($request);
        }
        if($request->has('eliminar')){
            return $this->elimMatricula($request);
        }
        if($request->has('busqueda')){
            return $this->busquedamat($request);
        }else{
            return $this->crearmat($request);
        }
    }

    private function modMatricula(Request $request){
        $resultado='No se ha creado el registro';
        $tempo=PagoMatricula::find($request->input('id'));
        $mismaFactura=false;
        $datoval=null;
        if ($request->input('numero_factura')==$tempo->numero_factura) {
            $mismaFactura=true;
        }
        if ($mismaFactura) {
            $datoval=[
                'valor' => 'required'
            ];
        }else{
            $datoval=[
                'valor' => 'required',
                'numero_factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
            ];
        }
        $validacion=Validator::make($request->all(),$datoval);
        if(!$validacion->fails()){
            $pago=PagoMatricula::find($request->input('id'));
            if (!$mismaFactura) {
                $pago->numero_factura=$request->input('numero_factura');
            }
            $pago->alumnos_id=$request->input('alumnos_id');
            $pago->descripcion=$request->input('descripcion');
            $pago->valor=$request->input('valor');
            $pago->faltante=$request->input('faltante');
            if ($request->input('faltante')<=0) {
                $pago->cancelado_at=Carbon::now();
            }
            $pago->save();
            $resultado='Pago modificado exitosamente';
        }
        return $this->pantallaPagoAlumno($request->input('alumnos_id'),'matricula',$resultado);
    }

    private function elimMatricula(Request $request){
        $objeto=PagoMatricula::find($request->id);
        $usuario=$objeto->alumnos_id;
        $factura=$objeto->numero_factura;
        $objeto->delete();
        return $this->pantallaPagoAlumno($usuario,'matricula','Matrícula de la factura No.'.$factura.' eliminada');
    }

    public function getMatriculaEditar(Request $request){
    	if ($request->has('accionMatricula')) {
            switch ($request->input('accionMatricula')) {
                case '1':
                    return $this->postMatriculaPrint($request);
                    break;
                case '2':
                    return $this->modelimMatricula($request);
                    break;
                case '3':
                    return $this->modelimMatricula($request);
                    break;
                default:
                    # code...
                    break;
            }
        }        //Acá se envían los valores
        return view('institucion/matriculaeditar',[
            'alumno'=>Alumnos::find($request->input('id')),
            'result'=>PagoMatricula::where('alumnos_id','=',$request->input('id'))->get(),
            'meses'=>Meses::all()
        ]);
    }

    public function getMatriculaEditarId($alumnos_id){
        return view('institucion/matriculaeditar',[
            'alumno'=>Alumnos::find($alumnos_id),
            'result'=>PagoMatricula::where('alumnos_id','=',$alumnos_id)->get(),
            'meses'=>Meses::all()
        ]);
    }

    public function modelimMatricula(Request $request){
        if ($request->has('modificar')) {
            return modMatricula($request);
        }
        if ($request->has('eliminar')) {
            return $this->elimMatricula($request);
        }
               //otro lugar de interes
        return view('institucion/matriculaactualizar',[
            'result'=>PagoMatricula::with('alumnos','alumnos.users')->where('id','=',$request->input('id'))->first(),
            'meses'=>Meses::all(),
            'accionmatricula'=>$request->input('accionMatricula')
        ]);
    }

    public function imprimeMatricula($id){
        return $this->imprimeMatriculaId($id);
    }

    public function postMatriculaPrint(Request $request,$id=null){
        return $this->imprimeMatriculaId($request->input('id'));
    }

    private function imprimeMatriculaId($selector){
        $result=PagoMatricula::with('alumnos')->where('id','=',$selector)->first();
        $valor_mat=$result->valor;
        return view('institucion/facturapensionmatricula',['tipo_factura'=>'matrícula','valor_matri'=>$valor_mat,'result'=>$result]);
    
    }

    public function putMatriculaEditar(Request $request){
    	return redirect()->route('institucion');
    }

    public function deleteMatriculaEditar(Request $request){
    	return redirect()->route('institucion');
    }

    public function getFacturaMatricula($factura){
        $objeto=PagoMatricula::where('numero_factura','=',$factura)->first();
        return $objeto->toJson();
    }

    /*Fin para Funciones para matricula*/

    /*Funciones para otros pagos*/

    public function imprimeOpagosId($id){
        $result=PagoOtros::with('alumnos')->where('id','=',$id)->first();
        $valor_opa=$result->valor;
        return view('institucion/facturapensionmatricula',['tipo_factura'=>'otros pagos','valor_opa'=>$valor_opa,'result'=>$result]);
    }

    public function getFacturaOpagos($factura){
        $objeto=PagoOtros::where('numero_factura','=',$factura)->first();
        return $objeto->toJson();
    }

    /*Fin para Funciones para otros pagos*/

    /*Funciones para nomina*/

    private function homeCrearNomina($calculado, $empl_sel, $sal_trab, $auxmovil, $fecha_ini, $fecha_fin, $dias, $mes){
        return view('institucion/nominacrear',[
            'principal'=>Empleados::all(),
            'empl_selec'=>Empleados::find($empl_sel),
            'sal_trab'=>$sal_trab,
            'calculado'=>$calculado,
            'auxmovil'=>$auxmovil,
            'porc_emp_eps'=>0.04,
            'porc_emp_pen'=>0.04,
            'meses'=>Meses::all(),
            'fecha_ini'=>$fecha_ini,
            'fecha_fin'=>$fecha_fin,
            'dias'=>$dias,
            'mes'=>Meses::find($mes)

        ]);
    }

    private function calcSalMes($salario, $dias){
        if($dias<=30){
            return $salario*$dias/30;
        }else{
            return false;
        }
    }

    public function getNominaCrear(){
    	return $this->homeCrearNomina(NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    }

    private function verAnioFecha($fechaA,$fechaB,$mes){
        if ($fechaA && $fechaB) {
            $fechaA=explode('-', $fechaA);
            $fechaB=explode('-', $fechaB);
            if (is_array($fechaA) && is_array($fechaB)) {
                if ($fechaA[1]==$mes) {
                    return $fechaA[0];
                }elseif ($fechaB[1]==$mes) {
                    return $fechaB[0];
                }
            }
        }
        return false;

    }

    public function postNominaCrear(Request $request){
        if ($request->has('anio') || $request->has('accionNomina')) {
            return $this->getNominaEditar($request);
        }
    	if(!$request->input('calculado')){
            $anioVerif=$this->verAnioFecha($request->input('fecha_ini'),$request->input('fecha_fin'),$request->input('mes_id'));
            if (is_null($anioVerif) || $anioVerif==false) {
                return back()->withFlashMessage('El rango de fechas no corresponde al mes seleccionado');
            }
            if ($request->dias<1 || $request->dias>30) {
                return back()->withFlashMessage('El numero de dias es errado. introduzca de 1 a 30 dias');
            }
            $empleado=Empleados::find($request->input('empleados_id'));
            $salario_trabajado=$this->calcSalMes($empleado->salario, $request->input('dias'));
            return $this->homeCrearNomina(
                1, 
                $empleado->id, 
                $salario_trabajado,
                $this->calcSalMes(74000,$request->input('dias')),//Auxilio de transporte
                $request->input('fecha_ini'),
                $request->input('fecha_fin'),
                $request->input('dias'),
                $request->input('mes_id')
            );

        }else{
            $resultado='No se ha creado el registro';
            $anioVerif=$this->verAnioFecha($request->input('fecha_ini'),$request->input('fecha_fin'),$request->input('mes_id'));

            if ($anioVerif<1000 || is_null($anioVerif) || $anioVerif==false) {
                return back()->withFlashMessage($resultado.'. El rango de fechas no corresponde al mes');
            }
            $validacion=Validator::make($request->all(),[
                'fecha_ini' => 'required',
                'fecha_fin' => 'required',
                'dias' => 'required',
                'mes_id' => 'required',
                'empl_selec_id' => 'required',
                'salario_pagado' => 'required',
                'auxmovil' => 'required',
                'eps_empleado' => 'required',
                'eps_empresa' => 'required',
                'pension_empleado' => 'required',
                'pension_empresa' => 'required',
                'arl_empresa' => 'required'
            ]);
            if(!$validacion->fails()){
                    $pago=new PagoSalario;
                    $pago->fecha_ini=$request->input('fecha_ini');
                    $pago->fecha_fin=$request->fecha_fin;
                    $pago->anio=$this->verAnioFecha($request->input('fecha_ini'),$request->input('fecha_fin'),$request->input('mes_id'));
                    $pago->mes_id=$request->mes_id;
                    $pago->dias=$request->dias;
                    $pago->empleados_id=$request->empl_selec_id;
                    $pago->salario_pagado=$request->salario_pagado;
                    $pago->auxmovil=$request->auxmovil;
                    $pago->eps_empleado=$request->eps_empleado;
                    $pago->eps_empresa=$request->eps_empresa;
                    $pago->pension_empleado=$request->pension_empleado;
                    $pago->pension_empresa=$request->pension_empresa;
                    $pago->arl_empresa=$request->arl_empresa;
                    $pago->descuento=$request->descuento;
                    $pago->descripcion_desc=$request->descripcion_desc;
                    $pago->bonificacion=$request->bonificacion;
                    $pago->descripcion_boni=$request->descripcion_boni;
                    $pago->notas=$request->notas;
                    $pago->save();
                    $resultado='Se ha creado la nómina con éxito';
            }
            return back()->withFlashMessage($resultado)->withErrors($validacion);
        }
    }

    private function homeVerNomina($histNom,$mes_selec,$anio_selec){
        return view('institucion/nominacrear',[
            'principal'=>Empleados::all(),
            'histNom'=>$histNom,
            'meses'=>Meses::all(),
            'mes_selec'=>$mes_selec,
            'anio_selec'=>$anio_selec
             ]);
    }

    public function getNominaEditar(Request $request){
        if($request->has('anio')){
            $resultado='No se han buscado los registros';
            $validacion=Validator::make($request->all(),[
                'anio' => 'required'
            ]);
            if(!$validacion->fails()){
                $salario=PagoSalario::where('mes_id','=',$request->mes_id)
                    ->where('anio','=',$request->anio)
                    ->get();
                return $this->homeVerNomina($salario,$request->mes_id,$request->anio);
            }else{
                return back()->withFlashMessage($resultado)->withErrors($validacion);
            }
        }
        if ($request->has('accionNomina')) {
            if($request->accionNomina!=2){
                return view('institucion/nominaeditar',['result'=>PagoSalario::find($request->id),'accion'=>$request->accionNomina,'meses'=>Meses::all()]);
            }else{
                $pago=PagoSalario::find($request->id);
                $pago->pagado_at=Carbon::now();
                $pago->save();
                return back()->withFlashMessage('Pago registrado');
            }
        }
        if($request->eliminar){
            $pago=PagoSalario::find($request->id);
            $pago->delete();
            return redirect()->route('crear_nomina')->withFlashMessage('Registro de pago de nómina eliminado');
        }
        if ($request->salario_pagado) {
            $resultado='No se ha modificado el registro';
            $anioVerif=$this->verAnioFecha($request->input('fecha_ini'),$request->input('fecha_fin'),$request->input('mes_id'));

            if ($anioVerif<1000 || is_null($anioVerif) || $anioVerif==false) {
                return back()->withFlashMessage($resultado.'. El rango de fechas no corresponde al mes');
            }
            $validacion=Validator::make($request->all(),[
                'fecha_ini' => 'required',
                'fecha_fin' => 'required',
                'dias' => 'required',
                'mes_id' => 'required',
                'empl_selec_id' => 'required',
                'salario_pagado' => 'required',
                'auxmovil' => 'required',
                'eps_empleado' => 'required',
                'eps_empresa' => 'required',
                'pension_empleado' => 'required',
                'pension_empresa' => 'required',
                'arl_empresa' => 'required'
            ]);
            if(!$validacion->fails()){
                    $pago=PagoSalario::find($request->input('id'));
                    $pago->fecha_ini=$request->input('fecha_ini');
                    $pago->fecha_fin=$request->fecha_fin;
                    $pago->anio=$this->verAnioFecha($request->input('fecha_ini'),$request->input('fecha_fin'),$request->input('mes_id'));
                    $pago->mes_id=$request->mes_id;
                    $pago->dias=$request->dias;
                    $pago->empleados_id=$request->empl_selec_id;
                    $pago->salario_pagado=$request->salario_pagado;
                    $pago->auxmovil=$request->auxmovil;
                    $pago->eps_empleado=$request->eps_empleado;
                    $pago->eps_empresa=$request->eps_empresa;
                    $pago->pension_empleado=$request->pension_empleado;
                    $pago->pension_empresa=$request->pension_empresa;
                    $pago->arl_empresa=$request->arl_empresa;
                    $pago->descuento=$request->descuento;
                    $pago->descripcion_desc=$request->descripcion_desc;
                    $pago->bonificacion=$request->bonificacion;
                    $pago->descripcion_boni=$request->descripcion_boni;
                    $pago->notas=$request->notas;
                    $pago->save();
                    $resultado='Se ha modificado la nómina con éxito';
                }
            return back()->withFlashMessage($resultado);
        }

    	return back()->withFlashMessage('Error, no se actualiza nada');
    }

    public function exportarNomina(Request $request){
        $salario=PagoSalario::where('mes_id','=',$request->mes_id)
            ->where('anio','=',$request->anio)
            ->orderBy('created_at','DESC')
            ->get();
        if($salario->count()>0){
            Excel::create($request->anio.'-'.$request->mes_id, function($excel) use ($request, $salario){
                $excel->sheet('Nómina de '.Meses::find($request->mes_id)->nombre_mes.' de '.$request->anio, function($sheet) use ($request, $salario){ 
                    $sheet->mergeCells('A1:O1');
                    $sheet->mergeCells('A2:O2');
                    $sheet->mergeCells('A3:O3');
                    $sheet->mergeCells('A4:A5');
                    $sheet->mergeCells('B4:B5');
                    $sheet->mergeCells('C4:C5');
                    $sheet->mergeCells('D4:D5');
                    $sheet->mergeCells('E4:E5');
                    $sheet->mergeCells('F4:I4');
                    $sheet->mergeCells('J4:M4');
                    $sheet->mergeCells('N4:N5');
                    $sheet->mergeCells('O4:O5');
                    $sheet->cells('A4:E5', function($cells){
                        $cells->setBackground('#B2FFFF');
                    });
                    $sheet->cells('F5:M5', function($cells){
                        $cells->setBackground('#6576CD');
                    });
                    $sheet->cells('F4:I4', function($cells){
                        $cells->setBackground('#B2FFFF');
                    });
                    $sheet->cells('J4:M4', function($cells){
                        $cells->setBackground('#A9CD65');
                    });
                    $sheet->cells('N4:O5', function($cells){
                        $cells->setBackground('#B2FFFF');
                    });
                    $sheet->setBorder('A4:O'.$salario->count(),'thin'+6);
                    $sheet->row(1,['NUEVO COLEGIO LUSADI LTDA']);
                    $sheet->row(2,['NOMINA DE SUELDOS DE '.Meses::find($request->mes_id)->nombre_mes.' DE '.$request->anio]);
                    $sheet->row(3,['NIT. 900.185.143-3']);
                    $sheet->row(4,['IDENTIFICACION','APELLIDOS','NOMBRES','SALARIO','DIAS','DEVENGADOS','','','','DEDUCCIONES','','','','NETO A PAGAR','FIRMA']);
                    $sheet->row(5,['','','','','','SAL. DEVENGADO', 'SUB/TRANS', 'BONIFICACIONES', 'TOTAL DEVENGADO', 'SALUD 4%', 'PENSION 4%', 'DESCUENTOS', 'TOTAL DEDUCCION']);
                    $contador=1;
                    foreach ($salario as $value) {
                        $sheet->row(5+$contador,[
                            $value->empleados->users->identificacion,
                            $value->empleados->users->lastname,
                            $value->empleados->users->name,
                            $value->empleados->salario,
                            $value->dias,
                            $value->salario_pagado,
                            $value->auxmovil,
                            $value->bonificacion,
                            $value->salario_pagado+$value->auxmovil+$value->bonificacion,
                            $value->eps_empleado,
                            $value->pension_empleado,
                            $value->descuento,
                            $value->eps_empleado+$value->pension_empleado+$value->descuento,
                            $value->salario_pagado+$value->auxmovil+$value->bonificacion-($value->eps_empleado+$value->pension_empleado+$value->descuento)
                        ]);
                        $contador++;
                    }
                    $sheet->cells('A1:O'.$salario->count(),'thin'+6, function($cells){
                        $cells->setAlignment('center');
                        $cells->setValignment('middle');
                    });
                });
            })->download('xlsx');
        }
        return redirect()->route('crear_nomina')->withFlashMessage('No hay resultados para exportar');
    }

    public function putNominaEditar(Request $request){
    	return redirect()->route('institucion');
    }

    public function deleteNominaEditar(Request $request){
    	return redirect()->route('institucion');
    }

    /*Fin para Funciones para nomina*/

    /*Funciones para estado*/

    public function getEstadoCrear(){
    	return view('institucion/estadocrear');
    }

    public function postEstadoCrear(Request $request){
    	return redirect()->route('institucion');
    }

    public function getEstadoEditar(){
    	return view('institucion/estadoeditar');
    }

    public function putEstadoEditar(Request $request){
    	return redirect()->route('institucion');
    }

    public function deleteEstadoEditar(Request $request){
    	return redirect()->route('institucion');
    }

    /*Fin para Funciones para estado*/
    public function gestionPagos(){
        return view('institucion/gestionpagos');
    }

    /*Funciones de REST para pensiones*/
    public function indexPensiones(){       //Listar todos
        $pensiones=PagoPension::with(['alumnos'=>function($query){
            $query->with(['users']);
        }])
            ->orderBy('cancelado_at','desc')->get();
        return $pensiones->toJson();
    }

    public function pensionPorDia(Request $request){
        $fecha=substr($request->input('fecha'), 0, 10);
        $objeto=PagoPension::with(['alumnos'=>function($query){
            $query->with(['users']);
        }])->where('created_at','LIKE','%'.$fecha.'%')->orderBy('numero_factura','asc')->get();
        return $objeto->toJson();
    }

    public function guardarPensiones(Request $request){     //Almacenar de BD
        $resultado='No se ha creado el registro. La factura existe o no introdujo el mes.';
        $estado=false;
        if ($request->has('alumnos_id')) {
                $validacion=Validator::make($request->all(),[
                    'valor' => 'required',
                    'mes' => 'required',
                    'factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
                ]);
                if(!$validacion->fails()){
                    $pago=new PagoPension;
                    $pago->numero_factura=$request->input('factura');
                    $pago->alumnos_id=$request->input('alumnos_id');
                    if (is_null($request->input('notas'))) {
                        $pago->descripcion=" ";
                    }else{
                        $pago->descripcion=$request->input('notas');
                    }
                    $pago->valor=$request->input('valor');
                    $pago->mes_id=$request->input('mes');
                    $pago->faltante=$request->input('restante');
                    if ($request->input('restante')<=0) {
                        $pago->cancelado_at=Carbon::now();
                    }
                    $pago->save();
                    $resultado='Pago de pension creado exitosamente.';
                    $estado=true;
                }
        }
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

    public function encontrarPensiones($alumnos_id){
        $objeto=PagoPension::with(['alumnos'=>function($query){
            $query->with(['niveles']);
        }])->where('alumnos_id','=',$alumnos_id)->orderBy('cancelado_at','desc')->get();
        return $objeto->toJson();
    }

    /*Funciones de REST para matriculas*/
    public function indexMatriculas(){       //Listar todos
        $matricula=PagoMatricula::with(['alumnos'=>function($query){
            $query->with(['users']);
        }])
            ->orderBy('cancelado_at','desc')->get();
        return $matricula->toJson();
    }

    public function matriculaPorDia(Request $request){
        $fecha=substr($request->input('fecha'), 0, 10);
        $objeto=PagoMatricula::with(['alumnos'=>function($query){
            $query->with(['users']);
        }])->where('created_at','LIKE','%'.$fecha.'%')->orderBy('numero_factura','asc')->get();
        return $objeto->toJson();
    }

    private function existeFactura($factura){
        $resultado=false;
        $objeto1=PagoPension::where('numero_factura','=',$factura)->get();
        $objeto2=PagoMatricula::where('numero_factura','=',$factura)->get();
        $objeto3=PagoMatricula::where('numero_factura','=',$factura)->get();
        if ($objeto1->count()>0 || $objeto2->count()>0 || $objeto3->count()>0) {
            return true;
        }
        return $resultado;
    }
    
    public function guardarMatriculas(Request $request){      //Almacenar de BD
        $resultado='No se ha creado el registro. La factura existe.';
        $estado=false;
        if ($request->has('alumnos_id')) {
                $validacion=Validator::make($request->all(),[
                    'valor' => 'required',
                    'factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
                ]);
                if(!$validacion->fails()){
                    if (!$this->existeFactura($request->input('factura'))) {
                    $pago=new PagoMatricula;
                    $pago->numero_factura=$request->input('factura');
                    $pago->alumnos_id=$request->input('alumnos_id');
                    if (is_null($request->input('notas'))) {
                        $pago->descripcion=" ";
                    }else{
                        $pago->descripcion=$request->input('notas');
                    }
                    $pago->valor=$request->input('valor');
                    $pago->faltante=$request->input('restante');
                    if ($request->input('restante')<=0) {
                        $pago->cancelado_at=Carbon::now();
                    }
                    $pago->save();
                    $resultado='Pago de matrícula creado exitosamente.';
                    $estado=true;
                    }
                }
        }
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }

    public function encontrarMatriculas($alumnos_id){
        $objeto=PagoMatricula::with(['alumnos'=>function($query){
            $query->with(['niveles']);
        }])->where('alumnos_id','=',$alumnos_id)->orderBy('cancelado_at','desc')->get();
        return $objeto->toJson();
    }

    /*Funciones de REST para Otros pagos*/
    public function indexOtros(){       //Listar todos
        $otros=PagoOtros::with(['alumnos'=>function($query){
            $query->with(['users']);
        }])
            ->orderBy('cancelado_at','desc')->get();
        return $otros->toJson();
    }

    public function otrospPorDia(Request $request){
        $fecha=substr($request->input('fecha'), 0, 10);
        $objeto=PagoOtros::with(['alumnos'=>function($query){
            $query->with(['users']);
        }])->where('created_at','LIKE','%'.$fecha.'%')->orderBy('numero_factura','asc')->get();
        return $objeto->toJson();
    }

    public function guardarOtros(Request $request){           //Almacenar de BD
        $resultado='No se ha creado el registro. La factura existe.';
        $estado=false;
        if ($request->has('alumnos_id')) {
                $validacion=Validator::make($request->all(),[
                    'valor' => 'required',
                    'factura'=>'required|unique:pago_pension,numero_factura|unique:pago_matricula,numero_factura'
                ]);
                if(!$validacion->fails()){
                    $pago=new PagoOtros;
                    $pago->numero_factura=$request->input('factura');
                    $pago->alumnos_id=$request->input('alumnos_id');
                    if (is_null($request->input('notas'))) {
                        $pago->descripcion=" ";
                    }else{
                        $pago->descripcion=$request->input('notas');
                    }
                    $pago->valor=$request->input('valor');
                    $pago->cancelado_at=Carbon::now();
                    $pago->save();
                    $resultado='Otro pago creado exitosamente.';
                    $estado=true;
                }
        }
        return response()->json(['mensaje' => $resultado,"estado"=>$estado]);
    }
    public function borrarOtros($id){
        $estado=false;
        $mensaje="No se ha borrado el registro.";
        $objeto=PagoOtros::find($id);
        if(is_object($objeto)){
            $objeto->delete();
            $mensaje="Se ha borrado el registro.";
            $estado=true;
        }
        return response()->json(['mensaje' => $mensaje,"estado"=>$estado]);
    }

    public function encontrarOtros($alumnos_id){
        $objeto=PagoOtros::with(['alumnos'=>function($query){
            $query->with(['niveles']);
        }])->where('alumnos_id','=',$alumnos_id)->orderBy('cancelado_at','desc')->get();
        return $objeto->toJson();
    }

    /*Funciones de REST para meses*/
    public function indexMeses(){
        $meses=Meses::all();
        return $meses->toJson();
    }

    public function buscarAlumnos($buscado){
        $result=Alumnos::select('alumnos.*','users.name','users.lastname','users.identificacion',
                'niveles.nombre_nivel','niveles.descripcion')->join('users','alumnos.users_id','=','users.id')
                ->join('niveles','alumnos.niveles_id','=','niveles.id')
                ->where('niveles.nombre_nivel','LIKE','%'.$buscado.'%')
                ->orWhere('niveles.descripcion','LIKE','%'.$buscado.'%')
                ->orWhere('users.name','LIKE','%'.$buscado.'%')
                ->orWhere('users.identificacion','LIKE','%'.$buscado.'%')
                ->orWhere('users.lastname','LIKE','%'.$buscado.'%')
                ->orderBy('users.name','ASC')->get();
        return $result->toJson();
    }

    public function tirillaCaja($fecha,$tPension,$tMatri,$tOtros,$tTotal){
        return view('institucion/tirillacaja',[
            'fechaAhora'=>Carbon::now(),
            'tFecha'=>$fecha,
            'tMatri'=>$tMatri,
            'tPension'=>$tPension,
            'tOtro'=>$tOtros,
            'tTotal'=>$tTotal
        ]);
    }
}
