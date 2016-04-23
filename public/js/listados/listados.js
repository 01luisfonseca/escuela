(function(){
	var app=angular.module('listados',[]).config(function($interpolateProvider){
    	$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	app.directive('listadoAlumnos',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/listadoalumnos.html'
		};
	});
	app.controller('listController',function($scope,$http){
		$scope.nivelElegido=0;
		$scope.listaNiveles={};
		$scope.listaAlumnos={};
		this.exportarAlumnos=function(nivel){
			return '/listados/alumnos/exportar/nivel/'+nivel;
		};
		this.txtEditarUsuario=function(usuario){
			return '/usuarios/editar/'+usuario;
		};
		this.txtEditarAlumno=function(alumno){
			return '/registro/alumnos/editar/'+alumno;
		};
		this.buscarAlumnos=function(){
			$http.get('/listados/alumnos/niveles/'+$scope.nivelElegido).then(
				function(response){
					$scope.listaAlumnos=response.data;
				},
				function(response){
					$scope.listaAlumnos.status=response.status;
				}
			);
		};
		this.buscarNiveles=function(){
			$http.get('/listados/alumnos/niveles').then(
				function(response){
					$scope.listaNiveles=response.data;
				},
				function(response){
					$scope.listaNiveles.status=response.status;
				}
			);
		};
		this.buscarNiveles();
		
	});
})();