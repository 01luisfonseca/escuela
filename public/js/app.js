(function(){
	var app=angular.module('escuela',['pagos','angular-confirm','ui.bootstrap']).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	
})();