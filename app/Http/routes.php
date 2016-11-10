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

//Rutas de dispositivos autorizados
Route::group(['middleware'=>'checkSerial'],function(){
	Route::group(['namespace'=>'registro'],function(){
		Route::group(['prefix'=>'{serial}/device'],function(){
			Route::get('/status',function(){
				return response('Habilitado',200);
			});
			Route::post('/asistencia/{tarjeta}','AsistenciaCtrl@postAsistencia');
			Route::get('/asistencia/{tarjeta}','AsistenciaCtrl@getDeviceAsistencia');
			Route::get('/asistencia','AsistenciaCtrl@getOnlyTarjetas'); // Probadas para la carga
			Route::get('/all','AuthdeviceCtrl@getDevices');
		});
	});
});

// Solicitud automática de dispositivos. Este código sale del servidor intermedio al servidor central.
Route::group(['prefix'=>'autocarga','namespace'=>'auto'],function(){
	Route::get('/tarjetas','AutoCtrl@getTarjetas');
	Route::get('/dispositivos','AutoCtrl@getDispositivos');
	Route::get('/syncasis','AutoCtrl@enviarAsistencias');
});

//Rutas de acceso restringido.
Route::group(['middleware'=>'auth'], function(){

	Route::get('/autorizado/home',['as'=>'autorizado/home', 'uses'=>'autorizado\AutorizadoHomeController@index']);
	Route::group(['prefix'=>'usuarios'],function (){
		Route::get('/',['as'=>'usuarios', 'uses'=>'usuarios\UsuariosController@index']);
		Route::get('/modificar',['as'=>'usuarios/modificar', 'uses'=>'usuarios\UsuariosController@getBuscar'])->middleware(['administrador', 'coordinador']);
		Route::post('/modificar',['as'=>'usuarios/modificar', 'uses'=>'usuarios\UsuariosController@postModificar'])->middleware(['administrador']);
		Route::post('/modificapassword',['as'=>'usuarios/modificapassword', 'uses'=>'usuarios\UsuariosController@postModificaPassword']);
		Route::get('/crear',['as'=>'usuarios/crear', 'uses'=>'usuarios\UsuariosController@getNuevo'])->middleware(['administrador']);
		Route::post('/registrar',['as'=>'usuarios/registrar', 'uses'=>'usuarios\UsuariosController@postRegistrar']);
		Route::post('/editar',['as'=>'usuarios/editar', 'uses'=>'usuarios\UsuariosController@gactualizar']);
		Route::get('/editar/{id}','usuarios\UsuariosController@editar')->middleware(['administrador']);
        Route::get('/perfil','usuarios\UsuariosController@perfil');
		Route::post('/actualizar',['as'=>'usuarios/actualizar', 'uses'=>'usuarios\UsuariosController@pactualizar'])->middleware(['actuser']);
		Route::post('/eliminar',['as'=>'usuarios/eliminar', 'uses'=>'usuarios\UsuariosController@eliminarUsuario'])->middleware(['administrador']);
		Route::get('/carnet',['as'=>'usuarios/carnet', 'uses'=>'usuarios\UsuariosController@getCarnet']);
	});

	Route::group(['prefix'=>'programas'],function(){

		Route::group(['namespace'=>'programas'],function(){

			Route::get('/',['as'=>'programas','uses'=>'ProgramasController@index']);

			Route::group(['prefix'=>'/nivel','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_nivel','uses'=>'ProgramasController@getNivelCrear']);
				Route::post('/crear',['as'=>'crear_nivel','uses'=>'ProgramasController@postNivelCrear']);
				Route::post('/editar',['as'=>'editar_nivel','uses'=>'ProgramasController@getNivelEditar']);
				Route::put('/editar',['as'=>'editar_nivel','uses'=>'ProgramasController@putNivelEditar']);
				Route::delete('/editar',['as'=>'editar_nivel','uses'=>'ProgramasController@deleteNivelEditar']);
			});
			
			Route::group(['prefix'=>'/materia','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_materia','uses'=>'ProgramasController@getMateriaCrear']);
				Route::post('/crear',['as'=>'crear_materia','uses'=>'ProgramasController@postMateriaCrear']);
				Route::post('/editar',['as'=>'editar_materia','uses'=>'ProgramasController@getMateriaEditar']);
				Route::put('/editar',['as'=>'editar_materia','uses'=>'ProgramasController@putMateriaEditar']);
				Route::delete('/editar',['as'=>'editar_materia','uses'=>'ProgramasController@deleteMateriaEditar']);
			});

			Route::group(['prefix'=>'/periodo','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_periodo','uses'=>'ProgramasController@getPeriodoCrear']);
				Route::post('/crear',['as'=>'crear_periodo','uses'=>'ProgramasController@postPeriodoCrear']);
				Route::post('/editar',['as'=>'editar_periodo','uses'=>'ProgramasController@getPeriodoEditar']);
				Route::put('/editar',['as'=>'editar_periodo','uses'=>'ProgramasController@putPeriodoEditar']);
				Route::delete('/editar',['as'=>'editar_periodo','uses'=>'ProgramasController@deletePeriodoEditar']);
			});

			Route::group(['prefix'=>'/plan','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_plan','uses'=>'ProgramasController@getPlanCrear']);
				Route::post('/crear',['as'=>'crear_plan','uses'=>'ProgramasController@postPlanCrear']);
				Route::post('/editar',['as'=>'editar_plan','uses'=>'ProgramasController@getPlanEditar']);
				Route::put('/editar',['as'=>'editar_plan','uses'=>'ProgramasController@putPlanEditar']);
				Route::delete('/editar',['as'=>'editar_plan','uses'=>'ProgramasController@deletePlanEditar']);
			});
		});
		
	});
	Route::group(['prefix'=>'registro'],function(){

		Route::group(['namespace'=>'registro'],function(){

			// Rutas para dispositivos
			Route::group(['prefix'=>'device','middleware'=>'administrador'],function(){
				Route::get('/','AuthdeviceCtrl@getDevices');
				Route::get('/{id}','AuthdeviceCtrl@getDevice');
				Route::get('/{id}/delete','AuthdeviceCtrl@delDevice');
				Route::post('/nuevo','AuthdeviceCtrl@setDevice');
				Route::post('/{id}','AuthdeviceCtrl@modDevice');
				Route::post('/{id}/estado','AuthdeviceCtrl@modEstado');
			});

			Route::get('/',['as'=>'registro','uses'=>'RegistroController@index']);

			Route::group(['prefix'=>'/alumnos','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_alumno','uses'=>'RegistroController@getAlumnoCrear']);
				Route::post('/crear',['as'=>'crear_alumno','uses'=>'RegistroController@postAlumnoCrear']);
				Route::post('/editar',['as'=>'editar_alumno','uses'=>'RegistroController@getAlumnoEditar']);
				Route::get('/editar/{id}','RegistroController@editarAlumno');
				Route::get('/actualizar',['as'=>'getactualizar_alumno']);
				Route::get('/actualizar/{alumnos_id}','RegistroController@getAlumnoActual');
				Route::put('/editar',['as'=>'editar_alumno','uses'=>'RegistroController@putAlumnoEditar']);
				Route::delete('/editar',['as'=>'editar_alumno','uses'=>'RegistroController@deleteAlumnoEditar']);
			});
			
			Route::group(['prefix'=>'/profesores','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_profesor','uses'=>'RegistroController@getProfesorCrear']);
				Route::post('/crear',['as'=>'crear_profesor','uses'=>'RegistroController@postProfesorCrear']);
				Route::post('/editar',['as'=>'editar_profesor','uses'=>'RegistroController@getProfesorEditar']);
				Route::put('/editar',['as'=>'editar_profesor','uses'=>'RegistroController@putProfesorEditar']);
				Route::delete('/editar',['as'=>'editar_profesor','uses'=>'RegistroController@deleteProfesorEditar']);
			});

			Route::group(['prefix'=>'/asistencia','middleware'=>'profesor'], function(){
				Route::get('/crear',['as'=>'crear_asistencia','uses'=>'RegistroController@getAsistenciaCrear']);
				Route::post('/crear',['as'=>'crear_asistencia','uses'=>'RegistroController@postAsistenciaCrear']);
				Route::post('/editar',['as'=>'editar_asistencia','uses'=>'RegistroController@getAsistenciaEditar']);
				Route::put('/editar',['as'=>'editar_asistencia','uses'=>'RegistroController@putAsistenciaEditar']);
				Route::delete('/editar',['as'=>'editar_asistencia','uses'=>'RegistroController@deleteAsistenciaEditar']);
			});

			// Rutas para newasistencia.
			Route::group(['prefix'=>'/newasistencia','middleware'=>'profesor'],function(){
				Route::get('/{inicio}/asist','AsistenciaCtrl@getAsistencias');
				Route::get('/info','AsistenciaCtrl@getInfoAsis');
			});

			Route::group(['prefix'=>'/rendimiento','middleware'=>'profesor'], function(){
				Route::get('/crear',['as'=>'crear_rendimiento','uses'=>'RegistroController@getRendimientoCrear']);
				Route::post('/crear',['as'=>'crear_rendimiento','uses'=>'RegistroController@postRendimientoCrear']);
				Route::post('/editar',['as'=>'editar_rendimiento','uses'=>'RegistroController@getRendimientoEditar']);
				Route::put('/editar',['as'=>'editar_rendimiento','uses'=>'RegistroController@putRendimientoEditar']);
				Route::delete('/editar',['as'=>'editar_rendimiento','uses'=>'RegistroController@deleteRendimientoEditar']);
			});

			Route::group(['prefix'=>'/notas'], function(){
				Route::get('/crear',['as'=>'crear_rendimientorest','uses'=>'NotasController@index']);
				Route::get('/materias_asignadas/{nivelId}','NotasController@getMateriasAuth');
				Route::get('/materias_asignadas/periodos/{id}','NotasController@getNivelesHasPeriodos');
				Route::get('/materias_asignadas/periodos/alumnos/{idNiveles}','NotasController@getAlumnosEnNivel');
				Route::get('/materias_asignadas','NotasController@getMateriasHasNiveles');
                Route::get('/materias_asignadas/periodos/indicadores/{nivPerid}','NotasController@getOnlyIndicadores');
                Route::get('/materias_asignadas/periodos/notas/{id}','NotasController@getIndicadores');
				Route::get('/materias_asignadas/periodos/indicadores/{id}/delete','NotasController@borrarIndicador');
				Route::get('/materias_asignadas/periodos/indicadores/tipo_nota/{id}/delete','NotasController@delTipoNotas');
                Route::post('/materias_asignadas/periodos/alumnos/rellenar','NotasController@rellenarAlumnosNuevosNivel')->middleware(['administrador']);
				Route::post('/materias_asignadas/periodos/indicadores/{nivelesInPeriodosId}','NotasController@setIndicadores')->middleware(['profesor']);
				Route::post('/materias_asignadas/periodos/indicadores/{id}/actualizar','NotasController@actIndicadores')->middleware(['profesor']);
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/{indicadoresId}','NotasController@setTipoNotas')->middleware(['profesor']);
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/{tipoId}/actualizar','NotasController@actTipoNotas')->middleware(['profesor']);
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/notas/{tipoNotaId}','NotasController@setNotas')->middleware(['profesor']);
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/notas/{id}/{cal}/actualizar','NotasController@actNotas')->middleware(['profesor']);
				Route::post('/materias_asignadas/periodos/indicadores/tipo_nota/notas/{tipoNotaId}/basica','NotasController@setNotaBasica')->middleware(['profesor']);
				Route::get('/niveles_asignados','NotasController@getNivelesAuth');
				Route::get('/periodos_asignados/{matId}','NotasController@getPeriodosPorMateria');
				Route::get('/indicadores/{periodoId}','NotasController@getNewIndicadores');

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

					/* Grupo de informacion */
					Route::group(['prefix'=>'info'],function(){
						Route::get('/niveles','AnalisisNotasCtrl@getNiveles');
						Route::get('/promedioporperiodo/{nivelId}','AnalisisNotasCtrl@getNotasPromediadas');
						Route::get('/promedioporperiodo/alumno/{alumnosId}/periodo/{periodoId}','AnalisisNotasCtrl@getPromedioPeriodoAlumno');
						Route::post('/promedioporlista/{procesos}','AnalisisNotasCtrl@promedioPorLista');
					});

					/* Grupo de informacion por niveles y periodos */
					Route::group(['prefix'=>'niveles_periodos'],function(){
						Route::get('/{anio}','AnalisisNotasCtrl@obtenerNivelesPeriodos');
						Route::post('/promedios','AnalisisNotasCtrl@calcularPromedios');
					});
				});
			});

		});
	});

	Route::group(['prefix'=>'listados','middleware'=>'coordinador'],function(){

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

			Route::group(['prefix'=>'/empleado','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_empleado','uses'=>'InstitucionController@getEmpleadoCrear']);
				Route::post('/crear',['as'=>'crear_empleado','uses'=>'InstitucionController@postEmpleadoCrear']);
				Route::post('/editar',['as'=>'editar_empleado','uses'=>'InstitucionController@getEmpleadoEditar']);
				Route::put('/editar',['as'=>'editar_empleado','uses'=>'InstitucionController@putEmpleadoEditar']);
			});

			Route::group(['prefix'=>'/pension','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_pension','uses'=>'InstitucionController@getPensionCrear']);
				Route::post('/crear',['as'=>'crear_pension','uses'=>'InstitucionController@postPensionCrear']);
				Route::get('/agregar',['as'=>'getcrear_pension']);
				Route::get('/agregar/{alumnos_id}','InstitucionController@getPensionActual');
				Route::get('/imprimir',['as'=>'imprimir_pension']);
				Route::get('/imprimir/{id}','InstitucionController@imprimePension');
				Route::post('/editar',['as'=>'editar_pension','uses'=>'InstitucionController@getPensionEditar']);
				Route::get('/editar/{alumnos_id}','InstitucionController@getPensionEditarId');
				Route::put('/editar',['as'=>'editar_pension','uses'=>'InstitucionController@putPensionEditar']);
				Route::delete('/editar',['as'=>'editar_pension','uses'=>'InstitucionController@deletePensionEditar']);
				Route::get('/factura/{factura}','InstitucionController@getFacturaPension');
			});

			Route::group(['prefix'=>'/matricula','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_matricula','uses'=>'InstitucionController@getMatriculaCrear']);
				Route::post('/crear',['as'=>'crear_matricula','uses'=>'InstitucionController@postMatriculaCrear']);
				Route::post('/editar',['as'=>'editar_matricula','uses'=>'InstitucionController@getMatriculaEditar']);
				Route::put('/editar',['as'=>'editar_matricula','uses'=>'InstitucionController@putMatriculaEditar']);
				Route::get('/editar/{alumnos_id}','InstitucionController@getMatriculaEditarId');
				Route::get('/imprimir',['as'=>'imprimir_matricula']);
				Route::get('/imprimir/{id}','InstitucionController@imprimeMatricula');
				Route::delete('/editar',['as'=>'editar_matricula','uses'=>'InstitucionController@deleteMatriculaEditar']);
				Route::get('/factura/{factura}','InstitucionController@getFacturaMatricula');
			});

			Route::group(['prefix'=>'/opagos','middleware'=>'coordinador'],function(){
				Route::get('/imprimir/{id}','InstitucionController@imprimeOpagosId');
				Route::get('/factura/{factura}','InstitucionController@getFacturaOpagos');
			});

			Route::group(['prefix'=>'/nomina','middleware'=>'coordinador'], function(){
				Route::get('/crear',['as'=>'crear_nomina','uses'=>'InstitucionController@getNominaCrear']);
				Route::post('/crear',['as'=>'crear_nomina','uses'=>'InstitucionController@postNominaCrear']);
				Route::post('/editar',['as'=>'editar_nomina','uses'=>'InstitucionController@getNominaEditar']);
				Route::put('/editar',['as'=>'editar_nomina','uses'=>'InstitucionController@putNominaEditar']);
				Route::delete('/editar',['as'=>'editar_nomina','uses'=>'InstitucionController@deleteNominaEditar']);
				Route::post('/exportar',['as'=>'exportar_nomina','uses'=>'InstitucionController@exportarNomina']);
			});

			Route::group(['prefix'=>'/estado','middleware'=>'administrador'], function(){
				Route::get('/crear',['as'=>'crear_estado','uses'=>'InstitucionController@getEstadoCrear']);
				Route::post('/crear',['as'=>'crear_estado','uses'=>'InstitucionController@postEstadoCrear']);
				Route::post('/editar',['as'=>'editar_estado','uses'=>'InstitucionController@getEstadoEditar']);
				Route::put('/editar',['as'=>'editar_estado','uses'=>'InstitucionController@putEstadoEditar']);
				Route::delete('/editar',['as'=>'editar_estado','uses'=>'InstitucionController@deleteEstadoEditar']);
			});

			Route::group(['prefix'=>'/pago','middleware'=>'coordinador'], function(){

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

	Route::group(['prefix'=>'mantenimiento','namespace'=>'mantenimiento','middleware'=>'coordinador'],function(){
		Route::get('/manual','MantenimientoController@getLimpiarHuerfanosTotal');
		Route::get('/autonotas/{id}','MantenimientoController@autoLlenarNotas');
		Route::get('/alumnos/{rango}','MantenimientoController@usuariosRecientes');
		Route::get('/general',function(){
			return view('mantenimientocompuesto');
		});
		Route::get('/highnota','MantenimientoController@highRegistroNotas');
		Route::get('/limpiezanotas/{idBajo}/{idAlto}','MantenimientoController@getLimpiarNotasRango');
	});

	Route::group(['prefix'=>'optgen','namespace'=>'optgen','middleware'=>'administrador'],function(){
		Route::get('/','OptgenCtrl@index');
		Route::get('/opciones','OptgenCtrl@create');
		Route::post('/opcion','OptgenCtrl@store');
		Route::post('/opcion/{id}/act','OptgenCtrl@update');
		Route::post('/opcion/{id}/del','OptgenCtrl@destroy');
	});

});



