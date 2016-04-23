(function(){
	var app=angular.module('pagos',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});

	app.directive('pagosPensiones',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/pensiones.html'
		};
	});

	app.directive('pagosMatriculas',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/matriculas.html'
		};
	});

	app.directive('otrosPagos',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/otrospagos.html'
		};
	});

	app.directive('liquidacionDiaria',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/liquidaciondiaria.html',
		};
	});

	app.directive('nuevoPago',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/nuevopago.html'
		};
	});

	app.controller('LiqCajaController',function($scope,$http){
		$scope.pOpagos={};
		$scope.pPensiones={};
		$scope.pMatriculas={};
		$scope.Osumatoria=0;
		$scope.Psumatoria=0;
		$scope.Msumatoria=0;
		this.obtenerFechaAhora=function(){
				this.objFecha=new Date();
				$scope.fechaAhora= new Date(this.objFecha.getTime()-this.objFecha.getTimezoneOffset()*60000);
		};
		this.imprimetirilla=function(){
			var fechacorta=$scope.fechaAhora.getFullYear()+'-'+$scope.fechaAhora.getMonth()+'-'+$scope.fechaAhora.getDate();
			var suma=$scope.Psumatoria+$scope.Msumatoria+$scope.Osumatoria;
			return '/institucion/pago/cierrecaja/'+fechacorta
			+'/'+$scope.Psumatoria
			+'/'+$scope.Msumatoria
			+'/'+$scope.Osumatoria
			+'/'+suma;
		};
		$scope.sumatorias=function(){
				$scope.Osumatoria=0;
				$scope.Psumatoria=0;
				$scope.Msumatoria=0;
				$scope.Osumatoria=$scope.sumador($scope.pOpagos);
				$scope.Psumatoria=$scope.sumador($scope.pPensiones);
				$scope.Msumatoria=$scope.sumador($scope.pMatriculas);
		};
		$scope.sumador=function(arreglo){
			var resultado=0;
			for (var i = arreglo.length - 1; i >= 0; i--) {
				resultado+=parseInt(arreglo[i].valor);
			};
			return resultado;
		};
		this.verificarFechas=function(){
				this.buscarPensionPorFecha();
				this.buscarMatriculasPorFecha();
				this.buscarOtrosPagosPorFecha();
		};
		this.buscarPensionPorFecha=function(){
				var fecha={};
				fecha.fecha=$scope.fechaAhora;
				$http.post('/institucion/pago/pension/porfecha',fecha).then(
					function(response){
						$scope.pPensiones=response.data;
						$scope.sumatorias();
					},
					function(response){
						$scope.pPensiones=registros=response.data || 'Solicitud fallida';
					}
				);
		};
		this.buscarMatriculasPorFecha=function(){
				var fecha={};
				fecha.fecha=$scope.fechaAhora;
				$http.post('/institucion/pago/matricula/porfecha',fecha).then(
					function(response){
						$scope.pMatriculas=response.data;
						$scope.sumatorias();
					},
					function(response){
						$scope.pMatriculas=registros=response.data || 'Solicitud fallida';
					}
				);
		};
		this.buscarOtrosPagosPorFecha=function(){
				var fecha={};
				fecha.fecha=$scope.fechaAhora;
				$http.post('/institucion/pago/otros/porfecha',fecha).then(
					function(response){
						$scope.pOpagos=response.data;
						$scope.sumatorias();
					},
					function(response){
						$scope.pOpagos=registros=response.data || 'Solicitud fallida';
					}
				);
		};
		this.textoNombre=function(nombre,apellido){
				if(nombre==null || nombre==""){
					return '<<Alumno eliminado>>';
				}
				return nombre+' '+apellido;
		};
		this.tLink=function(objetivo,lugar){
			return '/institucion/'+lugar+'/editar/'+objetivo;
		};
		this.mostrar=false;
		this.mostrarVentana=function(){
				if(this.mostrar==true){
					this.mostrar=false;
				}else{
					this.mostrar=true;
				}
		};
		$scope.fechaAhora=new Date();
		this.obtenerFechaAhora();
		$scope.temporal={};
		this.verificarFechas();
	});

	app.controller('GeneralController',function(){
		this.activewindow='0';
		this.setActiveWindow=function(ventana){
			this.activewindow=ventana;
		};
	});

	app.controller('PensionCtrl',function($scope,$http){
		this.mostrar=false;
		$scope.predicate='';
		$scope.reverse=false;
		$scope.order = function(predicate) {
    		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
    		$scope.predicate = predicate;
  		};
		this.mostrarVentana=function(){
			if(this.mostrar==true){
				this.mostrar=false;
			}else{
				this.mostrar=true;
				this.buscarRest();
			}
		};
		this.buscarRest=function(){
			$http.get('/institucion/pago/pensiones').then(
				function(response){
					$scope.registros=response.data;
				},
				function(response){
					$scope.registros=response.data || 'Solicitud fallida';
				}
			);
		};
		this.textoNombre=function(nombre,apellido){
			if(nombre==null || nombre==""){
				return '<<Alumno eliminado>>';
			}
			return nombre+' '+apellido;
		};
		this.tLink=function(objetivo){
			return '/institucion/pension/editar/'+objetivo;
		};
	});

	app.controller('MatriculaCtrl',function($scope,$http){
		this.mostrar=false;
		$scope.predicate='';
		$scope.reverse=false;
		$scope.order = function(predicate) {
    		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
    		$scope.predicate = predicate;
  		};
		this.mostrarVentana=function(){
			if(this.mostrar==true){
				this.mostrar=false;
			}else{
				this.mostrar=true;
				this.buscarRest();
			}
		};
		this.buscarRest=function(){
			$http.get('/institucion/pago/matriculas').then(
				function(response){
					$scope.registros=response.data;
				},
				function(response){
					$scope.registros=response.data || 'Solicitud fallida';
				}
			);
		};
		this.textoNombre=function(nombre,apellido){
			if(nombre==null || nombre==""){
				return '<<Alumno eliminado>>';
			}
			return nombre+' '+apellido;
		};
		this.tLink=function(objetivo){
			return '/institucion/matricula/editar/'+objetivo;
		};
	});

	app.controller('NuevoPagoCtrl',function($scope,$http,$timeout,$window){
		this.mostrar=true;
		$scope.alumnos={};
		this.pago={};
		$scope.meses={};
		$scope.registroPago={};
		$scope.historial={};
		$http.get('/institucion/meses').then(
			function(response){
				$scope.meses=response.data;
			},
			function(response){
				$scope.meses=response.data || 'Solicitud de meses fallida';
			}
		);
		this.verificarRestante=function(){
			switch(this.pago.tipo) {
    			case 'pension':
        			this.pago.restante=parseInt(this.pago.valorPensionInicial)-this.pago.valor;
        			break;
    			case 'matricula':
        			this.pago.restante=parseInt(this.pago.valorMatriculaInicial)-this.pago.valor;
        			break;
			};
		};
		this.verificarPago=function(){
			switch(this.pago.tipo) {
    			case 'pension':
        			this.pago.valor=parseInt(this.pago.valorPensionInicial);
        			break;
    			case 'matricula':
        			this.pago.valor=parseInt(this.pago.valorMatriculaInicial);
        			break;
			} ;
			this.verificarRestante();
			this.actualizarHistorial(this.pago.alumnos_id);
		};
		this.esSeleccion=function(seleccion){
			return this.pago.alumnos_id===seleccion;
		};
		this.seleccionarAlumno=function(id,pension,matricula){
			this.pago.alumnos_id=id;
			this.pago.valorPensionInicial=pension;
			this.pago.valorMatriculaInicial=matricula;
			this.verificarPago();
			this.actualizarHistorial(this.pago.alumnos_id);
		};
		this.addPago=function(){
			var url='';
			switch(this.pago.tipo){
				case 'pension':
					url='/institucion/pago/pensiones';
					break;
				case 'matricula':
					url='/institucion/pago/matriculas';
					break;
				case 'otros':
					url='/institucion/pago/otros';
					break;
			};
			var factura=this.pago.factura;
			var tipo=this.pago.tipo;
			$http.post(url,this.pago).then(
				function(response){
					$scope.registroPago=response.data;
					if ($scope.registroPago.estado===true) {
						$scope.imprimirtirilla(tipo,factura);
					};
				},
				function(response){
					$scope.registroPago.mensaje = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
		$scope.imprimirtirilla=function(tipo,factura){
			var urlgen='/institucion/';
			switch(tipo){
				case 'pension':
					urlgen+='pension';
					break;
				case 'matricula':
					urlgen+='matricula';
					break;
				case 'otros':
					urlgen+='opagos';
				break;
			};
			var urlfac=urlgen+'/factura/'+factura;
			var respuesta={};
			$http.get(urlfac).then(
				function (response){
					respuesta=response;
					$window.open(urlgen+'/imprimir/'+respuesta.data.id);
					$scope.registroPago.estado
				},
				function (response){
					respuesta=response;
				});
			this.pago={};
		};
		this.esOtros=function(){
			if (this.pago.tipo=='otros') {
				return true;
			}else{
				return false;
			}
		};
		this.esPension=function(){
			if (this.pago.tipo=='pension') {
				return true;
			}else{
				return false;
			}
		};
		this.mostrarVentana=function(){
			if(this.mostrar==true){
				this.mostrar=false;
			}else{
				this.mostrar=true;
			}
		};
		this.buscarAlumnos=function(buscado){
			$http.get('/institucion/alumnos/'+buscado).then(
				function(response){
					$scope.alumnos=response.data;
				},
				function(response){
					$scope.alumnos=response.data || 'Solicitud de meses fallida';
				}
			);			
		};
		this.actualizarHistorial=function(alumnos_id){
			var url='';
			switch(this.pago.tipo){
				case 'pension':
					url='/institucion/pago/pensiones/'+alumnos_id;
					break;
				case 'matricula':
					url='/institucion/pago/matriculas/'+alumnos_id;
					break;
				case 'otros':
					url='/institucion/pago/otros/'+alumnos_id;
					break;
			};
			$http.get(url,this.pago).then(
				function(response){
					$scope.historial=response.data;
				},
				function(response){
					$scope.historial.mensaje = response.status || 'No hay comunicación con el servidor';
				}
			);
		};
	});

	app.controller('OtrosPagosCtrl',function($scope,$http){
		this.mostrar=false;
		$scope.predicate='';
		$scope.reverse=false;
		$scope.order = function(predicate) {
    		$scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
    		$scope.predicate = predicate;
  		};
		$scope.actualizarOtrosP=function(){
			$http.get('/institucion/pago/otros').then(
				function(response){
					$scope.registros=response.data;
				},
				function(response){
					$scope.registros=response.data || 'Solicitud fallida';
				}
			);
		};
		this.mostrarVentana=function(){
			if(this.mostrar==true){
				this.mostrar=false;
			}else{
				this.mostrar=true;
				this.buscarRest();
			}
		};
		this.buscarRest=function(){
			$http.get('/institucion/pago/otros').then(
				function(response){
					$scope.registros=response.data;
				},
				function(response){
					$scope.registros=response.data || 'Solicitud fallida';
				}
			);
		};
		this.textoNombre=function(nombre,apellido){
			if(nombre==null || nombre==""){
				return '<<Alumno eliminado>>';
			}
			return nombre+' '+apellido;
		};
		this.borrar=function(id){
			$http.get('/institucion/pago/otros/'+id+'/delete').then(
				function(response){
					$scope.registroOtros=response.data;
					$scope.actualizarOtrosP();
				},
				function(response){
					$scope.registroOtros.mensaje = response.status || 'No hay comunicación con el servidor';
				}
			);
			this.buscarRest();
		};
	});
})();