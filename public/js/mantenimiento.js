(function(){
	var app=angular.module('mantenimiento',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
    	});
	app.directive('mttoDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/mantenimiento.html'
		};
	});	
	app.controller('mttoCtrl',function($scope,$http){
		$scope.registroNotas={};
		$scope.notasBorradas=[];
		$scope.rangoBusqueda=1000;
		$scope.estadoRevision=0;
		$scope.procesoFinal=false;
		$scope.eliminarNotasHuerfanas=function(){
			$scope.procesoFinal=false;
			$scope.buscarRangoNotas(0,$scope.rangoBusqueda);
		};
		$scope.buscarRangoNotas=function(idBajo,idAlto){
			$http.get('/mantenimiento/limpiezanotas/'+idBajo+'/'+idAlto).then(
				function(response){
					$scope.notasBorradas.push={low:idBajo,hi:idAlto,eliminados:response.data,status:response.status};
					if (response.data>0) {
						$scope.buscarRangoNotas(idBajo,idAlto);
					}else{
						if(idAlto-$scope.rangoBusqueda<=$scope.registroNotas){
							$scope.estadoRevision=idAlto;
							$scope.buscarRangoNotas(idAlto+1,$scope.rangoBusqueda+idAlto);
						}else{
							$scope.procesoFinal=true;
						}
						
					}
				},
				function(response){
					$scope.notasBorradas.recorridos.push={low:idBajo,hi:idAlto,eliminados:0,status:response.status};
				}
			);
		};
		$scope.cargarRegistroNotas=function(){
			$http.get('/mantenimiento/highnota').then(
				function(response){$scope.registroNotas=response.data},
				function(response){$scope.registroNotas=0}
			);
		};
		$scope.cargarRegistroNotas();
	});
})();