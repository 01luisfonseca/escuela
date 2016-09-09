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
		$scope.debug=true;
		$scope.registroNotas={};
		$scope.notasBorradas=[];
		$scope.rangoBusqueda=1000;
		$scope.estadoRevision=0;
		$scope.procesoFinal=false;
		$scope.log=function(objeto){
			if ($scope.debug) {
				console.log(objeto);
			}
		};
		$scope.eliminarNotasHuerfanas=function(){
			$scope.procesoFinal=false;
			$scope.buscarRangoNotas(0,$scope.rangoBusqueda);
		};
		$scope.buscarRangoNotas=function(idBajo,idAlto){
			$scope.log($scope.notasBorradas);
			$http.get('/mantenimiento/limpiezanotas/'+idBajo+'/'+idAlto).then(
				function(response){
					$scope.notasBorradas.push({low:idBajo,hi:idAlto,eliminados:response.data,status:response.status});
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
					$scope.notasBorradas.recorridos.push({low:idBajo,hi:idAlto,eliminados:0,status:response.status});
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
    
    
    app.controller('mttoAlumNuevo',function($http){
        var vm=this;
        
        /*** Variables y objetos ***/
        vm.alumnos={};
        vm.ciclos=0;
        vm.pasos=0;
        vm.procesoAcabado=true;
        vm.rango=200;
        
        /*** Funciones del controlador ***/
        vm.buscarAlumnos=buscarAlumnos;
        
        /*** Acciones automáticas  al cargar ***/
        //Ninguna
        
        /*** Declaración de funciones ***/
        
        /* Busca alumnos y los asigna a vm.alumnos, despues carga revisar notas */
        function buscarAlumnos(){
            vm.procesoAcabado=false;
            vm.alumnos={};
            vm.ciclos=0;
            vm.pasos=0;
            $http.get('/mantenimiento/alumnos/'+vm.rango).then(function(res){
                vm.alumnos=res;
                vm.ciclos=vm.alumnos.data.length;
                console.log('Ciclos asignados: '+vm.ciclos);
                console.log('Alumnos:');
                console.log(vm.alumnos);
                if(vm.ciclos>0){
                    revisarNotas();
                }
            });
        }
        
        /* Envía el alumno a creación de notas. */
        function revisarNotas(){
            if(!vm.alumnos.data[vm.pasos]){//Cierra en caso de que se acceda a un index que no existe
                console.log('Proceso de alumnos finalizado');
                console.log(vm.alumnos);
                vm.procesoAcabado=true;
                return true;
            }
            vm.alumnos.data[vm.pasos].revision=[];
            $http.get('/mantenimiento/autonotas/'+vm.alumnos.data[vm.pasos].id).then(function(res){
                console.log('Paso:'+vm.pasos);
                vm.alumnos.data[vm.pasos].revision.push(res.data);
                if(vm.pasos>vm.ciclos){
                    console.log('Proceso de alumnos finalizado');
                    console.log(vm.alumnos.data);
                    vm.procesoAcabado=true;
                }else{
                    vm.pasos++;
                    revisarNotas();
                }
            });
        }
    });
})();