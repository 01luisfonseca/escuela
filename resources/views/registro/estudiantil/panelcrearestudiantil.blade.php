@extends('panelgeneral')

@section('titulopanel')
<h3>Registros de estudiante</h3>
@stop

@section('cuerpopanel')

<p>Bienvenido <em>{{Auth::user()->name}} {{Auth::user()->lastname}}</em>. En esta sección podrá consultar los niveles, materias y periodos, tanto las notas como las asistencias a clase.
</p>
<script src="{{ route('public') }}/js/registro/rendimiento.js"></script>
<div ng-app="rendimiento">
	<estudiante-dir></estudiante-dir>
</div>

@stop