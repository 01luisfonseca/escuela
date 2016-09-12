(function(){
    angular
        .module('notas',[])
        .config(function($interpolateProvider){$interpolateProvider.startSymbol('//').endSymbol('//');})
        .directive('notasDir',notasDir);
    
    function notasDir(){
        var directive={
            restrict: 'EA',
			templateUrl: '../../public/templates/notas.html',
            controller:controller,
            controllerAs:'vm',
        };
        return directive;
        
        function controller($http,$window){
            
            var vm=this;
            
            vm.porcentajeMaximo=100;    // Maximo porcentaje de indicador
            vm.ventanaSeleccionada=0;   // Ventana seleccionada para mostrar notas.
            vm.elegido={                // Que se ha elegido
                nivel:0,
                materia:0,
                periodo:0,
                indicador:0,
            };
            vm.cargando={               // Estado de carga de la aplicación
                indicadores:false,
                notas:false,
            };
            vm.info={};                 // Muestra la información de respuesta de la API
            vm.niveles={};              // Recoje los niveles del usuario
            vm.materias={};             // Recoge las materias habilitadas por el usuario
            vm.periodos={};             // Recoge los periodos según la materia elegida
            vm.indicadores={};          // Recoge los indicadores
            vm.notasRaw={};             // Recoge a los alumnos y a las notas de la API
            vm.nuevoIndic={};           // Almacena el nuevo indicador 
            vm.promedios={};            // Almacena los totalizados de los periodos.
            vm.mensajeTipo='';          // Tiene las respuestas de las actualizaciones de tipos de notas, en el modal
            
            // Funciones
            vm.inicializador=inicializador;                     // Inicializa las variables;
            vm.buscarNiveles=buscarNiveles;                     // Busca los niveles
            vm.buscarMaterias=buscarMaterias;                   // Busca las materias del nivel
            vm.buscarPeriodos=buscarPeriodos;                   // Busca periodos relacionados con materias
            vm.buscarIndicadores=buscarIndicadores;             // Busca los indicadores del periodo elegido
            vm.actIndicador=actIndicador;                       // Actualiza el indicador según el $index.
            vm.borrarIndicador=borrarIndicador;                 // Borra el indicador según el ID
            vm.addIndicador=addIndicador;                       // Añade indicador.
            vm.actTipo=actTipo;                                 // Actualiza los tipos de notas
            vm.crearTipo=crearTipo;                             // Crea los tipos de notas para un indicador.
            vm.eliminarTipo=eliminarTipo;                       // Elimina un tipo de nota
            vm.actNota=actNota;                                 // actualiza una nota.
            
            // Funciones para control de ventanas
            vm.esSeleccionada=esSeleccionada;   // Verifica que una ventana es seleccionada
            vm.selecciona=selecciona;           // Selecciona una ventana a mostrar.
            
            // Lanzamientos automáticos
            vm.buscarNiveles(); // Lanzar la búsqueda de niveles.
            
            /////////////////////////////////////////////////
            
            //Funcion que inicializa las variables. Los mismos textos de variables del controlador en general.
            function inicializador(){
                //Pendiente si se necesita
                return true;
            }
            
            //Funcion que recibe los niveles autorizados
            function buscarNiveles(){
                vm.niveles={};
                $http.get('/registro/notas/niveles_asignados').then(function(res){
                    vm.niveles=res.data;
                });
            }
            
            // Funcion que recoge las materias en niveles
            function buscarMaterias(){
                vm.materias={};
                vm.elegido.materia=0;
                vm.elegido.periodo=0;
                console.log('Nivel elegido: '+vm.elegido.nivel);
                $http.get('/registro/notas/materias_asignadas/'+vm.elegido.nivel).then(
				    function(res){
                        vm.materias=res.data;
                    });
            }
            
            // Funcion que recoge los periodos de las materias
            function buscarPeriodos(){
                vm.elegido.periodo=0;
                vm.periodos={};
                console.log('Materia elegida: '+vm.elegido.materia);
                $http.get('/registro/notas/periodos_asignados/'+vm.elegido.materia).then(
				function(data){
					vm.periodos=data.data;
				});
            }
            
            /*Aqui se acaba las funciones iniciales de carga y empiezan las funciones de indicadores*/
            
            // Funcion que recoge los indicadores, tipos de nota, notas y alumnos
            function buscarIndicadores(){
                vm.indicadores={};
                vm.cargando.indicadores=true;
                $http.get('/registro/notas/indicadores/'+vm.elegido.periodo).then(
				    function(response){
				        vm.indicadores=response.data;
                        vm.cargando.indicadores=false;
                        console.log('Indicadores: Abajo');
                        console.log(vm.indicadores);
                        porcentajeMax();
                        actPromedio();
                        console.log('Promedios: Abajo');
                        console.log(vm.promedios);
				});
            }
            
            // Calcula el promedio de los indicadores
            function promIndicadores(){
                // Calculamos el promedio de tipos de nota en cada alumno
                for(var i=0; i<vm.indicadores.length; i++){
                    for(var j=0; j<vm.indicadores[i].alumnos.length; j++){
                        var defin =0;
                        for(var k=0; k<vm.indicadores[i].alumnos[j].tipo_nota.length; k++){
                            var tempo=parseFloat(vm.indicadores[i].alumnos[j].tipo_nota[k].cal);
                            vm.indicadores[i].alumnos[j].tipo_nota[k].cal=tempo;
                            defin+=parseFloat(vm.indicadores[i].alumnos[j].tipo_nota[k].cal)
                        }
                        defin=defin/vm.indicadores[i].alumnos[j].tipo_nota.length;
                        vm.indicadores[i].alumnos[j].prom=defin;
                    }
                }
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
                vm.cargando.indicadores=true;
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
            
            // Actualiza la tabla de promedios
            function actPromedio(){
                vm.promedios={};
                // Calculamos los promedios de los indicadores
                promIndicadores();
                vm.promedios.indicadores=new Array;
                // Se asignan los indicadores disponibles al promedio
                for (var x = 0; x < vm.indicadores.length; x++) {
                    vm.promedios.indicadores[x]={};
                    vm.promedios.indicadores[x].id=vm.indicadores[x].id;
                    vm.promedios.indicadores[x].nombre=vm.indicadores[x].nombre;
                    vm.promedios.indicadores[x].porcentaje=vm.indicadores[x].porcentaje;
                }
                vm.promedios.alumnos=new Array;
                // Se agregan los alumnos disponibles al promedio
                for (var x=0; x<vm.indicadores[0].alumnos.length; x++){
                    vm.promedios.alumnos[x]={};
                    vm.promedios.alumnos[x].id=vm.indicadores[0].alumnos[x].id;
                    vm.promedios.alumnos[x].users_id=vm.indicadores[0].alumnos[x].users_id;
                    vm.promedios.alumnos[x].name=vm.indicadores[0].alumnos[x].name;
                    vm.promedios.alumnos[x].lastname=vm.indicadores[0].alumnos[x].lastname;
                    vm.promedios.alumnos[x].indicadores=vm.promedios.indicadores; // Asignar los indicadores existentes en promedio a cada alumno.
                    vm.promedios.alumnos[x].def=0;
                }
                // Calculando las definitivas de cada indicador por alumno e indicador.
                
                
                console.log('Despues del cálculo de promedios:');
                console.log(vm.promedios);
                // Cálculo de definitivas del periodo
                for(var i=0; i<vm.promedios.alumnos.length; i++){
                    for(var j=0; j<vm.promedios.alumnos[i].indicadores.length; j++){
                        var def = parseFloat(vm.promedios.alumnos[i].indicadores[j].porcentaje) * parseFloat(vm.promedios.alumnos[i].indicadores[j].def)/100;
                        vm.promedios.alumnos[i].def+=def;
                    }
                }
			}
            
            /* Fin de funciones sobre indicadores, siguen cálculo de notas */
            
            // Crear tipo de nota por indicador
            function crearTipo(indId){
                console.log('Pase por aqui');
                vm.cargando.indicadores=true;
                $http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/'+indId).then(function(res){
                    $window.alert(res.data.mensaje);
                    vm.buscarIndicadores();
                });
            }
            
            //Actualizar los tipos de notas
            function actTipo(indIndex,tipoIndex){
                $http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/' + vm.indicadores[indIndex].tipo_nota[tipoIndex].id+ '/actualizar', vm.indicadores[indIndex].tipo_nota[tipoIndex])
                    .then(function(res){
                    vm.mensajeTipo=res.data.mensaje;
                });
            }
            
            // Elimina un tipo de nota por Id.
            function eliminarTipo(tipoId){
                if($window.confirm('¿Desea eliminar el tipo de nota? Se eliminarán las notas asociadas.')){
                    vm.cargando.indicadores=true;
                    $http.get('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/'+ tipoId+ '/delete').then(function(res){
                        $window.alert(res.data.mensaje);
                        vm.buscarIndicadores();
                    });
                }
            }
            
            // Actualiza una nota
            function actNota(notaId,cal){
                $http.post('/registro/notas/materias_asignadas/periodos/indicadores/tipo_nota/notas/'+ notaId+ '/'+ cal+'/actualizar')
                    .then(function(res){
                    actPromedio();
                });
            }
            
            /****** Control de ventanas *******/
            
            // Verifica la seleccion de ventana
            function esSeleccionada(ventana){
                return vm.ventanaSeleccionada===ventana
            }
            
            // Selecciona ventana
            function selecciona(id){
                vm.ventanaSeleccionada=id;
            }
        }
    }
})();