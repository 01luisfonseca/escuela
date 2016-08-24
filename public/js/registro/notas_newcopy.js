(function(){
	var app=angular.module('notas',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	app.directive('notasDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/notas.html',
            controller:controller,
            controllerAs:vm,
		};
                
        function controller($http,$window){
            var vm=this;
            
            vm.porcentajeMaximo=100;    // Maximo porcentaje de indicador
            vm.ventanaSeleccionada=0;   // Ventana seleccionada para mostrar notas.
            vm.elegido={                // Que se ha elegido
                materia:0,
                periodo:0,
                indicador:0,
            };
            vm.cargando={               // Estado de carga de la aplicación
                indicadores:false,
                notas:false,
            };
            vm.info={};                 // Muestra la información de respuesta de la API
            vm.nivelesHasMaterias={};   // Recoge las materias habilitadas por el usuario
            vm.periodosPorMateria={};   // Recoge los periodos según la materia elegida
            vm.indicadores={};          // Recoge los indicadores
            vm.notasRaw={};             // Recoge a los alumnos y a las notas de la API
            vm.nuevoIndic={};           // Almacena el nuevo indicador 
            vm.promedios=new Array;
            
            // Funciones
            vm.buscarMateriasAsignadas=buscarMateriasAsignadas; // Busca las materias del nivel
            vm.buscarPeriodos=buscarPeriodos;                   // Busca periodos relacionados con materias
            vm.buscarIndicadores=buscarIndicadores;             // Busca los indicadores del periodo elegido
            vm.actIndicador=actIndicador;                       // Actualiza el indicador según el $index.
            vm.borrarIndicador=borrarIndicador;                 // Borra el indicador según el ID
            vm.addIndicador=addIndicador;                       // Añade indicador.
            vm.buscarNotas=buscarNotas;                         // Actualiza las notas
            
            // Funciones para control de ventanas
            vm.esSeleccionada=esSeleccionada;
            
            // Lanzamientos automáticos
            vm.buscarMateriasAsignadas(); // Lanzar la búsqueda.
            
            /////////////////////////////////////////////////
            
            // Funcion que recoge las materias en niveles
            function buscarMateriasAsignadas(){
                vm.nivelesHasMaterias={};
                $http.get('/registro/notas/materias_asignadas').then(
				    function(data){
                        vm.nivelesHasMaterias=data.data;
                    });
            }
            
            // Funcion que recoge los periodos de las materias
            function buscarPeriodos(){
                console.log('Materia elegida: '+vm.elegido.materia);
                vm.periodosPorMateria={};
                $http.get('/registro/notas/materias_asignadas/periodos/'+vm.elegido.materia).then(
				function(data){
					vm.periodosPorMateria=data.data;
				});
            }
            
            // Funcion que recoge los indicadores, tipos de nota, notas y alumnos
            function buscarIndicadores(){
                vm.indicadores={};
                vm.cargando.indicadores=true;
                vm.buscarNotas();
                $http.get('/registro/notas/materias_asignadas/periodos/indicadores/'+vm.elegido.periodo).then(
				    function(response){
				        vm.indicadores=response.data;
                        vm.cargando.indicadores=false;
                        porcentajeMax();
				});
            }
            
            // Calcula el promedio restante de indicador nuevo
            function porcentajeMax(){
                var sumador=0;
                try {
				    for (var i = vm.indicadores.length - 1; i >= 0; i--) {
					   vm.indicadores[i].porcentaje=parseFloat(vm.indicadores[i].porcentaje);
					   sumador=sumador+vm.indicadores[i].porcentaje;
				    }
                }
                catch(err){
                    vm.porcentajeMaximo=100;
                }
                if (sumador>0) {
				    if (sumador<100) {
					   vm.porcentajeMaximo=100-sumador;
				    }else{
					   vm.porcentajeMaximo=0;
				    }
                }else{
				    vm.porcentajeMaximo=100;
                }
            }

            // Actualizar indicador
            function actIndicador(index){	
                vm.info={};
                $http.post('/registro/notas/materias_asignadas/periodos/indicadores/'+vm.indicadores[index].id+'/actualizar',vm.indicadores[index]).then(
				function(response){
					vm.info=response.data;
					if (vm.info.estado===true) {
                        vm.buscarIndicadores();
					};
				}
                );
            }
            
            // Eliminar indicador
            function borrarIndicador(id){
                vm.info={};
                if ($window.confirm('¿Desea eliminar el indicador? Se eliminarán los tipos de nota y las notas asociadas.')) {
				    $http.get('/registro/notas/materias_asignadas/periodos/indicadores/'+id+'/delete').then(
					   function(response){
                           vm.info=response.data;                           
                           $window.alert(vm.info.mensaje);
                           vm.buscarIndicadores();
                       });
                }
            }
            
            // Añadir un indicador
            function addIndicador(){
                vm.info={};
                $http.post('/registro/notas/materias_asignadas/periodos/indicadores/'+vm.elegido.periodo,vm.nuevoIndic).then(
                    function(response){
                        vm.info=response.data;
                        if (vm.info.estado===true) {
                            vm.nuevoIndic={};
                            vm.buscarIndicadores();
					};
				});
            }
            
            // Buscar Notas del periodo.
            function buscarNotas(){
                vm.cargando.notas=true;
                $http.get('/registro/notas/materias_asignadas/periodos/alumnos/'+$scope.periodoSeleccionado).then(
				    function(response){
					   vm.notasRaw=response.data;
					   postBuscarNotas();
				});
            }
            function postBuscarNotas(){
			for (var i = $scope.indicadores.length - 1; i >= 0; i--) {
				for (var j = $scope.indicadores[i].tipo_nota.length - 1; j >= 0; j--) {
					if ($scope.indicadores[i].tipo_nota[j].notas.length<=0) {
						$scope.crearNotaBasica($scope.indicadores[i].tipo_nota[j].id);
					};
				};
			};
			actPromedio();
            vm.cargando.notas=false;
            }
            function actPromedio(){
                vm.promedios=new Array;
			for (var x = 0; x < vm.indicadores.length; x++) {
				vm.promedios[x]={};
				vm.promedios[x].id=vm.indicadores[x].id;
				vm.promedios[x].nombre=vm.indicadores[x].nombre;
				vm.promedios[x].porcentaje=vm.indicadores[x].porcentaje;
				vm.promedios[x].alumnos=[];
				for (var i = 0; i < $scope.indicadores[x].tipo_nota[0].notas.length; i++) {//////Estoy resolviento esto
					vm.promedios[x].alumnos[i]={};
					vm.promedios[x].alumnos[i].id=$scope.indicadores[x].tipo_nota[0].notas[i].alumnos.id;
					vm.promedios[x].alumnos[i].name=$scope.indicadores[x].tipo_nota[0].notas[i].alumnos.users.name;
					vm.promedios[x].alumnos[i].lastname=$scope.indicadores[x].tipo_nota[0].notas[i].alumnos.users.lastname;
					vm.promedios[x].alumnos[i].promedio=0;
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
            }
            
            /****** Control de ventanas *******/
            
            // Selección de ventana
            function esSeleccionada(ventana){
                return vm.ventanaSeleccionada===ventana
            }
        }
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
		
		$scope.calculoDefinitiva=function(indexAlu){
			var definitiva=0;
			for (var i = $scope.promedios.length - 1; i >= 0; i--) {
				definitiva+=parseFloat($scope.promedios[i].alumnos[indexAlu].promedio)*parseFloat($scope.promedios[i].porcentaje)/100;
			}
			return definitiva;
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
		
		
		
		
		
		
	});
})();