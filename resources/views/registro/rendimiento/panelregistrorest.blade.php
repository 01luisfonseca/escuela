@extends('panelgeneral')

@section('titulopanel')
<h3>Gestión de notas</h3>
@stop

@section('cuerpopanel')
<p>Bienvenido {{Auth::user()->name}} {{Auth::user()->lastname}}. En el siguiente espacio podrá administrar las notas de los alumnos.
</p>
<div ng-app="notas">
	<notas-dir></notas-dir>
</div>
@stop