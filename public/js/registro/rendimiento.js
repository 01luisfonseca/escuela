(function(){
	var app=angular.module('rendimiento',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	app.directive('estudianteDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/rendimiento.html'
		};
	});
	app.directive('explicrendimDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/explicrendim.html'
		};
	});
	app.controller('rendimientoCrtl',function($scope,$http,$window){
		$scope.alumno={};
		$scope.nivelSeleccionado={};
		$scope.obtenerNiveles=function(){
			$http.get('/registro/estudiantil/obtenerniveles').then(
				function(response){
					$scope.alumno=response;
					//console.log($scope.alumno);
				},
				function(response){
					$scope.alumno=response;
				});
		};
		$scope.obtenerNotasAlumno=function(alumnos_id){
			$scope.nivelSeleccionado={};
			$http.get('/registro/estudiantil/obteneractividad/'+alumnos_id).then(
				function(response){
					$scope.nivelSeleccionado=response;
					console.log($scope.nivelSeleccionado);
				},
				function(response){
					$scope.nivelSeleccionado=response;
				});
		};
		$scope.promediador=function(materiaId,periodoId,indicadorId){
			var materias=$scope.nivelSeleccionado.data.materias_has_niveles;
			for (var i = 0; i < materias.length; i++) {
				var totalPeriodo=0;
				for (var j = 0; j < materias[i].niveles_has_periodos.length; j++) {
					var totalIndicadores=0;
					for (var k = 0; k < materias[i].niveles_has_periodos[j].indicadores.length; k++) {
						var totalTipo=0;
						for (var l = 0; l < materias[i].niveles_has_periodos[j].indicadores[k].tipo_nota.length; l++) {
							totalTipo+=parseFloat(materias[i].niveles_has_periodos[j].indicadores[k].tipo_nota[l].notas[0].calificacion);
						}
						totalTipo/=materias[i].niveles_has_periodos[j].indicadores[k].tipo_nota.length;
						if(typeof indicadorId!='undefined' || typeof indicadorId!='null'){
							if (materias[i].niveles_has_periodos[j].indicadores[k].id==indicadorId) {
								return totalTipo;
							}
						}
						totalIndicadores+=totalTipo*parseFloat(materias[i].niveles_has_periodos[j].indicadores[k].porcentaje)/100;

					}
					if(typeof periodoId!='undefined' || typeof periodoId!='null'){
						if (materias[i].niveles_has_periodos[j].id==periodoId) {
							return totalIndicadores;
						}
					}
					totalPeriodo+=totalIndicadores;
				}
				if(typeof materiaId!='undefined' || typeof materiaId!='null'){
					if (materias[i].id==materiaId) {
						return totalPeriodo/materias[i].niveles_has_periodos.length;
					}
				}
			}
		};
		$scope.promedioIndicador=function(indicadorId){
			return $scope.promediador(null,null,indicadorId);
		};
		$scope.promedioPeriodo=function(periodoId){
			return $scope.promediador(null,periodoId,null);
		};
		$scope.promedioMateria=function(materiaId){
			return $scope.promediador(materiaId,null,null);
		};
		$scope.obtenerNiveles();
	});
})();