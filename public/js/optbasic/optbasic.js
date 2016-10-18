(function(){
	/**
	* @desc Directiva de asistencias automáticas desde la plataforma.
	* @example <div opt-dir></dir>
	**/
	angular
		.module('opciones',[])
		.directive('optDir', dirFn);

	function dirFn(){
		var directive={
			restrict: 'EA',
			templateUrl: '../../public/templates/optDir.html',
			controller: controller,
			controllerAs: 'vm',
			bindToController: true
		};
		return directive;

		function controller($http,$window,$interval){
			var vm=this;
			/* Variables locales */
			vm.opciones={};
			vm.newopcion={};

			/* Declaración de funciones */
			vm.getData=getData;
			vm.oculOptionElim=oculOptionElim;
			vm.delOption=delOption;
			vm.actOpcion=actOpcion;
			vm.newOpcion=newOpcion;
			

			/* Lanzamiento de funciones automáticas */
			vm.getData();

			/* Creación de funciones */
			function getData(){
				$http.get('/optgen/opciones').then(function(res){
					vm.opciones=res;
				});
			}
			function oculOptionElim(index){
				if(index<4){
					return true;
				}
				return false;
			}
			function delOption(index){
				$http.post('/optgen/opcion/'+vm.opciones.data[index].id+'/del').then(function(res){
					console.log(res);
					vm.getData();
				});
			}
			function actOpcion(index){
				$http.post('/optgen/opcion/'+vm.opciones.data[index].id+'/act',vm.opciones.data[index]).then(function(res){
					console.log(res);
					vm.opciones.data[index].visible=false;
				});
			}
			function newOpcion(){
				$http.post('/optgen/opcion/',vm.newopcion).then(function(res){
					console.log(res);
					vm.getData();
				});
			}
		}
	}
})();