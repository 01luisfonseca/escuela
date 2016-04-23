@extends('panelgeneral')

@section('titulopanel')
<h3>Gestión de Pagos centralizada</h3>
@stop

@section('cuerpopanel')
<p>Bienvenido {{Auth::user()->name}} {{Auth::user()->lastname}}. En el siguiente espacio podrá gestionar todos los pagos.
</p>
<div ng-app="escuela">
<div ng-controller="GeneralController as general">
	<pagos-pensiones></pagos-pensiones>
	<pagos-matriculas></pagos-matriculas>
	<otros-pagos></otros-pagos>
	<liquidacion-diaria></liquidacion-diaria>
	<nuevo-pago></nuevo-pago>
</div>
</div>
@stop