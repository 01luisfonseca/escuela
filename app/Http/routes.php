<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function(){
	return redirect()->route('autorizado/home');
});
Route::get('/home', function(){
	return redirect()->route('autorizado/home');
});

Route::get('/public',['as'=>'public']);

/*Rutas de logueo y deslogueo*/
Route::get('auth/login', ['as'=>'auth/login','uses'=>'Auth\AuthController@getLogin']);
Route::post('auth/login', ['as'=>'auth/login','uses'=>'logueo\LogueoController@postLogin']);
Route::get('auth/logout', ['as'=>'auth/logout','uses'=>'Auth\AuthController@getLogout']);

//Rutas de acceso restringido.
Route::group(['middleware'=>'auth'], function(){

	Route::get('/autorizado/home',['as'=>'autorizado/home', 'uses'=>'autorizado\AutorizadoHomeController@index']);
	Route::group(['prefix'=>'usuarios'],function (){
		Route::get('/',['as'=>'usuarios', 'uses'=>'usuarios\UsuariosController@index']);
		Route::get('/modificar',['as'=>'usuarios/modificar', 'uses'=>'usuarios\UsuariosController@getBuscar'])->middleware(['administrador', 'coordinador']);
		Route::post('/modificar',['as'=>'usuarios/modificar', 'uses'=>'usuarios\UsuariosController@postModificar']);
		Route::post('/modificapassword',['as'=>'usuarios/modificapassword', 'uses'=>'usuarios\UsuariosController@postModificaPassword']);
		Route::get('/crear',['as'=>'usuarios/crear', 'uses'=>'usuarios\UsuariosController@getNuevo'])->middleware(['administrador', 'coordinador']);
		Route::post('/registrar',['as'=>'usuarios/registrar', 'uses'=>'usuarios\UsuariosController@postRegistrar']);
		Route::post('/editar',['as'=>'usuarios/editar', 'uses'=>'usuarios\UsuariosController@gactualizar']);
		Route::get('/editar/{id}','usuarios\UsuariosController@editar');
		Route::post('/actualizar',['as'=>'usuarios/actualizar', 'uses'=>'usuarios\UsuariosController@pactualizar']);
		Route::post('/eliminar',['as'=>'usuarios/eliminar', 'uses'=>'usuarios\UsuariosController@eliminarUsuario']);
		Route::get('/carnet',['as'=>'usuarios/carnet', 'uses'=>'usuarios\UsuariosController@getCarnet']);
	});

	Route::group(['prefix'=>'programas'],function(){

		Route::group(['namespace'=>'programas'],function(){

			Route::get('/',['as'=>'programas','uses'=>'ProgramasController@index']);

			Route::group(['prefix'=>'/nivel'], function(){
				Route::get('/crear',['as'=>'crear_nivel','uses'=>'ProgramasController@getNivelCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_nivel','uses'=>'ProgramasController@postNivelCrear']);
				Route::post('/editar',['as'=>'editar_nivel','uses'=>'ProgramasController@getNivelEditar']);
				Route::put('/editar',['as'=>'editar_nivel','uses'=>'ProgramasController@putNivelEditar']);
				Route::delete('/editar',['as'=>'editar_nivel','uses'=>'ProgramasController@deleteNivelEditar']);
			});
			
			Route::group(['prefix'=>'/materia'], function(){
				Route::get('/crear',['as'=>'crear_materia','uses'=>'ProgramasController@getMateriaCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_materia','uses'=>'ProgramasController@postMateriaCrear']);
				Route::post('/editar',['as'=>'editar_materia','uses'=>'ProgramasController@getMateriaEditar']);
				Route::put('/editar',['as'=>'editar_materia','uses'=>'ProgramasController@putMateriaEditar']);
				Route::delete('/editar',['as'=>'editar_materia','uses'=>'ProgramasController@deleteMateriaEditar']);
			});

			Route::group(['prefix'=>'/periodo'], function(){
				Route::get('/crear',['as'=>'crear_periodo','uses'=>'ProgramasController@getPeriodoCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_periodo','uses'=>'ProgramasController@postPeriodoCrear']);
				Route::post('/editar',['as'=>'editar_periodo','uses'=>'ProgramasController@getPeriodoEditar']);
				Route::put('/editar',['as'=>'editar_periodo','uses'=>'ProgramasController@putPeriodoEditar']);
				Route::delete('/editar',['as'=>'editar_periodo','uses'=>'ProgramasController@deletePeriodoEditar']);
			});

			Route::group(['prefix'=>'/plan'], function(){
				Route::get('/crear',['as'=>'crear_plan','uses'=>'ProgramasController@getPlanCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_plan','uses'=>'ProgramasController@postPlanCrear']);
				Route::post('/editar',['as'=>'editar_plan','uses'=>'ProgramasController@getPlanEditar']);
				Route::put('/editar',['as'=>'editar_plan','uses'=>'ProgramasController@putPlanEditar']);
				Route::delete('/editar',['as'=>'editar_plan','uses'=>'ProgramasController@deletePlanEditar']);
			});
		});
		
	});
	Route::group(['prefix'=>'registro'],function(){

		Route::group(['namespace'=>'registro'],function(){

			Route::get('/',['as'=>'registro','uses'=>'RegistroController@index']);

			Route::group(['prefix'=>'/alumnos'], function(){
				Route::get('/crear',['as'=>'crear_alumno','uses'=>'RegistroController@getAlumnoCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_alumno','uses'=>'RegistroController@postAlumnoCrear']);
				Route::post('/editar',['as'=>'editar_alumno','uses'=>'RegistroController@getAlumnoEditar']);
				Route::get('/editar/{id}','RegistroController@editarAlumno');
				Route::get('/actualizar',['as'=>'getactualizar_alumno'])->middleware(['administrador', 'coordinador']);
				Route::get('/actualizar/{alumnos_id}','RegistroController@getAlumnoActual')->middleware(['administrador', 'coordinador']);
				Route::put('/editar',['as'=>'editar_alumno','uses'=>'RegistroController@putAlumnoEditar']);
				Route::delete('/editar',['as'=>'editar_alumno','uses'=>'RegistroController@deleteAlumnoEditar']);
			});
			
			Route::group(['prefix'=>'/profesores'], function(){
				Route::get('/crear',['as'=>'crear_profesor','uses'=>'RegistroController@getProfesorCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_profesor','uses'=>'RegistroController@postProfesorCrear']);
				Route::post('/editar',['as'=>'editar_profesor','uses'=>'RegistroController@getProfesorEditar']);
				Route::put('/editar',['as'=>'editar_profesor','uses'=>'RegistroController@putProfesorEditar']);
				Route::delete('/editar',['as'=>'editar_profesor','uses'=>'RegistroController@deleteProfesorEditar']);
			});

			Route::group(['prefix'=>'/asistencia'], function(){
				Route::get('/crear',['as'=>'crear_asistencia','uses'=>'RegistroController@getAsistenciaCrear'])->middleware(['administrador', 'coordinador','profesor']);
				Route::post('/crear',['as'=>'crear_asistencia','uses'=>'RegistroController@postAsistenciaCrear']);
				Route::post('/editar',['as'=>'editar_asistencia','uses'=>'RegistroController@getAsistenciaEditar']);
				Route::put('/editar',['as'=>'editar_asistencia','uses'=>'RegistroController@putAsistenciaEditar']);
				Route::delete('/editar',['as'=>'editar_asistencia','uses'=>'RegistroController@deleteAsistenciaEditar']);
			});

			Route::group(['prefix'=>'/rendimiento'], function(){
				Route::get('/crear',['as'=>'crear_rendimiento','uses'=>'RegistroController@getRendimientoCrear'])->middleware(['administrador', 'coordinador','profesor']);
				Route::post('/crear',['as'=>'crear_rendimiento','uses'=>'RegistroController@postRendimientoCrear']);
				Route::post('/editar',['as'=>'editar_rendimiento','uses'=>'RegistroController@getRendimientoEditar']);
				Route::put('/editar',['as'=>'editar_rendimiento','uses'=>'RegistroController@putRendimientoEditar']);
				Route::delete('/editar',['as'=>'editar_rendimiento','uses'=>'RegistroController@deleteRendimientoEditar']);
			});

			Route::group(['prefix'=>'/notas'], function(){
				Route::get('/crear',['as'=>'crear_rendimientorest','uses'=>'NotasController@index']);
				Route::get('/materias_asignadas/periodos/{id}','NotasController@getNivelesHasPeriodos');
				Route::get('/materias_asignadas/periodos/alumnos/{idNivelesHasPeriodos}','NotasController@getAlumnosConNotas');
				Route::get('/materias_asignadas','NotasController@getMateriasHasNiveles');
				Route::get('/materias_asignadas/periodos/notas/{id}','NotasController@getIndicadores');
				Route::get('/materias_asignadas/periodos/indicadores/{id}/delete','NotasController@borrarIndicador');
				Route::get('/materias_asignadas/periodos/indicadores/tipo_nota/{id}/delete','NotasController@delTipoNotas');
				Route::post('/materias_asignadas/periodos/indicadores/{nivelesInPeriodosId}','NotasController@setIndicadores');
				Route::post('/materias_asignadas/periodos/indicadores/{id}/actualizar','NotasController@actIndicadores');
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/{indicadoresId}','NotasController@setTipoNotas');
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/{tipoId}/actualizar','NotasController@actTipoNotas');
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/notas/{tipoNotaId}','NotasController@setNotas');
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/notas/{id}/{cal}/actualizar','NotasController@actNotas');
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/notas/{tipoNotaId}/basica','NotasController@setNotaBasica');

			});

			Route::group(['prefix'=>'/estudiantil'], function(){
				Route::get('/crear',['as'=>'crear_estudiantil','uses'=>'RegistroController@getEstudiantilCrear']);
				Route::post('/crear',['as'=>'crear_estudiantil','uses'=>'RegistroController@postEstudiantilCrear']);
				Route::post('/editar',['as'=>'editar_estudiantil','uses'=>'RegistroController@getEstudiantilEditar']);
				Route::put('/editar',['as'=>'editar_estudiantil','uses'=>'RegistroController@putEstudiantilEditar']);
				Route::delete('/editar',['as'=>'editar_estudiantil','uses'=>'RegistroController@deleteEstudiantilEditar']);
				Route::get('/obtenerniveles','RegistroController@getNivelesEstudiante');
				Route::get('/obteneractividad/{alumnos_id}','RegistroController@getActividad');
			});

			Route::group(['prefix'=>'boletin'], function(){
				Route::get('/',['as'=>'home_boletin','uses'=>'BoletinController@index']);
				Route::get('/niveles','BoletinController@getNiveles');
				Route::get('/alumnos/{niveles_id}','BoletinController@getAlumnos');
				Route::get('/periodos/{niveles_id}','BoletinController@getPeriodos');
				Route::get('/{alumnos_id}/{periodos_id}','BoletinController@getBoletin');
			});

			Route::group(['prefix'=>'analisis'],function(){
				Route::group(['prefix'=>'notas'],function(){
					Route::get('/',['as'=>'home_annotas','uses'=>'AnalisisNotasCtrl@getAnNotas']);
					Route::get('/excel/{anio}','AnalisisNotasCtrl@descargaExcel');
				});
			});

		});
	});

	Route::group(['prefix'=>'listados'],function(){

		Route::group(['namespace'=>'listados'],function(){

			Route::get('/',['as'=>'listados','uses'=>'listadosController@index']);

			Route::group(['prefix'=>'alumnos'],function(){
				Route::get('consulta',['as'=>'listado_alumnos','uses'=>'listadosController@getAlumnos']);
				Route::get('niveles','listadosController@getNiveles');
				Route::get('/niveles/{id}','listadosController@getAlumnosPorNivel');
				Route::get('/exportar/nivel/{id}','listadosController@exportarAlumnos');
			});


		});
	});

	Route::group(['prefix'=>'institucion'],function(){

		Route::group(['namespace'=>'institucion'],function(){

			Route::get('/',['as'=>'institucion','uses'=>'InstitucionController@index']);

			Route::group(['prefix'=>'/empleado'], function(){
				Route::get('/crear',['as'=>'crear_empleado','uses'=>'InstitucionController@getEmpleadoCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_empleado','uses'=>'InstitucionController@postEmpleadoCrear']);
				Route::post('/editar',['as'=>'editar_empleado','uses'=>'InstitucionController@getEmpleadoEditar']);
				Route::put('/editar',['as'=>'editar_empleado','uses'=>'InstitucionController@putEmpleadoEditar']);
			});

			Route::group(['prefix'=>'/pension'], function(){
				Route::get('/crear',['as'=>'crear_pension','uses'=>'InstitucionController@getPensionCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_pension','uses'=>'InstitucionController@postPensionCrear']);
				Route::get('/agregar',['as'=>'getcrear_pension']);
				Route::get('/agregar/{alumnos_id}','InstitucionController@getPensionActual')->middleware(['administrador', 'coordinador']);
				Route::get('/imprimir',['as'=>'imprimir_pension']);
				Route::get('/imprimir/{id}','InstitucionController@imprimePension')->middleware(['administrador', 'coordinador']);
				Route::post('/editar',['as'=>'editar_pension','uses'=>'InstitucionController@getPensionEditar']);
				Route::get('/editar/{alumnos_id}','InstitucionController@getPensionEditarId');
				Route::put('/editar',['as'=>'editar_pension','uses'=>'InstitucionController@putPensionEditar']);
				Route::delete('/editar',['as'=>'editar_pension','uses'=>'InstitucionController@deletePensionEditar']);
				Route::get('/factura/{factura}','InstitucionController@getFacturaPension');
			});

			Route::group(['prefix'=>'/matricula'], function(){
				Route::get('/crear',['as'=>'crear_matricula','uses'=>'InstitucionController@getMatriculaCrear'])->middleware(['administrador', 'coordinador']);
				Route::post('/crear',['as'=>'crear_matricula','uses'=>'InstitucionController@postMatriculaCrear']);
				Route::post('/editar',['as'=>'editar_matricula','uses'=>'InstitucionController@getMatriculaEditar']);
				Route::put('/editar',['as'=>'editar_matricula','uses'=>'InstitucionController@putMatriculaEditar']);
				Route::get('/editar/{alumnos_id}','InstitucionController@getMatriculaEditarId');
				Route::get('/imprimir',['as'=>'imprimir_matricula']);
				Route::get('/imprimir/{id}','InstitucionController@imprimeMatricula')->middleware(['administrador', 'coordinador']);
				Route::delete('/editar',['as'=>'editar_matricula','uses'=>'InstitucionController@deleteMatriculaEditar']);
				Route::get('/factura/{factura}','InstitucionController@getFacturaMatricula');
			});

			Route::group(['prefix'=>'/opagos'],function(){
				Route::get('/imprimir/{id}','InstitucionController@imprimeOpagosId');
				Route::get('/factura/{factura}','InstitucionController@getFacturaOpagos');
			});

			Route::group(['prefix'=>'/nomina'], function(){
				Route::get('/crear',['as'=>'crear_nomina','uses'=>'InstitucionController@getNominaCrear'])->middleware(['administrador']);
				Route::post('/crear',['as'=>'crear_nomina','uses'=>'InstitucionController@postNominaCrear']);
				Route::post('/editar',['as'=>'editar_nomina','uses'=>'InstitucionController@getNominaEditar']);
				Route::put('/editar',['as'=>'editar_nomina','uses'=>'InstitucionController@putNominaEditar']);
				Route::delete('/editar',['as'=>'editar_nomina','uses'=>'InstitucionController@deleteNominaEditar']);
				Route::post('/exportar',['as'=>'exportar_nomina','uses'=>'InstitucionController@exportarNomina']);
			});

			Route::group(['prefix'=>'/estado'], function(){
				Route::get('/crear',['as'=>'crear_estado','uses'=>'InstitucionController@getEstadoCrear'])->middleware(['administrador']);
				Route::post('/crear',['as'=>'crear_estado','uses'=>'InstitucionController@postEstadoCrear']);
				Route::post('/editar',['as'=>'editar_estado','uses'=>'InstitucionController@getEstadoEditar']);
				Route::put('/editar',['as'=>'editar_estado','uses'=>'InstitucionController@putEstadoEditar']);
				Route::delete('/editar',['as'=>'editar_estado','uses'=>'InstitucionController@deleteEstadoEditar']);
			});

			Route::group(['prefix'=>'/pago'], function(){

				Route::get('/cierrecaja/{fecha}/{tPension}/{tMatri}/{tOtros}/{tTotal}','InstitucionController@tirillaCaja');

				Route::get('/gestioncentral',['as'=>'gestion-pagos','uses'=>'InstitucionController@gestionPagos']);

				Route::get('/pensiones','InstitucionController@indexPensiones');
				Route::post('/pensiones','InstitucionController@guardarPensiones');
				Route::get('/pensiones/{alumnos_id}','InstitucionController@encontrarPensiones');
				Route::post('/pension/porfecha','InstitucionController@pensionPorDia');

				Route::get('/matriculas','InstitucionController@indexMatriculas');
				Route::post('/matriculas','InstitucionController@guardarMatriculas');
				Route::get('/matriculas/{alumnos_id}','InstitucionController@encontrarMatriculas');
				Route::post('/matricula/porfecha','InstitucionController@matriculaPorDia');

				Route::get('/otros','InstitucionController@indexOtros');
				Route::post('/otros','InstitucionController@guardarOtros');
				Route::get('/otros/{id}/delete','InstitucionController@borrarOtros');
				Route::get('/otros/{alumnos_id}','InstitucionController@encontrarOtros');
				Route::post('/otros/porfecha','InstitucionController@otrospPorDia');
				
			});

			Route::get('/meses','InstitucionController@indexMeses');
			Route::get('/alumnos/{buscado}','InstitucionController@buscarAlumnos');

		});
	});

	Route::group(['prefix'=>'mantenimiento','namespace'=>'mantenimiento'],function(){
		Route::get('/manual','MantenimientoController@getLimpiarHuerfanosTotal');
		Route::get('/general',function(){
			return view('mantenimientocompuesto');
		});
		Route::get('/highnota','MantenimientoController@highRegistroNotas');
		Route::get('/limpiezanotas/{idBajo}/{idAlto}','MantenimientoController@getLimpiarNotasRango');
	});

});



