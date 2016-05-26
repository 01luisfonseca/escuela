(function(){
	var app=angular.module('mantenimiento',[]);
	app.directive('mttoDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/mantenimiento.html'
		};
	});	
	app.controller('mttoCtrl',function($scope,$http){
		$scope.registroNotas={};
		$scope.notasBorradas={recorridos:[]};
		$scope.rangoBusqueda=500;
		$scope.eliminarNotasHuerfanas=function(){
			for (var i = 0; i*$scope.rangoBusqueda < $scope.registroNotas; i++) {
				$scope.buscarRangoNotas(i*$scope.rangoBusqueda,(i+1)*$scope.rangoBusqueda);
			}
		};
		$scope.buscarRangoNotas=function(idBajo,idAlto){
			$http.get('/mantenimiento/limpiezanotas/'+idBajo+'/'+idAlto).then(
				function(response){
					$scope.notasBorradas.recorridos.push={low:idBajo,hi:idAlto,eliminados:response.data,status:response.status};
				},
				function(response){
					$scope.notasBorradas.recorridos.push={low:idBajo,hi:idAlto,eliminados:0,status:response.status};
				}
			);
		};
		$scope.cargarRegistroNotas=function(){
			$http.get('/mantenimiento/highnota').then(
				function(response){$scope.registroNotas=response.data},
				function(response){$scope.registroNotas=response.status}
			);
		};
		$scope.cargarRegistroNotas();
	});
})();