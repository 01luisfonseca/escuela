(function(){
	var app=angular.module('annotas',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	app.directive('annotasDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/annotas.html'
		};
	});

	/*Para obtener notas por materia seleccionada*/
	app.controller('exportNivelCtrl',function($scope,$http,$window){
		$scope.debug=true; //Modificar para eliminar las advertencias de consola
		$scope.niveles={};
		$scope.nivelSelec=0;
		$scope.mostrarNiveles=false;

		/*Gestion de URL*/
		$scope.urlbase='/registro/analisis/notas';
		$scope.urlinfo=$scope.urlbase+'/info';

		/* Funcion de informacion de consola */
		$scope.log=function(objeto){
			if ($scope.debug) {
				console.log(objeto);
			}
		};

		/* Funcion para cargar las notas segun el id del nivel */
		$scope.getNotas=function(nivelId){
			$http.get($scope.urlinfo+'/promedioporperiodo/'+nivelId).then(
				function(response){
					$scope.materias=response;
					$scope.log('Responde materias al '+nivelId+':');
					$scope.log($scope.materias);
				},
				function(response){
					$scope.materias=response;
					$scope.log('No responde materias');
					$scope.log($scope.materias);
				}
			);
		};

		/* Obtener niveles */
		$scope.getNiveles=function(anio){
			$scope.mostrarNiveles=false;
			$http.get($scope.urlinfo+'/niveles').then(
				function(response){
					$scope.niveles=response;
					$scope.log('Responde Niveles:');
					$scope.log($scope.niveles);
					$scope.mostrarNiveles=true;
				},
				function(response){
					$scope.niveles=response;
					$scope.log('No responde niveles');
					$scope.log($scope.niveles);
				}
			);
		};

		/* Funciones que se cargan al iniciar */
		$scope.getNiveles();
	});

	/* Para exportar la totalidad de niveles */
	app.controller('exportCtrl',function($scope,$http,$window){
		$scope.debug=false; //Modificar para eliminar las advertencias de consola
		$scope.anios={};
		$scope.NivPer={};// Niveles, materias y periodos
		$scope.NivPerProm={}; // id de periodos y promedios
		$scope.NivPerStatus=0;
		$scope.NivPerPromStatus=0;
		$scope.NivPerList=[];
		$scope.NivPerNormalizado=[];
		$scope.datosCargados=false;

		/*Manejo de url*/
		var urlbaseNotas='/registro/analisis/notas';
		$scope.urlExcel=urlbaseNotas+'/excel';
		$scope.urlNivPer=urlbaseNotas+'/niveles_periodos';

		/* Funcion de informacion de consola */
		$scope.log=function(objeto){
			if ($scope.debug) {
				console.log(objeto);
			}
		};

		/*Funcion para obtener la lista completa de miveles, materias, periodos */
		$scope.getNivelPeriodo=function(anio){
			$scope.NivPer={};
			$scope.NivPerStatus=0;
			$scope.datosCargados=false;
			$http.get($scope.urlNivPer+'/'+anio)
				.then(
					function(response){
						$scope.NivPer=response.data;
						$scope.NivPerStatus=response.status;
						$scope.log('getNivelPer(). Respuesta de APi');
						$scope.log($scope.NivPer);
						$scope.cargarPromedios();
					},
					function(response){
						$scope.NivPerStatus=response.status;
						$scope.log('getNivelPer(). Sin respuesta de APi');
						$scope.log(response);
					});
		};

		/* Funcion que carga la lista de ids para el promedio */
		$scope.cargarPromedios=function(){
			var procesos=0;
			$scope.NivPerList=[];
			for (var i = $scope.NivPer.length - 1; i >= 0; i--) {
				for (var j = $scope.NivPer[i].materias_has_niveles.length - 1; j >= 0; j--) {
					for (var k = $scope.NivPer[i].materias_has_niveles[j].niveles_has_periodos.length - 1; k >= 0; k--) {
						$scope.NivPerList.push($scope.NivPer[i].materias_has_niveles[j].niveles_has_periodos[k].id);
						procesos++;
					}
				}
			}
			$scope.log('cargarPromedios(). Resultados');
			$scope.log('Lista:');
			$scope.log($scope.NivPerList);
			$scope.log('Procesos: '+procesos);
			$scope.enviarNivPerList();
		};
		$scope.enviarNivPerList=function(){
			var objeto={ids: $scope.NivPerList};
			$http.post($scope.urlNivPer+'/promedios',objeto).then(
				function(response){
					$scope.NivPerProm=response.data;
					$scope.NivPerPromStatus=response.status;
					$scope.datosCargados=true;
					$scope.log('enviarNivPerList(). Respuesta de APi');
					$scope.log(response);
					$scope.generarNormalizado();
				},
				function(response){
					$scope.NivPerPromStatus=response.status;
					$scope.log('enviarNivPerList(). Sin respuesta de APi');
					$scope.log(response);
				}
			);
		};
		$scope.generarNormalizado=function(){
			$scope.log('Inicia generarNormalizado()');
			$scope.NivPerNormalizado=[];
			var procesos=0;
			for (var i = $scope.NivPer.length - 1; i >= 0; i--) {
				for (var j = $scope.NivPer[i].materias_has_niveles.length - 1; j >= 0; j--) {
					for (var k = $scope.NivPer[i].materias_has_niveles[j].niveles_has_periodos.length - 1; k >= 0; k--) {
						var number=$scope.buscaPromedios($scope.NivPer[i].materias_has_niveles[j].niveles_has_periodos[k].id);
						number+='';
						var numbRes=number.replace('.',',');
						$scope.NivPerNormalizado.push(
							{
								nivel: $scope.NivPer[i].nombre_nivel,
								materia: $scope.NivPer[i].materias_has_niveles[j].materias.nombre_materia,
								periodo: $scope.NivPer[i].materias_has_niveles[j].niveles_has_periodos[k].periodos.nombre_periodo,
								promedio: numbRes
							}
						);
						procesos++;
					}
				}
			}
			$scope.log('Normalizando:');
			$scope.log($scope.NivPerNormalizado);
			$scope.log('Procesos: '+procesos);
		}

		/* Funcion para buscar el promedio */
		$scope.buscaPromedios=function(id){
			for (var i = $scope.NivPerProm.length - 1; i >= 0; i--) {
				if($scope.NivPerProm[i].id==id){
					return $scope.NivPerProm[i].promedio;
				}
			}
		};

		$scope.DescargaExcel=function(){
			var blob=new Blob([document.getElementById('exportable').innerHTML],
			{
				type:'application/vnd.opexmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'
			});
			saveAs(blob, "Reporte.xls");
		};

		/*Funciones que se cargan al subir el controlador*/
		$scope.getNivelPeriodo(2016);//Esto es remplazado con la implementación de años.
	});
})();