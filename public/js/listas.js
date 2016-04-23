(function(){
	var app=angular.module('listas',['listados']).config(function($interpolateProvider){
    		$interpolateProvider.startSymbol('//').endSymbol('//');
	});
	
})();