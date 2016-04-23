@extends('panelgeneral')

@section('titulopanel')
<h3>Boletines</h3>
@stop

@section('cuerpopanel')
<p>Bienvenido <em>{{Auth::user()->name}} {{Auth::user()->lastname}}</em>. En el siguiente espacio podrá generar los boletines de los alumnos.
</p>
<div ng-app="boletines">
	<boletin-dir></boletin-dir>
</div>
@stop