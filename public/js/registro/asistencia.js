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

		function controller(){
			var vm=this;
			/* Variables locales */
			vm.muestraHerr=false;
			vm.muestraAsis=false;

			/* Declaración de funciones */
			vm.verHerr=verHerr;
			vm.verAsis=verAsis;

			/* Lanzamiento de funciones automáticas */

			/* Creación de funciones */

			function verHerr(){
				vm.muestraHerr=!vm.muestraHerr;
			}
			function verAsis(){
				vm.muestraAsis=!vm.muestraAsis;
			}
		}
	}
})();