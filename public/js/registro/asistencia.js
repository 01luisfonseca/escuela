(function(){
	/**
	* @desc Directiva de asistencias automáticas desde la plataforma.
	* @example <div asist-dir></dir>
	**/
	angular
		.module('asistencia',[])
		//.config(function($interpolateProvider){$interpolateProvider.startSymbol('//').endSymbol('//');})
		.directive('asistDir', dirFn);

	function dirFn(){
		var directive={
			restrict: 'EA',
			templateUrl: '../../public/templates/asistencia.html',
			controller: controller,
			controllerAs: 'vm',
			bindToController: true
		};
		return directive;

		function controller($http,$window,$interval){
			var vm=this;
			/* Variables locales */
			vm.muestraHerr=false;
			vm.muestraAsis=false;
			vm.clasesAsistencia='col-xs-12';
			vm.newAsisActual=0;
			vm.resultado={};
			vm.devices={};
			vm.newDevice={};
			vm.asistencias={};
			vm.infoAsis={};

			/* Declaración de funciones */
			vm.verHerr=verHerr;
			vm.verAsis=verAsis;
			vm.actDevice=actDevice;
			vm.addDevice=addDevice;
			vm.getDevices=getDevices;
			vm.delDevice=delDevice;
			vm.newAsistencias=newAsistencias;
			vm.selectNewAsis=selectNewAsis;

			/* Lanzamiento de funciones automáticas */
			vm.getDevices();
			vm.newAsistencias();
			$interval(vm.newAsistencias,5000);

			/* Creación de funciones */

			function verHerr(){
				vm.muestraHerr=!vm.muestraHerr;
				if (vm.muestraHerr) {
					vm.clasesAsistencia='col-md-8 col-xs-12';
				}else{
					vm.clasesAsistencia='col-xs-12';
				}
			}
			function verAsis(){
				vm.muestraAsis=!vm.muestraAsis;
				if (!vm.muestraAsis) {
					vm.clasesAsistencia='col-xs-12';
					vm.muestraHerr=false;
				}
			}
			function actDevice(id){
				$http.post('/registro/device/'+vm.devices.data[id].id,vm.devices.data[id]).then(function(res){
					resGen(res);
				});
			}
			function addDevice(){
				$http.post('/registro/device/nuevo',vm.newDevice).then(function(res){
					vm.newDevice={};
					resGen(res);
				});
			}
			function getDevices(){
				$http.get('/registro/device').then(function(res){
					vm.devices=res;
				});
			}
			function delDevice(id){
				 if ($window.confirm('¿Desea eliminar el dispositivo? El dispositivo no podrá generar asistencias.')){
				 	$http.get('/registro/device/'+vm.devices.data[id].id+'/delete').then(function(res){
				 		resGen(res);
				 	});
				 }
			}
			function resGen(res){
				vm.resultado=res;
				console.log(res);
				vm.getDevices();
			}
			function newAsistencias(){
				$http.get('/registro/newasistencia/'+vm.newAsisActual+'/asist').then(function(res){
					vm.asistencias=res;
					console.log(res);
					getTotalRegAsistencias();
				});
			}
			function getTotalRegAsistencias(){
				$http.get('/registro/newasistencia/info').then(function(res){
					vm.infoAsis=res;
					var pestanas=parseInt(vm.infoAsis.data.registros)/parseInt(vm.infoAsis.data.mostrados);
					vm.infoAsis.elems=[];
					pestanas=Math.floor(pestanas);
					for (var i = 0; i <= pestanas; i++) {
						vm.infoAsis.elems.push({id: i});
					}
				});
			}
			function selectNewAsis(pestana){
				vm.newAsisActual=pestana*50;
				vm.newAsistencias();
			}
		}
	}
})();