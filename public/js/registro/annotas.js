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
	app.controller('exportCtrl',function($scope,$http,$window){
		$scope.anios={};
		$scope.NivPer={};// Niveles, materias y periodos
		$scope.NivPerProm={}; // id de periodos y promedios
		$scope.NivPerStatus=0;
		$scope.NivPerPromStatus=0;
		$scope.NivPerList=[];
		$scope.datosCargados=false;

		/*Manejo de url*/
		var urlbaseNotas='/registro/analisis/notas';
		$scope.urlExcel=urlbaseNotas+'/excel';
		$scope.urlNivPer=urlbaseNotas+'/niveles_periodos';

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
						console.log('getNivelPer(). Respuesta de APi');
						console.log(response);
						$scope.cargarPromedios();
					},
					function(response){
						$scope.NivPerStatus=response.status;
						console.log('getNivelPer(). Sin respuesta de APi');
						console.log(response);
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
			console.log('cargarPromedios(). Resultados');
			console.log('Lista:');
			console.log($scope.NivPerList);
			console.log('Procesos: '+procesos);
			$scope.enviarNivPerList();
		};
		$scope.enviarNivPerList=function(){
			var objeto={ids: $scope.NivPerList};
			$http.post($scope.urlNivPer+'/promedios',objeto).then(
				function(response){
					$scope.NivPerProm=response.data;
					$scope.NivPerPromStatus=response.status;
					$scope.datosCargados=true;
					console.log('enviarNivPerList(). Respuesta de APi');
					console.log(response);
					$scope.unePromedios();
				},
				function(response){
					$scope.NivPerPromStatus=response.status;
					console.log('enviarNivPerList(). Sin respuesta de APi');
					console.log(response);
				}
			);
		};
		$scipe.unePromedios=function(){
			//Pendiente por unir las listas.
		};
		$scope.DescargaExcel=function(anio){
			$http.get($scope.urlExcel+'/'+anio)
				.then(function(response){console.log(response);},function(response){console.log(response);});
			$window.open($scope.urlExcel+'/'+anio);
		};

		/*Funciones que se cargan al subir el controlador*/
		$scope.getNivelPeriodo(2016);//Esto es remplazado con la implementación de años.
	});
})();