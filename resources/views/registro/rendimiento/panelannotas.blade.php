@extends('panelgeneral')

@section('titulopanel')
<h3>Análisis de notas</h3>
@stop

@section('cuerpopanel')
<p>Bienvenido <em>{{Auth::user()->name}} {{Auth::user()->lastname}}</em>. En el siguiente espacio podrá generar los análisis de notas de la institución.
</p>
<div ng-app="annotas">
	<annotas-dir></annotas-dir>
</div>
@stop