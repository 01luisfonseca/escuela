(function(){
	var app=angular.module('annotas',[]).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	app.directive('annotasDir',function(){
		return {
			restrict: 'E',
			templateUrl: '../../public/templates/annotas.html'
		};
	});
	app.controller('exportCtrl',function($scope,$http,$window){
		$scope.anios={};
		$scope.urlExcel='/registro/analisis/notas/excel';
		$scope.DescargaExcel=function(anio){
			$window.open($scope.urlExcel+'/'+anio);
		};
	});
})();