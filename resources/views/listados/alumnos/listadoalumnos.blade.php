@extends('panelgeneral')

@section('titulopanel')
<h3>Listado de alumnos en niveles</h3>
@stop

@section('cuerpopanel')
<p>Bienvenido {{Auth::user()->name}} {{Auth::user()->lastname}}. En el siguiente espacio podrá generar los listados de alumnos por nivel.
</p>
<div ng-app="listas">
	<listado-alumnos></listado-alumnos>
</div>
@stop