(function(){
	var app=angular.module('notas',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	app.directive('notasDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/notas.html'
		};
	});
	app.controller('notasCtrl',function($scope,$http,$window){
		$scope.cargando=false;
		$scope.mensajeTipo='';
		$scope.ventanaSeleccionada=0;
		$scope.promedios=new Array;
		$scope.materiasInNiveles={};
		$scope.nivelesInPeriodos={};
		$scope.activaPeriodos=false;
		$scope.activaNotas=false;
		$scope.cargandoNotas=false;
		$scope.materiaSeleccionada=0;
		$scope.periodoSeleccionado=0;
		$scope.notasInPeriodo={};
		$scope.indicadores={};
		$scope.alumnos={};
		$scope.nIndicador={};
		$scope.porcentajeMaximo=100;
		$scope.registroIndicador={};
		$scope.registroIndicador.mensaje='';
		$scope.verificador='Inicia. ';
		$scope.inicializaVar=function(){
			$scope.indicadores={};
			$scope.alumnos={};
			$scope.nIndicador={};
			$scope.registroIndicador={};
			$scope.registroIndicador.mensaje='';
			$scope.promedios=new Array;
		};
		$scope.selecciona=function(ventana){
			$scope.ventanaSeleccionada=ventana;
		};
		$scope.esSeleccionada=function(ventana){
			return $scope.ventanaSeleccionada===ventana
		};
		$scope.calculoDefinitiva=function(indexAlu){
			var definitiva=0;
			for (var i = $scope.promedios.length - 1; i >= 0; i--) {
				definitiva+=parseFloat($scope.promedios[i].alumnos[indexAlu].promedio)*parseFloat($scope.promedios[i].porcentaje)/100;
			}
			return definitiva;
		};
		$scope.actPromedio=function(){
			for (var x = 0; x < $scope.indicadores.length; x++) {
				$scope.promedios[x]={};
				$scope.promedios[x].id=$scope.indicadores[x].id;
				$scope.promedios[x].nombre=$scope.indicadores[x].nombre;
				$scope.promedios[x].porcentaje=$scope.indicadores[x].porcentaje;
				$scope.promedios[x].alumnos=[];
				for (var i = 0; i < $scope.indicadores[x].tipo_nota[0].notas.length; i++) {
					$scope.promedios[x].alumnos[i]={};
					$scope.promedios[x].alumnos[i].id=$scope.indicadores[x].tipo_nota[0].notas[i].alumnos.id;
					$scope.promedios[x].alumnos[i].name=$scope.indicadores[x].tipo_nota[0].notas[i].alumnos.users.name;
					$scope.promedios[x].alumnos[i].lastname=$scope.indicadores[x].tipo_nota[0].notas[i].alumnos.users.lastname;
					$scope.promedios[x].alumnos[i].promedio=0;
					for (var j = $scope.indicadores[x].tipo_nota.length - 1; j >= 0; j--) {
						$scope.indicadores[x].tipo_nota[j].notas[i].calificacion=parseFloat($scope.indicadores[x].tipo_nota[j].notas[i].calificacion);
						$scope.promedios[x].alumnos[i].promedio+=$scope.indicadores[x].tipo_nota[j].notas[i].calificacion;
					}
					$scope.promedios[x].alumnos[i].promedio=$scope.promedios[x].alumnos[i].promedio/$scope.indicadores[x].tipo_nota.length;
				}
			}
			for (var i = 0; i < $scope.promedios[0].alumnos.length; i++) {
				$scope.promedios[0].alumnos[i].definitiva=$scope.calculoDefinitiva(i);
			}
		};
		$scope.actNota=function($id,$calificacion){
			$http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/notas/'+$id+'/'+$calificacion+'/actualizar').then(
				function(response){
					$scope.registroIndicador=response.data;
					$scope.actPromedio();
				},
				function(response){
					$scope.registroIndicador.status = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
		$scope.eliminarTipo=function(tipoId){
			if ($window.confirm("¿Desea eliminar el tipo de nota? Se eliminarán las notas asociadas.")) {
				$http.get('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/'+tipoId+'/delete').then(
					function(response){
						$scope.registroIndicador=response.data;
						$scope.actualizarTodo();
						$window.alert($scope.registroIndicador.mensaje);
					},
					function(response){
						$scope.registroOtros.mensaje = response.status || 'No hay comunicación con el servidor';
					}
				);
			}
		};
		$scope.crearNotaBasica=function(tipoId){
			$http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/notas/'+tipoId+'/basica').then(
				function(response){
					$scope.registroIndicador=response.data;
					$scope.actualizarTodo();
				},
				function(response){
					$scope.registroOtros.mensaje = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
		$scope.actTipo=function(indicIndex,index){
			$http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/'+$scope.indicadores[indicIndex].tipo_nota[index].id+'/actualizar',$scope.indicadores[indicIndex].tipo_nota[index]).then(
				function(response){
					$scope.mensajeTipo=response.data.mensaje;
				},
				function(response){
					$scope.mensajeTipo= response.status || 'No hay comunicación con el servidor';
				}
				);
		};
		$scope.blanquearMensajeTipo=function(){
			$scope.mensajeTipo='';
		};
		$scope.evaluaTituloTipo=function(titulo){
			if (titulo!='No definido') {
				return titulo;
			}
			return '';
		};
		$scope.crearTipo=function(id){
			$http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/'+id).then(
				function(response){
					$scope.registroIndicador=response.data;
					$scope.actualizarTodo();
				},
				function(response){
					$scope.registroOtros.mensaje = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
		this.actIndicador=function(index){
			$http.post('/registro/notas/materias_asignadas/periodos/indicadores/'+$scope.indicadores[index].id+'/actualizar',$scope.indicadores[index]).then(
				function(response){
					$scope.registroIndicador=response.data;
					if ($scope.registroIndicador.estado===true) {
						$scope.nIndicador={};
						$scope.actualizarTodo();
					};
				},
				function(response){
					$scope.registroIndicador.status = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
		$scope.actualizarTodo=function(){
			$scope.buscarNotas();
		};
		$scope.postBuscarNotas=function(){
			$scope.indicadores=$scope.notasInPeriodo.data[0].indicadores;
			$scope.activaNotas=true;
			$scope.porcentajeMax();
			for (var i = $scope.indicadores.length - 1; i >= 0; i--) {
				for (var j = $scope.indicadores[i].tipo_nota.length - 1; j >= 0; j--) {
					if ($scope.indicadores[i].tipo_nota[j].notas.length<=0) {
						$scope.crearNotaBasica($scope.indicadores[i].tipo_nota[j].id);
					};
				};
			};
			$scope.actPromedio();
		};
		this.borrarIndicador=function(id){
			if ($window.confirm('¿Desea eliminar el indicador? Se eliminarán los tipos de nota y las notas asociadas.')) {
				$http.get('/registro/notas/materias_asignadas/periodos/indicadores/'+id+'/delete').then(
					function(response){
						$scope.registroIndicador=response.data;
						$scope.actualizarTodo();
						$window.alert($scope.registroIndicador.mensaje);
					},
					function(response){
						$scope.registroOtros.mensaje = response.status || 'No hay comunicación con el servidor';
					}
				);
			}
		};
		this.addIndicador=function(){
			$http.post('/registro/notas/materias_asignadas/periodos/indicadores/'+$scope.periodoSeleccionado,$scope.nIndicador).then(
				function(response){
					$scope.registroIndicador=response.data;
					if ($scope.registroIndicador.estado===true) {
						$scope.nIndicador={};
						$scope.actualizarTodo();
					};
				},
				function(response){
					$scope.registroIndicador.status = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
		$scope.porcentajeMax=function(){
			sumador=0;
			try {
				for (var i = $scope.indicadores.length - 1; i >= 0; i--) {
					$scope.indicadores[i].porcentaje=parseFloat($scope.indicadores[i].porcentaje);
					sumador=sumador+$scope.indicadores[i].porcentaje;
				}
			}catch(err){
				$scope.porcentajeMaximo=100;
			}
			if (sumador>0) {
				if (sumador<100) {
					$scope.porcentajeMaximo=100-sumador;
				}else{
					$scope.porcentajeMaximo=0;
				}
			}else{
				$scope.porcentajeMaximo=100;
			}
		};
		this.buscarPeriodos=function(){
			$scope.nivelesInPeriodos={};
			$scope.inicializaVar();
			$http.get('/registro/notas/materias_asignadas/periodos/'+$scope.materiaSeleccionada).then(
				function(response){
					$scope.nivelesInPeriodos.data=response.data;
					$scope.nivelesInPeriodos.status=response.status;
					$scope.activaPeriodos=true;
					$scope.activaNotas=false;
				},function(response){
					$scope.nivelesInPeriodos.status=response.status;
				}
			);
		};
		$scope.buscarNotas=function(){
			$scope.inicializaVar();
			$scope.cargandoNotas=true;
			$scope.cargando=true;
			$http.get('/registro/notas/materias_asignadas/periodos/notas/'+$scope.periodoSeleccionado).then(
				function(response){
					$scope.notasInPeriodo.data=response.data;
					$scope.notasInPeriodo.status=response.status;
					$scope.buscarAlumnosNotas();
					$scope.cargandoNotas=false;
				},function(response){
					$scope.notasInPeriodo.status=response.status;
					$scope.cargandoNotas=false;
				}
			);
		};
		$scope.buscarAlumnosNotas=function(){
            $scope.cargando=false;
            $scope.postBuscarNotas();
			/*$http.get('/registro/notas/materias_asignadas/periodos/alumnos/'+$scope.periodoSeleccionado).then(
				function(response){
					$scope.alumnos=response.data;
					$scope.registroIndicador.status=response.status;
					$scope.cargando=false;
					$scope.postBuscarNotas();
				},function(response){
					$scope.registroIndicador.status=response.status;
				}
			);*/
		};
		this.buscarMateriasAsignadas=function(){
			$http.get('/registro/notas/materias_asignadas').then(
				function(response){
					$scope.materiasInNiveles.data=response.data;
					$scope.materiasInNiveles.status=response.status;
				},function(response){
					$scope.materiasInNiveles.status=response.status;
				}
			);
		};
		this.buscarMateriasAsignadas();
	});
})();